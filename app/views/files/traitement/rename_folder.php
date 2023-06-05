<?php
session_start();


if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    if (isset($_POST)) {
        $folderRename = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        foreach ($folderRename as $key => $value) {
            $path = str_replace('/', '$', $key); // Remplace les '/' du path par des '$' pour ne pas casser le lien dans header('Location: .../file/renameFolder/$id')
            $newName = $value;
        }

        $_SESSION['folder_rename']['path'] = $path;
        $_SESSION['folder_rename']['new_name'] = $newName;

        header('Location: /www/cloud/public/file/renameFolder/' . $path);
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
