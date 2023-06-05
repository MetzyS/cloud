<?php

session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    if (isset($_POST)) {
        $filePath = filter_input(INPUT_POST, 'file-path', FILTER_DEFAULT);
        $filePathNew = filter_input(INPUT_POST, 'file-new-path', FILTER_DEFAULT);
        //Verification si le chemin existe? 

        $fileTruePath = str_replace('files~', '', $filePath); // On retire ../files/ car il est déjà présent dans le premier if
        $fileTruePath = str_replace('~', '/', $fileTruePath);
        $fileTruePath = str_replace('+', ' ', $fileTruePath);


        if (is_file('../../../../files/' . $fileTruePath)) { //'Si le fichier existe';

            if (is_dir('../../../../files/' . $filePathNew) || $filePathNew == 'racine') { //'Si le chemin de destination (dossier) existe';

                if ($filePathNew == 'racine') {
                    $filePathNew = '';
                }
                $pathOld = '../files/' . $fileTruePath;
                $pathNew = '../files/' . $filePathNew;

                $pathOldArr = explode('/', $fileTruePath);
                // $pathNew = explode('/', $filePathNew);
                $name = end($pathOldArr);
                $_SESSION['file_move']['file_name'] = $name;

                // rename remplace un dossier déjà existant si il porte le même nom

                $_SESSION['file_move']['path_old'] = $pathOld;
                $_SESSION['file_move']['path_new'] = $pathNew;

                header('Location: /www/cloud/public/file/moveFile/');
                exit();
            } else {
                // "Le chemin de destination (dossier) n'existe pas";
                $_SESSION['message'] = "Le chemin " . $filePathNew . " n'existe pas.";
                header('Location: /www/cloud/public/home/index');
                exit();
            }
        } else {
            // "Le fichier n'existe pas";
            $name = end(explode('/', $fileTruePath));
            $_SESSION['message'] = "Le fichier " . $name . " n'existe pas.";
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
