<?php
session_start();
if (isset($_SESSION['password'])) {
    unset($_SESSION['password']);
}
$psw = filter_input(INPUT_POST, 'password');

$_SESSION['password'] = $psw;

header('Location: /www/cloud/public/home/password/');
exit;
