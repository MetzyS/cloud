# <img src="https://raw.githubusercontent.com/MetzyS/cloud/ca9e05b604602ff97e60727ffa9814b6d6739397/public/assets/presentation/LogoAd.svg" width="35px">&emsp; Projet Cloud :fr: 

### Plateforme de partage de fichiers. 
##### Live demo: [https://cloud.metzys.net](https://cloud.metzys.net), si vous désirez un acces pour tester les fonctionnalités, n'hésitez pas à me contacter!
Crée en autonomie, from scratch, dans le cadre d'un stage en entreprise pour le titre professionnel Développeur Web et Web Mobile.
Ce projet respecte les demandes directes du maître de stage (comme la taille des fichiers max, les caractères autorisés dans le nom des fichiers/dossiers etc.).

#### Use case :
<img src="https://raw.githubusercontent.com/MetzyS/cloud/master/public/assets/presentation/usecase.jpg" alt="Use case" width="500px">

#### Liens :

La plupart des liens utilisés sont des liens absolus, il faudra donc respecter la structure requise ou modifier tous les liens dans le code.
- Clonez le repo dans dans htdocs/www/
- Créez un dossier 'files' dans le dossier 'cloud'.

#### Base de données :
Vous trouverez le code à importer directement (via votre sgbd) dans le dossier /sql/

#### Connexion :

Utilisez l'adresse **'test\@mail.com'** et le mot de passe **'Test123\@\*\*\*'**

### /!\ Bien entendu, lors du passage en prod, quelques modifications seront nécessaires :
1) **app/models/M_Home.php** méthode **passwordCheck()** *ligne 56 à 60*
Permet de se connecter sans mot de passe valable, en utilisant l'adresse 'test\@mail.com' et le mot de passe 'Test123\@\*\*\*', a supprimer obligatoirement lors du passage en prod.

2) **app/views/account/index.php** *lignes 23 à 27 + 38 à 42*
Permettent d'afficher les mots de passe aléatoires générés lors de la création ou la modification d'un compte, a supprimer obligatoirement lors du passage en prod.

3) **app/controllers/HomeController.php** méthode **passwordForgot()** *lignes 176 à 208*
Utilisation de PHPMailer, il faudra donc entrer les informations requises concernant la configuration de l'adresse mail a utiliser (smtp, username, password etc.).
Pour plus d'informations concernant PHPMailer : https://github.com/PHPMailer/PHPMailer#readme

Si ce projet vous plaît, si vous avez des remarques, des critiques ou des conseils, n'hésitez pas a me contacter via l'adresse mail suivante: contact@metzys.net

*Nous avons crée les maquettes, la base de données et la totalité du code, c'est un projet réalisé par deux dev web junior (après 5 mois de formations), soyez indulgents! :D*
*Credit: Adnène H., Olena I.*
