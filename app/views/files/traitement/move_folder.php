<?php

session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    if (isset($_POST)) {
        $folderPath = filter_input(INPUT_POST, 'folder-path', FILTER_DEFAULT);
        $folderPathNew = filter_input(INPUT_POST, 'folder-new-path', FILTER_DEFAULT);
        //Verification si le chemin existe

        if (is_dir('../../../../files/' . $folderPath)) {
            //'Dir existe';
            if (is_dir('../../../../files/' . $folderPathNew) || $folderPathNew == 'racine') {
                //'chemin existe';

                if ($folderPathNew == 'racine') {
                    $folderPathNew = '';
                }
                $pathOld = '../files/' . $folderPath;
                $pathNew = '../files/' . $folderPathNew;

                $pathOldArr = explode('/', $folderPath);
                // $pathNew = explode('/', $folderPathNew);
                $name = end($pathOldArr);
                $_SESSION['folder_move']['folder_name'] = $name;

                // rename remplace un dossier déjà existant si il porte le même nom

                $_SESSION['folder_move']['path_old'] = $pathOld;
                $_SESSION['folder_move']['path_new'] = $pathNew;

                header('Location: /www/cloud/public/file/moveFolder/');
                exit();
            } else {
                // "Chemin n'existe pas";
                $_SESSION['message'] = "Le chemin " . $folderPathNew . " n'existe pas.";
                header('Location: /www/cloud/public/home/index');
                exit();
            }
        } else {
            // "Dir n'existe pas";
            $_SESSION['message'] = "Le dossier " . $folderPath . " n'existe pas.";
            header('Location: /www/cloud/public/home/index');
            exit();
        }
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
