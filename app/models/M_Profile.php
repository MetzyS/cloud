<?php

namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use \PDO;
use \Exception;


class M_Profile extends Model
{
    /**
     * Modifie les informations de l'utilisateur dans la BDD
     *
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string|null $psw
     * @return string $message 
     */
    public function modifyUser(string $name, string $surname, string $email, string $psw = null)
    {
        $old_email = $_SESSION['user']['email'];
        $db = DB::getPdo();
        if (is_null($psw)) {
            try {
                $sql = $db->prepare('
                UPDATE user
                SET user_name = :name, user_surname = :surname, email = :email 
                WHERE email = :old_email');
                $sql->bindParam(':name', $name);
                $sql->bindParam(':surname', $surname);
                $sql->bindParam(':email', $email);
                $sql->bindParam(':old_email', $old_email);
                $sql->execute();
            } catch (Exception $e) {
                if ($e->getCode() == 23000) {
                    $errorCode = $e->getCode();
                    $_SESSION['update_error'] = "L'adresse mail " . $email . " est déjà utilisée. Code d'erreur: " . $errorCode;
                    $this->redirect('profile/index');
                    exit();
                } else {
                    // handle other types of exceptions here
                    $errorCode = $e->getCode();
                    $_SESSION['update_error'] = "Une erreur est survenue lors de la création d'un compte. Code d'erreur: " . $errorCode;
                    $this->redirect('profile/index');
                    exit();
                }
            }
        } else {
            try {
                $psw = password_hash($psw, PASSWORD_BCRYPT);
                $sql = $db->prepare('
                UPDATE user
                SET user_name = :name, user_surname = :surname, email = :email, password = :psw 
                WHERE email = :old_email');
                $sql->bindParam(':name', $name);
                $sql->bindParam(':surname', $surname);
                $sql->bindParam(':email', $email);
                $sql->bindParam(':psw', $psw);
                $sql->bindParam(':old_email', $old_email);
                $sql->execute();
            } catch (Exception $e) {
                if ($e->getCode() == 23000) {
                    $errorCode = $e->getCode();
                    $_SESSION['update_error'] = "L'adresse mail " . $email . " est déjà utilisée. Code d'erreur: " . $errorCode;
                    $this->redirect('profile/index');
                    exit();
                } else {
                    // handle other types of exceptions here
                    $errorCode = $e->getCode();
                    $_SESSION['update_error'] = "Une erreur est survenue lors de la création d'un compte. Code d'erreur: " . $errorCode;
                    $this->redirect('profile/index');
                    exit();
                }
            }
        }
        $data = 'Vos modifications ont bien été prises en compte.';
        return $data;
    }

    /**
     * Récupère les informations de l'utilisateur
     *
     * @param string $email
     * @return array $data
     */
    public function getUser(string $email)
    {
        try {
            $db = DB::getPdoLow();
            $sql = $db->prepare('
            SELECT id_user, user_name, user_surname, email, role, superadmin
            FROM user
            WHERE email = :email;
            ');
            $sql->bindParam(':email', $email);
            $sql->execute();

            $data = $sql->fetch(PDO::FETCH_NAMED);
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('account/index');
            exit();
        }

        return $data;
    }
}
