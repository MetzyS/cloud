# Projet Cloud :fr:

### Plateforme de partage de fichiers. 
Crée en autonomie, from scratch, dans le cadre d'un stage en entreprise pour le titre professionnel Développeur Web et Web Mobile.
Ce projet respecte les demandes directes du maître de stage (comme la taille des fichiers max, les caractères autorisés dans le nom des fichiers/dossiers etc.).

#### Use case :


#### Liens :

La plus part des liens utilisés sont des liens **absolus**, il faudra donc respecter la structure requise ou modifier tout les liens dans le code.
- Créez un dossier nommé 'cloud' dans htdocs/www/.
- Clonez le repo dans 'cloud'.

#### Base de données :
Vous trouverez le code a importer directement (via votre sgbd) dans le dossier /sql/

#### Connexion :

Utilisez l'adresse **'test\@mail.com'** et le mot de passe **'Test123\@\*\*\*'**

### /!\ Bien entendu, lors du passage en prod, quelques modifications seront nécessaires :
1) **app/models/M_Home.php** méthode **passwordCheck()** *ligne 56 à 60*
Permet de se connecter sans mot de passe valable, en utilisant l'adresse 'test\@mail.com' et le mot de passe 'Test123\@\*\*\*', a supprimer obligatoirement lors du passage en prod.

2) **app/views/account/index.php** *lignes 23 à 27 + 38 à 42*
Permettent d'afficher les mots de passes aléatoires générés lors de la création ou la modification d'un compte, a supprimer obligatoirement lors du passage en prod.

3) **app/controllers/HomeController.php** méthode **passwordForgot()** *lignes 176 à 208*
Utilisation de PHPMailer, il faudra donc entrer les informations requises concernant la configuration de l'adresse mail a utiliser (smtp, username, password etc.).
Pour plus d'informations concernant PHPMailer : https://github.com/PHPMailer/PHPMailer#readme

Si ce projet vous plaît, si vous avez des remarques, des critiques ou des conseils, n'hésitez pas a me contacter via l'adresse mail suivante: contact@metzys.net

*Nous avons crée les maquettes, la base de données et la totalité du code, c'est un projet réalisé par deux dev web junior (après 5 mois de formations), soyez indulgents! :D*
*Credit: Adnène H., Olena I.*