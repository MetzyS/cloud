<?php

use App\Models\M_Profile;
use App\Validators\Verification;

class Profile
{
    protected $model;
    public function __construct()
    {
        $this->model = new M_Profile();
    }

    /**
     * Si l'utilisateur est connecté : affiche la vue profile/index.php 
     * Sinon utilise la méthode connect() du controleur Home
     *
     * @return void
     */
    public function index()
    {
        if (isset($_SESSION['user'])) {
            $this->model->view('profile/index', []);
        } else {
            $this->model->redirect('home/connect');
        }
    }

    /**
     * Si l'utilisateur est connecté : utilise la méthode index()
     * Sinon utilise la méthode connect() du controleur Home
     *
     * @return void
     */
    public function connect()
    {
        if (isset($_SESSION['user'])) {
            $this->model->redirect('profile/index');
        } else {
            $this->model->redirect('home/connect');
        }
    }

    /**
     * Permet de modifier les informations personnelles du compte de l'utilisateur
     */
    public function modify()
    {
        if (isset($_SESSION['user_modify'])) {
            $name = $_SESSION['user_modify']['name'];
            $surname = $_SESSION['user_modify']['surname'];
            $email = $_SESSION['user_modify']['email'];
            $psw = $_SESSION['user_modify']['password'];

            if (empty($psw)) {
                $psw = null;
            }

            unset($_SESSION['user_modify']);

            if (Verification::mailRegex($email)) {
                if (empty($psw)) {
                    $userModify = $this->model->modifyUser($name, $surname, $email, $psw);
                    $user = $this->model->getUser($email);

                    // Mise a jour des informations de $_SESSION['user']
                    if (isset($_SESSION['user'])) {
                        unset($_SESSION['user']);
                        $_SESSION['user'] = $user;
                    }
                    $this->model->view('profile/index', [
                        'message' => $userModify,
                    ]);
                } else {
                    if (Verification::passwordRegex($psw)) {
                        $userModify = $this->model->modifyUser($name, $surname, $email, $psw);
                        $user = $this->model->getUser($email);

                        // Mise a jour des informations de $_SESSION['user']
                        if (isset($_SESSION['user'])) {
                            unset($_SESSION['user']);
                            $_SESSION['user'] = $user;
                        }
                        session_unset();
                        $_SESSION['message'] = 'Votre mot de passe a été modifié. Veuillez vous reconnecter.';
                        $this->model->redirect('profile/connect');
                    } else {
                        $error = 'Le mot de passe doit contenir 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial.<br>Le mot de passe doit contenir entre 8 et 16 caractères au total.';
                        $this->model->view('profile/index', [
                            'error' => $error,
                        ]);
                    }
                }
            } else {
                $error = "L'adresse mail entrée ne respecte pas le format standard.";
                $this->model->view('profile/index', [
                    'error' => $error,
                ]);
            }
        } else {
            $this->model->redirect('profile/connect');
        }
    }
}
