<?php

session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    if (isset($_POST)) {
        $fileId = filter_input(INPUT_POST, 'file_id', FILTER_DEFAULT);
        $filePathNew = filter_input(INPUT_POST, 'destination_path', FILTER_DEFAULT);
        //Verification si le chemin existe? 

        if (is_dir('../../../../files/' . $filePathNew) || $filePathNew == 'racine') { //'Si le chemin de destination (dossier) existe';

            if ($filePathNew == 'racine') {
                $filePathNew = '';
            }

            $pathNew = '../files/' . $filePathNew;
            $_SESSION['file_recent_move']['file_id'] = $fileId;
            $_SESSION['file_recent_move']['path_new'] = $pathNew;

            header('Location: /www/cloud/public/file/move/');
            exit();
        } else {
            // "Le chemin de destination (dossier) n'existe pas";
            $_SESSION['message'] = "Le chemin " . $filePathNew . " n'existe pas.";
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
