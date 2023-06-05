<?php

namespace App\Validators;

class Verification
{
    /**
     * Vérifie si l'email donné en argument respecte le format standard
     *
     * @param string $mail
     * @return bool
     */
    public static function mailRegex(string $mail)
    {
        $mailLowercase = strtolower($mail);
        $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/i";
        return preg_match($pattern, $mailLowercase);
    }

    /**
     * Vérifie si le mot de passe donné en argument respecte le format dans $pattern
     *
     * @param string $psw
     * @return bool
     */
    public static function passwordRegex(string $psw)
    {
        $pattern = "/(?=^.{8,16}$)((?=.*\d)(?=.*\w+))(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[$&+,:;=?@#_*-]).*$/";
        return preg_match($pattern, $psw);
    }

    /**
     * Change le nom du fichier si le fichier existe déjà
     * on rajoute "&" avant d'entrer les parametres afin de permettre la modification des variables en parametre
     * @param string nom du fichier
     * @param string lien de destination (dossier + nom fichier)
     * @param string dossier destination
     * @param int compteur
     * @return void
     */
    public static function changeName(array &$array, &$target_file, string &$target_directory, int $y)
    {
        while (file_exists($array['target_file'])) {
            $y += 1;
            $array['name'] = explode('.', $array['name']);
            $array['name'][0] = $array['name'][0] . '(' . $y . ')';
            $array['name'] = implode('.', $array['name']);
            $array['target_file'] = $target_directory . basename($array['name']);
            $target_file = $array['target_file'];
            while (file_exists($array['target_file'])) {
                $y += 1;
                $array['name'] = explode('.', $array['name']);
                $array['name'][0] = substr($array['name'][0], 0, -3);
                $array['name'][0] = $array['name'][0] . '(' . $y . ')';
                $array['name'] = implode('.', $array['name']);
                $array['target_file'] = $target_directory . basename($array['name']);
                $target_file = $array['target_file'];
            }
        }
    }

    /**
     * Vérification REGEX changement de nom de fichier
     * 5 caractères minimum, .- acceptés
     * @param $name = Nom du fichier
     * @return bool validation
     */
    public static function changeFileName(string $name)
    {
        $pattern = "/^([\/a-zA-Z0-9\s\.-]+){2,}$/";
        return preg_match($pattern, $name);
    }

    /**
     * Vérification REGEX changement de nom de dossier
     * 1 caractères minimum, .- acceptés
     * @param string $name = Nom du dossier
     * @return bool validation
     */
    public static function changeFolderName(string $name)
    {
        $pattern = "/^(?:[a-zA-Z0-9\s\.()_-]+){1,45}$/";
        return preg_match($pattern, $name);
    }

    /**
     * Vérifie que le path ne contient pas ne dépasse pas 255 caractères (VARCHAR(255))
     * @param string @fullPath = Chemin complet (../files/toto/tata...)
     * @return bool validation
     */
    public static function pathValidation(string $fullPath)
    {
        $pattern = "/^.{1,255}$/";
        return preg_match($pattern, $fullPath);
    }
}
