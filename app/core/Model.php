<?php

namespace App\Core;

use App\Core\DB;
use \PDO;
use \Exception;

include_once 'DB.php';
class Model
{
    /**
     * Permet de charger une view avec un tableau de données prédéfini
     *
     * @param string $view
     * @param array $data
     */
    public function view(string $view, array $data = [])
    {
        require_once '../app/views/' . $view . '.php';
    }

    /**
     * Redirige (ré écriture de l'url)
     */
    public function redirect(string $method)
    {
        header('Location: /www/cloud/public/' . $method);
        exit;
    }


    /**
     * Génère un mot de passe aléatoire qui contiendra:
     * 8 caractères dont 1 maj, 1 symbole, 1 chiffre et le reste en minuscule
     */
    public function randomPassword()
    {
        //lettres
        $alphabet = "abcdefghijklmnopqrstuwxyz";
        $alphabetMaj = "ABCDEFGHIJKLMNOPQRSTUWXYZ";
        //chiffres
        $number = "0123456789";
        //caractères speciaux 
        $symbol = "*@_-$";
        //taille de $alphabet
        $alphaLength = strlen($alphabet) - 1;
        $numberLength = strlen($number) - 1;
        $symbolLength = strlen($symbol) - 1;
        $psw = "";
        for ($i = 0; $i < 5; $i++) {
            //nombre aléatoire entre 0 et taille de $alphabet (min,max)
            $n = rand(0, $alphaLength);
            $psw .= $alphabet[$n];
        }

        for ($i = 0; $i < 1; $i++) {
            $n = rand(0, $alphaLength);
            $psw = $alphabetMaj[$n] . $psw;
        }

        for ($i = 0; $i < 1; $i++) {
            $n = rand(0, $numberLength);
            $psw = $psw . $number[$n];
        }

        for ($i = 0; $i < 1; $i++) {
            $n = rand(0, $symbolLength);
            $psw = $psw . $symbol[$n];
        }

        return $psw;
    }

    /**
     * Récupère les informations des 10 fichiers les plus récents
     * Retourne un tableau associatif contenant les données des fichiers récents ou false si une erreur s'est produite.
     * 
     * @return array|false 
     */
    public function getRecentFiles()
    {
        try {
            $db = DB::getPdoLow();
            $sql = 'SELECT * FROM file JOIN category
                ON file.category_id = category.id_category
                ORDER BY id_file DESC limit 10';
            $res = $db->query($sql);
            $data = $res->fetchAll(PDO::FETCH_NAMED);
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('account/index');
            exit();
        }
        return $data;
    }

    /**
     * Supprime de manière récursive un dossier et son contenu.
     *
     * @param string $dir = path de dossier
     * @return bool
     */
    public function deleteDirectory(string $dir)
    {
        // Vérifie si le dossier existe.
        if (!file_exists($dir)) {
            return true;
        }

        // Vérifie si le chemin est un dossier.
        if (!is_dir($dir)) {
            // Si c'est un fichier, le supprime.
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            // Ignore les dossiers spéciaux '.' et '..'.
            if ($item == '.' || $item == '..') {
                continue;
            }

            // Supprime récursivement les sous-dossiers et leur contenu.
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        // Supprime le dossier.
        return rmdir($dir);
    }

    public function serverStorageInfos()
    {
        try {
            $db = DB::getPdoLow();
            $sql = 'SELECT size FROM file';
            $res = $db->query($sql);
            $data['sizes'] = $res->fetchAll(PDO::FETCH_NAMED);

            $db2 = DB::getPdoLow();
            $sql2 = 'SELECT server_capacity FROM serv_infos WHERE id_server = 1';
            $res2 = $db2->query($sql2);
            $data2 = $res2->fetch(PDO::FETCH_NAMED);
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('account/index');
            exit();
        }

        $storage_used = 0;
        if (!empty($data['sizes'])) {
            foreach ($data['sizes'] as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    $storage_used += $value2;
                }
            }
        }
        $data['storage_used'] = $storage_used;
        $data['server_capacity'] = $data2['server_capacity'];

        return $data;
    }
}
