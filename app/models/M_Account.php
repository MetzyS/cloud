<?php

namespace App\Models;


use App\Core\Model;
use App\Core\DB;
use \PDO;
use \Exception;

class M_Account extends Model
{
    /**
     * Récupère les informations des comptes dans la BDD
     *
     * @return array
     */
    public function getAccounts(int $id = null)
    {
        if (is_null($id)) {
            try {
                $db = DB::getPdoLow();
                $sql = $db->query("SELECT id_user, user_name, user_surname, email, role, superadmin FROM user");
                $sql->execute();

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
                $sql = $db->prepare("SELECT id_user, user_name, user_surname, email, role FROM user WHERE id_user = :id");
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
     * Mise à jour des informations des comptes
     * Nom, premom, email, mot de passe et role
     *
     * @param array $array
     * @return string message
     */
    public function updateAccounts(array $array)
    {
        $data = [];
        foreach ($array as $key => $values) {
            if (isset($values['password']) && !empty($values['password'])) {
                try {
                    $psw = password_hash($values['password'], PASSWORD_BCRYPT);
                    $db = DB::getPdo();
                    $sql = $db->prepare('UPDATE user SET user_name = :name, user_surname = :surname, email = :email, password = :password, role = :role WHERE id_user = :id');
                    $sql->bindParam(':name', $values['user_name']);
                    $sql->bindParam(':surname', $values['user_surname']);
                    $sql->bindParam(':email', $values['email']);
                    $sql->bindParam(':password', $psw);
                    $sql->bindParam(':role', $values['role']);
                    $sql->bindParam(':id', $key);

                    $sql->execute();
                } catch (Exception $e) {
                    if ($e->getCode() == 23000) {
                        $errorCode = $e->getCode();
                        $_SESSION['create_error'] = "L'adresse mail " . $values['email'] . " est déjà utilisée. Code d'erreur: " . $errorCode;
                        $this->redirect('account/index');
                        exit();
                    } else {
                        // handle other types of exceptions here
                        $errorCode = $e->getCode();
                        $_SESSION['create_error'] = "Une erreur est survenue lors de la création d'un compte. Code d'erreur: " . $errorCode;
                        $this->redirect('account/index');
                        exit();
                    }
                }
            } else if (isset($values['password']) && empty($values['password'])) {
                try {
                    $db = DB::getPdo();
                    $password = $this->randomPassword(); // On crée un mot de passe aléatoire
                    $psw = password_hash($password, PASSWORD_BCRYPT); // On hash le mot de passe
                    $sql = $db->prepare('UPDATE user SET user_name = :name, user_surname = :surname, email = :email, password = :password, role = :role WHERE id_user = :id');
                    $sql->bindParam(':name', $values['user_name']);
                    $sql->bindParam(':surname', $values['user_surname']);
                    $sql->bindParam(':email', $values['email']);
                    $sql->bindParam(':password', $psw);
                    $sql->bindParam(':role', $values['role']);
                    $sql->bindParam(':id', $key);

                    $sql->execute();
                    $data['update_password'][$values['email']] = $password; // On affiche le mot de passe non crypté
                } catch (Exception $e) {
                    if ($e->getCode() == 23000) {
                        $errorCode = $e->getCode();
                        $_SESSION['create_error'] = "L'adresse mail " . $values['email'] . " est déjà utilisée. Code d'erreur: " . $errorCode;
                        $this->redirect('account/index');
                        exit();
                    } else {
                        // handle other types of exceptions here
                        $errorCode = $e->getCode();
                        $_SESSION['create_error'] = "Une erreur est survenue lors de la création d'un compte. Code d'erreur: " . $errorCode;
                        $this->redirect('account/index');
                        exit();
                    }
                }
            } else {
                try {
                    $db = DB::getPdo();
                    $sql = $db->prepare('UPDATE user SET user_name = :name, user_surname = :surname, email = :email, role = :role WHERE id_user = :id');
                    $sql->bindParam(':name', $values['user_name']);
                    $sql->bindParam(':surname', $values['user_surname']);
                    $sql->bindParam(':email', $values['email']);
                    $sql->bindParam(':role', $values['role']);
                    $sql->bindParam(':id', $key);

                    $sql->execute();
                } catch (Exception $e) {
                    if ($e->getCode() == 23000) {
                        $errorCode = $e->getCode();
                        $_SESSION['create_error'] = "L'adresse mail " . $values['email'] . " est déjà utilisée. Code d'erreur: " . $errorCode;
                        $this->redirect('account/index');
                        exit();
                    } else {
                        // handle other types of exceptions here
                        $errorCode = $e->getCode();
                        $_SESSION['create_error'] = "Une erreur est survenue lors de la création d'un compte. Code d'erreur: " . $errorCode;
                        $this->redirect('account/index');
                        exit();
                    }
                }
            }
        }
        $data['update_message'] = "Les modifications ont été enregistrées.";
        return $data;
    }

    /**
     * Créer un compte dans la base de données
     * 
     * @param string $nom
     * @param string $prenom
     * @param string $email
     * @param string $mdp
     * @param string $role 'user' (par défaut) or 'admin'
     * 
     * @return string $data
     */
    public function createAccount(string $nom, string $prenom, string $email, string $mdp, string $role = 'user')
    {
        try {
            $db = DB::getPdo();
            $sql = $db->prepare(
                'INSERT INTO user (user_name, user_surname, email, password, role)
                VALUES (:prenom, :nom, :email, :mdp, :role)'
            );
            $sql->bindParam(':prenom', $nom);
            $sql->bindParam(':nom', $prenom);
            $sql->bindParam(':email', $email);
            $sql->bindParam(':mdp', $mdp);
            $sql->bindParam(':role', $role);

            $sql->execute();

            $data = 'Le compte à bien été crée.';
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['create_error'] = "Une erreur est survenue lors de la création d'un compte. Code d'erreur: " . $errorCode;
            $this->redirect('account/index');
            exit();
        }
        return $data;
    }

    /**
     * Création de nouveaux comptes
     * Si aucun mot de passe n'est fourni, crée un mot de passe aléatoire
     *
     * @param array $array
     * @return array message et mots de passe aléatoire 
     */
    public function createAccounts(array $array)
    {
        $data = [];
        $i = 0; // Compteur pour le nombre de comptes crées au total 
        $j = 0; // Compteur pour le nombre de comptes crées avec un mot de passe aléatoire 
        foreach ($array as $key => $values) {
            //Verification des inputs obligatoires (nom, prénom, email)
            if (!empty($values['user_name']) && !empty($values['user_surname']) && !empty($values['email'])) {
                // Si un mot de passe a été saisi
                if (isset($values['password']) && !empty($values['password'])) {
                    try {
                        $psw = password_hash($values['password'], PASSWORD_BCRYPT);
                        $db = DB::getPdo();
                        $sql = $db->prepare('
                        INSERT INTO user (id_user, user_name, user_surname, email, password, role)
                        VALUES (:id, :name, :surname, :email, :password, :role)');
                        $sql->bindParam(':id', $key);
                        $sql->bindParam(':name', $values['user_name']);
                        $sql->bindParam(':surname', $values['user_surname']);
                        $sql->bindParam(':email', $values['email']);
                        $sql->bindParam(':password', $psw);
                        $sql->bindParam(':role', $values['role']);

                        $sql->execute();

                        $i += 1;
                    } catch (Exception $e) {
                        if ($e->getCode() == 23000) {
                            $errorCode = $e->getCode();
                            $_SESSION['create_error'] = "L'adresse mail " . $values['email'] . " est déjà utilisée. Code d'erreur: " . $errorCode;
                            $this->redirect('account/index');
                            exit();
                        } else {
                            // handle other types of exceptions here
                            $errorCode = $e->getCode();
                            $_SESSION['create_error'] = "Une erreur est survenue lors de la création d'un compte. Code d'erreur: " . $errorCode;
                            $this->redirect('account/index');
                            exit();
                        }
                    }
                } else {
                    try {
                        // Si mot de passe vide 
                        $password = $this->randomPassword(); //On crée un mot de passe aléatoire 
                        $psw = password_hash($password, PASSWORD_BCRYPT); //On hash le mot de passe
                        $db = DB::getPdo();
                        $sql = $db->prepare('
                        INSERT INTO user (id_user, user_name, user_surname, email, password, role)
                        VALUES (:id, :name, :surname, :email, :password, :role)');
                        $sql->bindParam(':id', $key);
                        $sql->bindParam(':name', $values['user_name']);
                        $sql->bindParam(':surname', $values['user_surname']);
                        $sql->bindParam(':email', $values['email']);
                        $sql->bindParam(':password', $psw);
                        $sql->bindParam(':role', $values['role']);

                        $sql->execute();

                        $data['password'][$values['email']] = $password; // On affiche le mot de passe non crypté
                        $j += 1;
                        $i += 1;
                    } catch (Exception $e) {
                        if ($e->getCode() == 23000) {
                            $errorCode = $e->getCode();
                            $_SESSION['create_error'] = "L'adresse mail " . $values['email'] . " est déjà utilisée. Code d'erreur: " . $errorCode;
                            $this->redirect('account/index');
                            exit();
                        } else {
                            // handle other types of exceptions here
                            $errorCode = $e->getCode();
                            $_SESSION['create_error'] = "Une erreur est survenue lors de la création d'un compte. Code d'erreur: " . $errorCode;
                            $this->redirect('account/index');
                            exit();
                        }
                    }
                }
            } else {
                $data['error'] = "Erreur";
            }
        }
        $data['message'] = "Vous avez crée $i nouveau(x) compte(s).";
        if ($j >= 1) {
            $data['message'] .= " Dont $j compte(s) avec un mot de passe aléatoire.";
        }
        return $data;
    }


    /**
     * Supprime un compte de la BDD (via ID)
     * Vérifie au préalable si l'ID existe dans la BDD, si l'ID n'existe pas, envoi un message d'erreur*
     * @param int $id = ID Utilisateur
     * @return string $data = Message confirmation/erreur
     */
    public function deleteAccount(int $id)
    {
        $account = $this->getAccounts($id);
        if (!$account) {
            $data = "Aucun compte correspondant à l'ID: " . $id . '.';
        } else {
            try {
                $db = DB::getPdo();
                $sql = $db->prepare('DELETE FROM user WHERE id_user = :id');
                $sql->bindParam(':id', $id);

                $sql->execute();

                $data = 'Le compte numéro ' . $id . ' a bien été supprimé.';
            } catch (Exception $e) {
                $errorCode = $e->getCode();
                $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                $this->redirect('account/index');
                exit();
            }
        }

        return $data;
    }
}
