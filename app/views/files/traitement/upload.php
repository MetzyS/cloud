<?php

require_once '../../../../app/validators/verification.php';

use App\Validators\Verification;

session_start();


// Déclaration constantes pour le calcul de la taille des fichiers
define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);

var_dump($_POST);
// Redirige l'utilisateur si il n'est pas connecté
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    $_SESSION['error'] = "not found";
    header('Location: /www/cloud/public/home/index/');
    exit();
}

if (isset($_SESSION['upload'])) {
    unset($_SESSION['upload']);
}

//Quand le fichier est beaucoup trop volumineux $_FILES est vide
if (empty($_FILES)) {
    $_SESSION['upload_error'] = "Fichier trop volumineux, 2MB par fichiers jusqu'à 8MB maximum par envoi.";
    header('Location: /www/cloud/public/file/index/');
    exit();
}

$files = [];
$input_files = filter_var_array($_FILES, FILTER_DEFAULT);
$target_directory = "../../../../files/";
$target_file = [];
$file_types = ['images', 'pdf', 'word', 'excel'];

//Réorganise les informations de $_FILES par fichiers
foreach ($input_files as $key => $values) {
    foreach ($values as $key2 => $values2) {
        for ($i = 0; $i < count($values2); $i++) {
            $files[$i][$key2] = $values2[$i];
        }
    }
}

// Vérification du nom des fichiers ajoutés
for ($i = 0; $i < count($files); $i++) {
    if (str_contains($files[$i]['name'], '%')) {
        $_SESSION['upload_error'] = "Le nom du fichier ne respecte pas le format standard. 5 caractères minimum, sont acceptés: . - _ ainsi que les espaces.";
        header('Location: /www/cloud/public/file/index/');
        exit();
    }
}
for ($i = 0; $i < count($files); $i++) {
    if (!Verification::changeFileName($files[$i]['name']) || str_contains($files[$i]['name'], '_')) {
        $_SESSION['upload_error'] = "Le nom du fichier ne respecte pas le format standard. 5 caractères minimum, sont acceptés: . - ainsi que les espaces.";
        header('Location: /www/cloud/public/file/index/');
        exit();
    }
}

// Ajout de l'extension ('pdf', 'jpg', 'txt', 'docx' ...) dans le tableau $files[$i]['extension']
for ($i = 0; $i < count($files); $i++) {
    $files[$i]['extension'] = pathinfo($files[$i]['full_path'], PATHINFO_EXTENSION);
}
// Ajout du lien de destination dans $target_file[$i]
for ($i = 0; $i < count($files); $i++) {
    $target_file[$i] = $target_directory . basename($files[$i]['name']);
    $files[$i]['target_file'] = $target_file[$i];
}

// Vérification de la taille des fichiers ajoutés (2MB max)
for ($i = 0; $i < count($files); $i++) {
    if ($files[$i]['size'] > 2 * MB) {
        $_SESSION['upload_error'] = 'Fichier trop volumineux, 2MB par fichiers jusqu' . "'à 8MB maximum par envoi.";
        header('Location: /www/cloud/public/file/index/');
        exit();
    }
}

//Ajout de la categorie pour chaque fichier 
foreach ($files as $key => &$values) {
    if (($values['extension'] == 'jpeg') || ($values['extension'] == 'jpg') || ($values['extension'] == 'png') || ($values['extension'] == 'gif') || ($values['extension'] == 'jfif') || ($values['extension'] == 'bmp') || ($values['extension'] == 'raw') || ($values['extension'] == 'tff') || ($values['extension'] == 'heic')) {
        $values['category'] = 1; //Images
    } else if (($values['extension'] == 'pdf') || ($values['extension'] == 'xps')) {
        $values['category'] = 2; //Pdf
    } else if (($values['extension'] == 'docx') || ($values['extension'] == 'doc') || ($values['extension'] == 'docm') || ($values['extension'] == 'dotx') || ($values['extension'] == 'txt') || ($values['extension'] == 'odt') || ($values['extension'] == 'ott')) {
        $values['category'] = 3; //Word
    } else if (($values['extension'] == 'xls') || ($values['extension'] == 'xlxm') || ($values['extension'] == 'xlsx') || ($values['extension'] == 'xml') || ($values['extension'] == 'ods') || ($values['extension'] == 'ots')) {
        $values['category'] = 4; //Excel

    } else { //Si l'extension n'est pas autorisée, crée un message d'erreur

        if (count($files) > 1) {
            $extensions .= '.' . $values['extension'] . ' ';
            $_SESSION['upload_error'] = 'Les extensions suivantes: ' . $extensions . ' ne sont pas autorisées.';
        } else {
            $_SESSION['upload_error'] = "L'extension ." . $values['extension'] . ' ne correspond pas aux formats acceptés.';
        }
    }
}

//Si il y a une erreur on redirige vers index avec un message
if (isset($_SESSION['upload_error'])) {
    header('Location: /www/cloud/public/file/index');
    exit();
}

// Change le nom si le fichier existe déjà
for ($i = 0; $i < count($files); $i++) {
    if (file_exists($files[$i]['target_file'])) {
        Verification::changeName($files[$i], $target_file[$i], $target_directory, 0);
    }
}

// Change la valeur de $files[$i]['target_file'] pour avoir un chemin correct stocké dans la BDD
for ($i = 0; $i < count($files); $i++) {
    $files[$i]['target_file'] = substr($files[$i]['target_file'], 9);
}

// Déplace les fichiers vers le dossier files qui est à la racine
foreach ($files as $key => $value) {
    move_uploaded_file($value['tmp_name'], $target_file[$key]);
    chmod($target_file[$key], 0777);
}

$_SESSION['upload'] = [];
$_SESSION['upload']['files'] = $files;


header('Location: /www/cloud/public/file/upload');
exit();
