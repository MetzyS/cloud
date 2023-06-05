<?php

namespace App\Validators;

use App\Validators\Verification;

include './verification.php';

class testValidator extends Verification
{
    function testMailRegex()
    {
        $validEmails = ['john.doe@example.com', 'JoHn.DoE@ExAmPlE.CoM', 'john.doe@mail.example.com', 'john.doe@example.paris', 'john.doe@example.museum'];
        $invalidEmails = ['@example.com', 'john.doe@', 'john.doe@ex&ample.com', 'john.doe@example', 'john.doe@example.', 'john.doe@example..com'];
        foreach ($validEmails as $email) {
            if (!$this->mailRegex($email)) {
                echo "Test échoué pour l'adresse email valide : $email <br>";
            }
        }

        foreach ($invalidEmails as $email) {
            if ($this->mailRegex($email)) {
                echo "Test échoué pour l'adresse email invalide : $email<br>";
            }
        }

        echo "Tous les tests mailRegex ont été exécutés.<br>";
    }

    function testPasswordRegex()
    {
        $validPasswords = ['Asbjtr0$', 'nrqfe3_E', 'sngSpt1*'];
        $invalidPasswords = ['toto', 'totoTata', 'Tototo@ta', 'toTotoTatataTititi454545@'];
        foreach ($validPasswords as $password) {
            if (!$this->passwordRegex($password)) {
                echo "Erreur le password INVALIDE (dans array des mots de passes valide) : $password <br>";
            }
        }

        foreach ($invalidPasswords as $password) {
            if ($this->passwordRegex($password)) {
                echo "Erreur le password VALIDE (dans array des mots de passes invalide) : $password<br>";
            }
        }

        echo "Tous les tests passwordRegex ont été exécutés.<br>";
    }

    function testChangeFolderName()
    {
        $validFolderNames = ['Mon Dossier', 'mon_dossier', 'Mon-Dossier', 'Mon Dossier (1)', 'Mon Dossier-2', 'Mon.Dossier'];
        $invalidFolderNames = ['/MonDossier', 'Mon<Do>ssier', 'MonDossier/..', 'MonDossier/.', 'MonDossier:', 'MonDossier\\'];

        foreach ($validFolderNames as $folderName) {
            if (!$this->changeFolderName($folderName)) {
                echo "Test échoué pour le nom de dossier valide : $folderName <br>";
            }
        }

        foreach ($invalidFolderNames as $folderName) {
            if ($this->changeFolderName($folderName)) {
                echo "Test échoué pour le nom de dossier invalide : $folderName <br>";
            }
        }

        echo "Tous les tests changeFolderName ont été exécutés. <br>";
    }
}

$test = new testValidator;
$test->testMailRegex();
$test->testPasswordRegex();
$test->testChangeFolderName();
