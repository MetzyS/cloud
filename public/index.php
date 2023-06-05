<?php
ob_start();
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
include_once '../app/views/template/head.php';


/**
 * Affiche ou non le header ansi que le menu de navigation
 * selon si l'utilisateur est connecté ou non
 */
if (isset($_SESSION['user']) && !isset($_SESSION['error'])) {
    require_once '../app/include.php';
}

use App\Core\App;

require_once '../app/init.php';
$app = new App;
