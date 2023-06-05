<?php

session_start();

if (isset($_SESSION['user'])) {
    if (isset($_SESSION['user_modify'])) {
        unset($_SESSION['user_modify']);
    }

    $name = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $surname = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $psw = filter_input(INPUT_POST, 'password_new', FILTER_SANITIZE_EMAIL);

    $_SESSION['user_modify'] = [
        'name' => $name,
        'surname' => $surname,
        'email' => $email,
        'password' => $psw
    ];

    header('Location: /www/cloud/public/profile/modify/');
    exit();
} else {
    $this->model->redirect('home/connect');
}
