<?php
session_start();
if (isset($_SESSION['mail'])) {
    unset($_SESSION['mail']);
}

$mail = filter_input(INPUT_POST, 'email');
$_SESSION['mail'] = $mail;

header('Location: /www/cloud/public/home/passwordForgot/');
exit;
