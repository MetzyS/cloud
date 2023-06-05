<?php

session_start();

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['superadmin'] == '1') {
        if (isset($_SESSION['accounts'])) {
            unset($_SESSION['accounts']);
        }
        $array = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $user_exist = $array['user_exist'];
        if (isset($array['user_new'])) {
            $user_new = $array['user_new'];
        }

        $_SESSION['accounts'] = [];
        $_SESSION['accounts']['user_exist'] = $user_exist;
        if (isset($array['user_new'])) {
            $_SESSION['accounts']['user_new'] = $user_new;
        }

        header('Location: /www/cloud/public/account/modify/');
        exit();
    } else {
        $_SESSION['error'] = 'not found';
        header('Location: /www/cloud/public/home/index');
        exit();
    }
} else {
    $this->model->redirect('home/connect');
}
