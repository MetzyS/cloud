<?php

use App\Models\M_Home;
use App\Validators\Verification;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../vendor/autoload.php';

class Home
{
    protected $model;
    public function __construct()
    {
        $this->model = new M_Home();
    }


    /**
     * Si l'utilisateur est connecté : affiche la vue home/index.php
     * avec les données des fichiers récents.
     * Sinon utilise la méthode connect()
     */
    public function index()
    {
        if (isset($_SESSION['error'])) {
            $this->model->view('template/404', []);
            exit();
        } else {
            if (isset($_SESSION['user'])) {
                $recentFiles = $this->model->getRecentFiles();
                $_SESSION['storage_infos'] = $this->model->serverStorageInfos();
                $this->model->view('home/index', [
                    'recent_files' => $recentFiles,
                ]);
            } else {
                $this->model->redirect('home/connect');
            }
        }
    }


    /**
     * Si l'utilisateur est connecté : utilise la méthode index()
     * Sinon affiche la vue home/connexion/email.php
     */
    public function connect()
    {
        if (isset($_SESSION['user'])) {
            $this->model->redirect('home/index');
        } else {
            $this->model->view('home/connexion/email', []);
        }
    }


    /**
     * Vérifie l'existence du mail dans la BDD
     * LIGNE .. A SUPPRIMER LORS DU PASSAGE EN DEV. CETTE LIGNE PERMET DE SE CONNECTER DIRECTEMENT SANS MOT DE PASSE
     */
    public function login()
    {
        if (isset($_SESSION['user'])) {
            $this->model->redirect('home/index');
        } else {
            if (isset($_SESSION['mail'])) {
                $mail = $_SESSION['mail'];
                if (Verification::mailRegex($mail)) {
                    $usermail = $this->model->mailCheck($mail);

                    if (empty($usermail)) {
                        $message = 'Adresse mail incorrecte.';
                        $this->model->view('home/connexion/email', [
                            'message' => $message,
                        ]);
                    } else {
                        $this->model->view('home/connexion/password', [
                            'user' => $usermail,
                        ]);
                    }
                } else {
                    $message = "Le format de l'adresse mail saisi est incorrect.";
                    $this->model->view('home/connexion/email', [
                        'message' => $message,
                    ]);
                }
            } else {
                $this->model->redirect('home/connect');
            }
        }
    }


    /**
     * Vérifie si le mot de passe entré correspond à l'adresse mail dans la BDD
     * Stocke les informations de $user dans $_SESSION['user']
     */
    public function password()
    {
        if (isset($_SESSION['mail']) && isset($_SESSION['password'])) {
            $mail = $_SESSION['mail'];
            $password = $_SESSION['password'];

            if (Verification::passwordRegex($password)) {
                $user = $this->model->passwordCheck($mail, $password);

                if (empty($user)) {
                    $message = 'Mot de passe incorrect.';
                    $this->model->view('home/connexion/password', [
                        'message' => $message,
                    ]);
                } else {
                    if (isset($_SESSION['user'])) {
                        unset($_SESSION['user']);
                        $_SESSION['user'] = $user;
                    } else {
                        $_SESSION['user'] = $user;
                    }
                    unset($_SESSION['mail']);
                    unset($_SESSION['password']);
                    $_SESSION['storage_infos'] = $this->model->serverStorageInfos();
                    $this->model->redirect('home/index');
                }
            } else {
                $message = "Mot de passe incorrect. 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial.";
                $this->model->view('home/connexion/password', [
                    'message' => $message,
                ]);
            }
        } else {
            unset($_SESSION['mail']);
            unset($_SESSION['password']);
            $this->model->redirect('home/login');
        }
    }

    /**
     * Permet d'afficher la page password-forgot si le client n'est pas déjà connecté
     */
    public function forgot()
    {
        if (isset($_SESSION['user'])) {
            $this->model->redirect('home/index');
        } else {
            $this->model->view('home/connexion/password-forgot', []);
        }
    }

    /**
     * Gestion de la page password-forgot
     * Change le mot de passe de l'utilisateur (mot de passe aléatoire)
     * Envoi le mot de passe par mail
     */
    public function passwordForgot()
    {
        if (isset($_SESSION['user'])) {
            $this->model->redirect('home/index');
        } else {
            if (isset($_SESSION['mail'])) {
                $mail = $_SESSION['mail'];
                unset($_SESSION['mail']);

                if (Verification::mailRegex($mail)) {
                    $usermail = $this->model->mailCheck($mail);

                    if (empty($usermail)) {
                        $error = 'Adresse mail incorrecte.';
                        $this->model->view('home/connexion/password-forgot', [
                            'error' => $error,
                        ]);
                    } else {
                        $newPassword = $this->model->randomPassword();
                        $message = $this->model->newRandomPassword($mail, $newPassword);

                        if ($message) {
                            try {
                                $pwMail = new PHPMailer(true);
                                $subject = 'Mot de passe oublié';
                                //Server settings
                                $pwMail->SMTPDebug = 0;
                                $pwMail->isSMTP();
                                $pwMail->SMTPAuth = true;

                                // SMTP Connection
                                $pwMail->Host = "smtp.host.com";
                                $pwMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                                $pwMail->Port = 587;

                                $pwMail->Username = "yourmail@mail.com"; // server mail
                                $pwMail->Password = "yourpassword";

                                // MAIL Send
                                $pwMail->setFrom('yourmail@mail.com', '');
                                $pwMail->addAddress($mail, ''); // reciever mail (your mail)

                                $pwMail->isHTML(true);
                                $pwMail->Subject = $subject;
                                $pwMail->Body = $message . "<br>" . $mail;

                                $pwMail->CharSet = 'UTF-8';
                                $pwMail->Encoding = 'base64';

                                $pwMail->send();
                                $confirm = 'Votre mot de passe à été réinitialisé. Veuillez consulter votre boîte mail.';
                            } catch (Exception $e) {
                                echo "Message could not be sent. Mailer Error: {$pwMail->ErrorInfo}";
                            }
                            $this->model->view('home/connexion/email', [
                                'confirm' => $confirm
                            ]);
                        }
                    }
                } else {
                    $error = "Le format de l'adresse mail saisi est incorrect.";
                    $this->model->view('home/connexion/password-forgot', [
                        'error' => $error,
                    ]);
                }
            } else {
                $this->model->redirect('home/forgot');
            }
        }
    }

    /**
     * Affiche les fichiers d'une catégorie si un id est spécifié (id_categorie)
     * Affiche tout les fichiers si aucun id
     * @param int $id = ID Catégorie
     */
    public function show(int $id = null)
    {
        if (isset($_SESSION['user'])) {
            $category = $id;
            $files = $this->model->getCategory($category);
            $this->model->view('home/show', [
                'files' => $files,
            ]);
        } else {
            $this->model->redirect('home/connect');
        }
    }
}
