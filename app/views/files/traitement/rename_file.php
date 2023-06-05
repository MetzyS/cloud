<?php

require_once('../../../validators/verification.php');

use App\Validators\Verification;

session_start();

if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    if (isset($_POST)) {
        $fileRename = filter_input_array(INPUT_POST);

        foreach ($fileRename as $key => $value) {
            $path = str_replace('/', '$', $key); // Remplace les '/' du path par des '$' pour ne pas casser le lien dans header('Location: .../file/renameFolder/$id')
            $newName = $value;
            if (!Verification::changeFileName($newName) || is_array($newName)) {
                $_SESSION['message'] = "Le nom du fichier ne respecte pas le format standard. 5 caractères minimum, sont acceptés: . - _ ainsi que les espaces.";
                header('Location: /www/cloud/public/home/index');
                exit();
            }
        }

        $_SESSION['file_rename']['path'] = $path;
        $_SESSION['file_rename']['new_name'] = $newName;

        header('Location: /www/cloud/public/file/renameFile/' . $path);
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
