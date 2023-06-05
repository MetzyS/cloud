<?php

use App\Validators\Verification;

require_once '../../../../app/validators/verification.php';

session_start();

$newFolder = filter_input(INPUT_POST, 'folderName', FILTER_DEFAULT);


if (isset($_SESSION['user'])) { // 1 Vérification si l'utilisateur est connecté
    if ($_SESSION['user']['role'] == 'admin') { // 2 Vérification si l'utilisateur est un admin
        if (isset($_POST)) {
            if (Verification::changeFolderName($newFolder)) { // 3 Vérification si le nom du dossier respecte le REGEX dans changeFileName()
                if (file_exists('../../../../files/' . $newFolder)) { // 4 Vérification si un dossier qui porte ce nom existe déjà
                    $_SESSION['message'] = 'Un dossier portant le nom ' . $newFolder . ' existe déjà.';
                    header('Location: /www/cloud/public/home/index');
                    exit();
                } else { // 4
                    // Traitement de $newFolder pour remplacer les espaces par des '$' pour qu'il soit accepté par le controleur
                    $newFolder = str_replace(' ', '$', $newFolder);
                    header('Location: /www/cloud/public/file/createFolder/' . $newFolder);
                    exit();
                }
            } else { // 3
                if (isset($_SESSION['message'])) {
                    unset($_SESSION['message']);
                }
                $_SESSION['message'] = 'Le nom du dossier ne correspond pas au format accepté. 5 caractères minimum, sont acceptés: . - ainsi que les espaces.';
                header('Location: /www/cloud/public/home/index');
                exit();
            }
        } else {
            $_SESSION['error'] = 'not found';
            header('Location: /www/cloud/public/home/index');
            exit();
        }
    } else { // 2
        $_SESSION['error'] = 'not found';
        header('Location: /www/cloud/public/home/index');
        exit();
    }
} else { // 1
    $_SESSION['error'] = 'not found';
    header('Location: /www/cloud/public/home/index');
    exit();
}
