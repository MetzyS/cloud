<?php
session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    if (isset($_POST)) {
        $fileRename = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        foreach ($fileRename as $key => $value) {
            $id = $key;
            $newName = $value;
        }

        $_SESSION['file_rename']['id'] = $id;
        $_SESSION['file_rename']['new_name'] = $newName;

        header('Location: /www/cloud/public/file/rename/' . $id);
        exit();
    } else {
        $_SESSION['error'] = 'not found';
        header('Location: /www/cloud/public/home/index');
        exit();
    }
} else {
    $_SESSION['error'] = 'not found';
    header('Location: /www/cloud/public/home/index');
    exit();
}
