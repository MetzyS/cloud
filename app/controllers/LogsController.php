<?php

use App\Models\M_Logs;
use App\Validators\Verification;

class Logs
{
    protected $model;
    public function __construct()
    {
        $this->model = new M_Logs();
    }

    /**
     * Vérifie si l'utilisateur est connecté
     * Redirige le cas échéant
     */
    public function connect()
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['superadmin'] == 1) {
                $this->model->redirect('logs/index');
            } else {
                $_SESSION['error'] = 'not found';
                $this->model->redirect('home/index');
            }
        } else {
            $this->model->redirect('home/connect');
        }
    }


    /**
     * Affiche les logs si l'utilisateur est un admin
     */
    public function index(int $page = 1)
    {
        if ($page == 0) {
            // Sert a éviter de casser la requête SQL
            $page = 1;
        }
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] == 'admin') {
                $nbLogs = $this->model->getFileLogsLength();
                $nbLogs = $nbLogs['nb_logs'];

                $showLogs = 15; // Nombre de logs a afficher par pages

                $pagesLog = ceil($nbLogs / $showLogs);

                $start = ($page * $showLogs) - $showLogs;

                $logs = $this->model->getFileLogs($start, $showLogs);

                $this->model->view('logs/index', [
                    'logs' => $logs,
                    'page' => $page,
                    'total_pages' => $pagesLog,
                ]);
            } else {
                $_SESSION['error'] = 'not found';
                $this->model->redirect('home/index');
            }
        } else {
            $this->model->redirect('home/connect');
        }
    }
}
