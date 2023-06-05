<?php

namespace App\Models;


use App\Core\Model;
use App\Core\DB;
use \PDO;
use \Exception;

class M_Home extends Model
{
    /**
     * Verifie si l'email existe dans la BDD 
     *
     * @param string $mail
     * @return array mail si existe / tableau vide si n'existe pas
     */
    public function mailCheck(string $mail)
    {
        try {
            $db = DB::getPdoLow();
            $sql = $db->prepare('SELECT email FROM user WHERE email = :email');
            $sql->bindParam(':email', $mail);
            // éxecution de la requête
            $sql->execute();

            $data = $sql->fetchAll(PDO::FETCH_NAMED);
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/login');
            exit();
        }

        return $data;
    }

    /**
     * Vérifie si le mot de passe correspond à l'email dans la BDD
     * 
     * @param string $mail Email utilisateur
     * @param string $password Mot de passe entré par l'utilisateur
     * @return array informations trouvés
     */
    public function passwordCheck(string $mail, string $password)
    {
        try {
            $db = DB::getPdoLow();
            $sql = $db->prepare('SELECT id_user, user_name, user_surname, password, email, role, superadmin FROM user WHERE email = :email');
            $sql->bindParam(':email', $mail);
            $sql->execute();

            $data = $sql->fetch(PDO::FETCH_NAMED);

            // A supprimer lors du passage en dev, lignes 56 à 60
            if ($password = 'Test123@***') {
                unset($data['password']);
                return $data;
            }

            if ($data && password_verify($password, $data['password'])) {
                unset($data['password']);
                return $data;
            }
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('account/index');
            exit();
        }

        return false;
    }

    /**
     * Récupère les informations des catégories dans la base de donnée (via leur ID si spécifiée)
     * @param int|null $id = id de la catégorie
     * @return array $data = Résultat de la requête
     */
    public function getCategory(int $id = null)
    {
        if (is_null($id)) {
            try {
                $db = DB::getPdoLow();
                $sql = 'SELECT * FROM file JOIN category ON file.category_id = category.id_category';
                $sql = $db->query($sql);

                $data = $sql->fetchAll(PDO::FETCH_NAMED);
            } catch (Exception $e) {
                $errorCode = $e->getCode();
                $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                $this->redirect('account/index');
                exit();
            }
        } else {
            try {
                $db = DB::getPdoLow();
                $sql = $db->prepare('
                SELECT *
                FROM file
                JOIN category
                ON file.category_id = category.id_category
                WHERE category_id = :id');

                $sql->bindParam(':id', $id);
                $sql->execute();

                $data = $sql->fetchAll(PDO::FETCH_NAMED);
            } catch (Exception $e) {
                $errorCode = $e->getCode();
                $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                $this->redirect('account/index');
                exit();
            }
        }
        return $data;
    }

    /**
     * Créer un nouveau mot de passe aléatoire pour un utilisateur
     * @param string $email = adresse mail de l'utilisateur
     * @param string $newPassword = nouveau mot de passe
     */
    public function newRandomPassword($email, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $message = 'Bonjour, voici votre nouveau mot de passe pour vous connecter a la plateforme : ' . $newPassword;

        try {
            $db = DB::getPdo();
            $sql = $db->prepare('UPDATE user SET password = :password WHERE email = :email');
            $sql->bindParam(':password', $hashedPassword);
            $sql->bindParam(':email', $email);
            $sql->execute();
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('account/index');
            exit();
        }
        return $message;
    }
}
