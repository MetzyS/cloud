<?php

use App\Models\M_Account;
use App\Validators\Verification;

class Account
{
    protected $model;
    public function __construct()
    {
        $this->model = new M_Account();
    }

    /**
     * Vérifie si l'utilisateur est connecté et si il est admin.
     * Si il est admin: Affiche la page index de accounts avec les informations de la BDD
     * Si il n'est pas connecté: Redirige vers page de connexion si l'utilisateur n'est pas connecté
     */
    public function index()
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['superadmin'] == '1') {
                $accounts = $this->model->getAccounts();
                $this->model->view('accounts/index', [
                    'accounts' => $accounts,
                ]);
            } else {
                $_SESSION['error'] = 'not found';
                $this->model->redirect('home/connect');
            }
        } else {
            $this->model->redirect('home/connect');
        }
    }

    /**
     * Vérifie si l'utilisateur est connecté
     * Redirige le cas échéant
     */
    public function connect()
    {
        if (isset($_SESSION['user'])) {
            $this->model->redirect('account/index');
        } else {
            $this->model->redirect('home/connect');
        }
    }

    /**
     * Permet de modifier les informations personnelles d'un compte
     */
    public function modify()
    {
        if (isset($_SESSION['update'])) {
            unset($_SESSION['update']);
        }
        if (isset($_SESSION['create'])) {
            unset($_SESSION['create']);
        }


        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['superadmin'] == '1') {
                if (isset($_SESSION['accounts'])) {
                    if (isset($_SESSION['accounts']['user_exist'])) {
                        $accounts = $_SESSION['accounts']['user_exist'];
                        unset($_SESSION['accounts']['user_exist']);
                        foreach ($accounts as $key => $values) {
                            if (!Verification::mailRegex($values['email'])) {
                                $_SESSION['create_error'] = "Le format de l'adresse mail saisi est incorrect.";
                                $this->model->redirect('account/index');
                            }
                            if (!empty($values['password'])) {
                                if (!Verification::passwordRegex($values['password'])) {
                                    $_SESSION['create_error'] = "Le format du mot de passe est incorrect.";
                                    $this->model->redirect('account/index');
                                }
                            }
                        }

                        $message = $this->model->updateAccounts($accounts);
                        $_SESSION['update'] = $message; //array avec messages
                    }

                    if (isset($_SESSION['accounts']['user_new'])) {
                        $accounts_new = $_SESSION['accounts']['user_new'];
                        unset($_SESSION['accounts']['user_new']);
                        foreach ($accounts_new as $key => $values) {
                            if (!Verification::mailRegex($values['email'])) {
                                $_SESSION['create_error'] = "Le format de l'adresse mail saisi est incorrect.";
                                $this->model->redirect('account/index');
                            }
                            if (!empty($values['password'])) {
                                if (!Verification::passwordRegex($values['password'])) {
                                    $_SESSION['create_error'] = "Le format du mot de passe est incorrect.";
                                    $this->model->redirect('account/index');
                                }
                            }
                        }

                        $message_2 = $this->model->createAccounts($accounts_new);
                        $_SESSION['create'] = $message_2;
                    }

                    $accounts = $this->model->getAccounts();

                    $this->model->view('accounts/index', [
                        'accounts' => $accounts,
                    ]);
                } else {
                    $accounts = $this->model->getAccounts();
                    $this->model->view('accounts/index', [
                        'accounts' => $accounts,
                    ]);
                }
            } else {
                $_SESSION['error'] = 'not found';
                $this->model->redirect('home/index');
            }
        } else {
            $this->model->redirect('home/connect');
        }
    }

    /**
     * Permet de supprimer un compte
     * @param int $id = ID du compte a supprimer
     */
    public function delete(int $id)
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['superadmin'] == '1') {
                if ($id !== 1) {
                    $message = $this->model->deleteAccount($id);
                    if (isset($_SESSION['delete'])) {
                        unset($_SESSION['delete']);
                    }
                    $_SESSION['delete'] = $message;
                    $this->model->redirect('account/index');
                } else {
                    $_SESSION['error'] = "not found";
                    $this->model->redirect('home/index');
                }
            } else {
                $_SESSION['error'] = "not found";
                $this->model->redirect('home/index');
            }
        } else {
            $this->model->redirect('home/connect');
        }
    }
}
