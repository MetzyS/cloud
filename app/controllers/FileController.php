<?php

use App\Models\M_File;
use App\Validators\Verification;

class File
{
    protected $model;

    public function __construct()
    {
        $this->model = new M_File();
    }

    /**
     * Affiche la page index de files
     * Redirige vers page de connexion si l'utilisateur n'est pas connecté
     */
    public function index()
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] == 'admin') {
                $this->model->view('files/index', []);
            } else {
                $_SESSION['error'] = "not found";
                $this->model->redirect('home/index');
            }
        } else {
            $_SESSION['error'] = "not found";
            $this->model->redirect('home/index');
        }
    }

    /**
     * Gestion des fichiers téléversés
     * (ajout dans la bdd et affiche vue avec message + données pour liste)
     * Redirige vers page de connexion si l'utilisateur n'est pas connecté
     */
    public function upload()
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] == 'admin') {
                $files = $_SESSION['upload']['files'];
                unset($_SESSION['upload']);
                $idUser = $_SESSION['user']['id_user'];

                $upload = $this->model->filesUpload($files, $idUser);

                $_SESSION['upload'] = $upload;
                $_SESSION['storage_infos'] = $this->model->serverStorageInfos();
                $this->model->redirect('file/index');
            } else {
                $_SESSION['error'] = "not found";
                $this->model->redirect('home/index');
            }
        } else {
            $_SESSION['error'] = "not found";
            $this->model->redirect('home/index');
        }
    }

    /**
     * Supprime un fichier de la base de donnée et du serveur
     * Redirige si l'utilisateur n'est pas un admin
     * Redirige si l'utilisateur n'est pas connecté
     */
    public function delete($id)
    {
        if (isset($_SESSION['user'])) {

            if ($_SESSION['user']['role'] === 'admin') {
                $data = $this->model->deleteFile($id);
                var_dump($data['file_info'][0]['path']);
                unlink($data['file_info'][0]['path']);
                $_SESSION['delete_message'] = $data['delete_message'];
                $this->model->redirect('home/index');
            } else {
                $_SESSION['error'] = "not found";
                $this->model->redirect('home/index');
            }
        } else {
            $this->model->redirect('home/index');
        }
    }

    /**
     * Renomme un fichier de la base de donnée et du serveur
     * Redirige si l'utilisateur n'est pas un admin
     * Redirige si l'utilisateur n'est pas connecté
     * @param int $id = ID de l'utilisateur
     */
    public function rename(int $id)
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] === 'admin') {
                if (isset($_SESSION['file_rename'])) {
                    $fileName = $_SESSION['file_rename']['new_name'];
                    unset($_SESSION['file_rename']);
                    if (Verification::changeFileName($fileName)) {
                        $data = $this->model->renameFile($id, $fileName);
                        $oldPath = $data['old_path'];
                        $newPath = $data['new_path'];
                        rename($oldPath, $newPath);
                        $_SESSION['rename_message'] = $data['message'];
                        $this->model->redirect('home/index');
                    } else {
                        $_SESSION['rename_error'] = 'Le nom du fichier ne respecte pas le format standard. 2 caractères minimum, sont acceptés: . - ainsi que les espaces.';
                        $this->model->redirect('home/index');
                    }
                } else {
                    $_SESSION['error'] = "not found";
                    $this->model->redirect('home/index');
                }
            } else {
                $_SESSION['error'] = "not found";
                $this->model->redirect('home/index');
            }
        } else {
            $this->model->redirect('home/index');
        }
    }

    /**
     * Déplace un fichier dans un dossier du serveur
     * Change son path dans la base de données
     */
    public function move()
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] == 'admin') {
                if (isset($_SESSION['file_recent_move'])) {
                    $fileId = $_SESSION['file_recent_move']['file_id'];
                    $pathNew = $_SESSION['file_recent_move']['path_new'];
                    unset($_SESSION['file_recent_move']);

                    $fileName = $this->model->getFileName($fileId);
                    $fileName = $fileName['file_name'];

                    if (file_exists($pathNew . '/' . $fileName)) {
                        $_SESSION['create_error'] = "Erreur lors du déplacement du fichier. Vérifiez qu'un fichier portant le même nom n'existe pas déjà.";
                        $this->model->redirect('home/index');
                    }

                    $pathOld = $this->model->getFilePath($fileId);
                    $pathOld = $pathOld['path'];

                    if (@rename($pathOld, $pathNew . '/' . $fileName)) { // Essaie de déplacer le fichier et vérifie si le déplacement a été effectué '@' = Opérateur de contrôle d'erreurs
                        $folderPath = $pathNew;
                        if ($pathNew != '../files/') { // Lorsque l'on déplace un fichier, le chemin est égal a '../files/dossier' <= le dernier '/' n'existe pas
                            $pathNew = $pathNew . '/' . $fileName; // Récupération du nouveau chemin
                        } else { // On concatène sans le '/' car il existe déjà dans '../files/'
                            $pathNew = $pathNew . $fileName;
                        }
                        $this->model->fileMove($folderPath, $pathNew, $pathOld);
                        $_SESSION['create_valid'] = 'Le fichier ' . $fileName . ' a bien été déplacé.';
                        $this->model->redirect('home/index');
                    } else {
                        $_SESSION['create_error'] = "Erreur lors du déplacement du fichier. Vérifiez qu'un fichier portant le même nom n'existe pas déjà.";
                        $this->model->redirect('home/index');
                    }
                } else {
                    $_SESSION['error'] = "not found";
                    $this->model->redirect('home/index');
                }
            } else {
                $_SESSION['error'] = "not found";
                $this->model->redirect('home/index');
            }
        } else {
            $this->model->redirect('home/connect');
        }
    }

    /**
     * Change le 'path' et le parent d'un fichier dans la bdd
     * Déplace un fichier (physique)
     * Affiche un message d'erreur en cas de problème
     */
    public function moveFile()
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] == 'admin') {
                $pathOld = $_SESSION['file_move']['path_old']; // Récupération de l'ancien chemin
                $pathNew = $_SESSION['file_move']['path_new']; // Récupération du nouveau chemin
                if (isset($_SESSION['file_move'])) {
                    unset($_SESSION['file_move']);
                }
                $fileArr = explode('/', $pathOld);
                $fileName = end($fileArr); // Nom du fichier a déplacer

                if (file_exists($pathNew . '/' . $fileName)) {
                    $_SESSION['create_error'] = "Erreur lors du déplacement du fichier. Vérifiez qu'un fichier portant le même nom n'existe pas déjà.";
                    $this->model->redirect('home/index');
                }

                if (@rename($pathOld, $pathNew . '/' . $fileName)) { // Essaie de déplacer le fichier et vérifie si le déplacement a été effectué '@' = Opérateur de contrôle d'erreurs
                    $folderPath = $pathNew;
                    if ($pathNew != '../files/') { // Lorsque l'on déplace un fichier, le chemin est égal a '../files/dossier' <= le dernier '/' n'existe pas
                        $pathNew = $pathNew . '/' . $fileName; // Récupération du nouveau chemin
                    } else { // On concatène sans le '/' car il existe déjà dans '../files/'
                        $pathNew = $pathNew . $fileName;
                    }
                    $this->model->fileMove($folderPath, $pathNew, $pathOld);
                    $_SESSION['create_valid'] = 'Le fichier ' . $fileName . ' a bien été déplacé.';
                    $this->model->redirect('home/index');
                } else {
                    $_SESSION['create_error'] = "Erreur lors du déplacement du fichier. Vérifiez qu'un fichier portant le même nom n'existe pas déjà.";
                    $this->model->redirect('home/index');
                }
            } else {
                $_SESSION['error'] = "not found";
                $this->model->redirect('home/index');
            }
        } else {
            $this->model->redirect('home/connect');
        }
    }

    /**
     * Supprime un dossier dans la BDD
     * Supprime un dossier physique (avec tout son contenu)
     * Affiche un message d'erreur en cas d'echec
     * @param string $path = Chemin du dossier a supprimer
     */
    public function deleteFile(string $path)
    {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
            // Le chemin est récupéré dans l'URL sous le format suivant: 'racine~dossier~nom+du+fichier'
            $path = str_replace('~', '/', $path); // on change les ~ en /
            $path = str_replace('+', ' ', $path); // on remet les espaces
            $path = str_replace('%20', ' ', $path); // on remet les espaces
            $path = '../' . $path; // on insère ../ au début du chemin
            $message = $this->model->fileDeleteByPath($path);
            unlink($path);

            $_SESSION['delete_message'] = $message['delete_message'];
            $_SESSION['storage_infos'] = $this->model->serverStorageInfos();
            $this->model->redirect('home/index');
        } else {
            $_SESSION['error'] = "not found";
            $this->model->redirect('home/index');
        }
    }

    /**
     * Transmet les informations du nouveau dossier vers la base de donnée
     * Crée un dossier physique dans la racine (../files/)
     * Affiche un message d'erreur en cas de problème
     * @param string $folder = Nom du dossier
     */
    public function createFolder(string $folder)
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] == 'admin') {
                $folder = str_replace('$', ' ', $folder);
                $id = $_SESSION['user']['id_user'];
                $fullPath = '../files/' . $folder;
                if (!file_exists($fullPath) && Verification::pathValidation($fullPath) && Verification::changeFolderName($folder)) { // Vérification existence du dossier + regex path
                    $this->model->createNewFolder($folder, $id, $fullPath);
                    mkdir('../files/' . $folder, 0777, true);

                    $_SESSION['create_valid'] = 'Le dossier ' . $folder . ' a bien été crée.';
                    $this->model->redirect('home/index');
                } else {
                    if (file_exists($fullPath)) { // Si le dossier existe
                        $_SESSION['create_error'] = 'Un dossier portant ce nom existe déjà.'; // Message d'erreur
                        $this->model->redirect('home/index');
                    } else if (!Verification::pathValidation($fullPath)) { // Si le chemin dépasse 255 caractères
                        $_SESSION['create_error'] = 'Le chemin dépasse les 255 caractères max autorisés'; // Message d'erreur
                        $this->model->redirect('home/index');
                    }
                    $_SESSION['create_error'] = 'Erreur lors de la création du dossier.';
                    $this->model->redirect('home/index');
                }
            } else {
                $_SESSION['error'] = "not found";
                $this->model->redirect('home/index');
            }
        } else {
            $this->model->redirect('home/connect');
        }
    }

    /**
     * Change le 'path' et le parent d'un dossier dans la bdd
     * Déplace un dossier (physique)
     * Affiche un message d'erreur en cas de problème
     */
    public function moveFolder()
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] == 'admin') {
                $pathOld = $_SESSION['folder_move']['path_old']; // Récupération de l'ancien chemin
                $pathNew = $_SESSION['folder_move']['path_new']; // Récupération du nouveau chemin
                if (isset($_SESSION['folder_move'])) {
                    unset($_SESSION['folder_move']);
                }
                $folderArr = explode('/', $pathOld);
                $folderName = end($folderArr); // Nom du dossier a déplacer
                if (@rename($pathOld, $pathNew . '/' . basename($pathOld))) { // Essaie de déplacer le fichier et vérifie si le déplacement a été effectué '@' = Opérateur de contrôle d'erreurs
                    $parent = $pathNew;
                    if ($pathNew != '../files/') { // Lorsque l'on déplace un dossier dans un autre dossier, le chemin est égal a '../files/Dossier' <= le dernier '/' n'existe pas
                        $pathNew = $pathNew . '/' . $folderName; // Récupération du nouveau chemin
                    } else { // On concatène sans le '/' car il existe déjà dans '../files/'
                        $pathNew = $pathNew . $folderName;
                    }
                    $this->model->folderMove($parent, $pathNew, $pathOld);
                    $this->model->pathsChange($pathOld, $pathNew);
                    $_SESSION['create_valid'] = 'Le dossier ' . $folderName . ' a bien été déplacé.';
                    $this->model->redirect('home/index');
                } else {
                    $_SESSION['create_error'] = "Erreur lors du déplacement du dossier. Vérifiez qu'un dossier portant le même nom n'existe pas déjà.";
                    $this->model->redirect('home/index');
                }
            } else {
                $_SESSION['error'] = "not found";
                $this->model->redirect('home/index');
            }
        } else {
            $this->model->redirect('home/connect');
        }
    }

    /**
     * Génère un lien de téléchargement via le chemin stocké dans la BDD
     * Cela sert a vérifier si le fichier présent dans le serveur est également présent dans la base de données
     * @param string $fileName = Nom du fichier
     */
    public function download(string $filePath)
    {
        if (isset($_SESSION['user'])) {
            $filePath = str_replace('files', '../files', $filePath);
            $filePath = str_replace('~', '/', $filePath);
            $filePath = str_replace('+', ' ', $filePath);
            $filePath = str_replace('%20', ' ', $filePath); // LINUX

            $fileName = end(explode('/', $filePath));

            $downloadPath = $this->model->searchFilePath($filePath);
            if (!$downloadPath) {
                $_SESSION['message'] = "Le fichier " . $fileName . " n'a pas été trouvé dans la base de données.";
                $this->model->redirect('home/index');
            }

            header('Location: /www/cloud/public/' . $downloadPath['path']);
            exit;
        } else {
            $this->model->redirect('home/connect');
        }
    }

    /**
     * Affiche le résultat d'une recherche (en allant chercher les informations dans la BDD)
     * @param string $fileName = Element a rechercher
     */
    public function search($fileName = null)
    {
        if (isset($_SESSION['user'])) {
            $fileName = str_replace('%20', ' ', $fileName);
            $search = $this->model->getFile($fileName);

            $this->model->view('files/search', [
                'search' => $search,
            ]);
        } else {
            $this->model->redirect('home/connect');
        }
    }


    /**
     * Supprime un dossier dans la BDD
     * Supprime un dossier physique (avec tout son contenu)
     * Affiche un message d'erreur en cas d'echec
     * @param string $path = Chemin du dossier a supprimer
     */
    public function deleteFolder(string $path)
    {
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
            $path = str_replace('$', '/', $path);
            $path = '../files/' . $path;
            $path = str_replace('+', ' ', $path); // LINUX
            $path = str_replace('%20', ' ', $path); // LINUX
            $message = $this->model->folderDelete($path);
            $this->model->deleteDirectory($path);

            $_SESSION['delete_message'] = $message['delete_message'];
            $this->model->redirect('home/index');
        } else {
            $_SESSION['error'] = "not found";
            $this->model->redirect('home/index');
        }
    }

    /**
     * Renomme un fichier de la base de donnée et du serveur
     * Redirige si l'utilisateur n'est pas un admin
     * Redirige si l'utilisateur n'est pas connecté
     * @param string $path = Chemin du dossier a renommer
     */
    public function renameFolder(string $path)
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] === 'admin') {
                if (isset($_SESSION['folder_rename'])) {
                    // Concaténation de '../files' au début de $path
                    $folderOldPath = '../files/' . $path;
                    $folderOldPath = str_replace('$', '/', $folderOldPath); // Remplacement des '$' en '/'
                    $folderOldPath = str_replace('_', ' ', $folderOldPath); // Remplacement des '_' en ' ' (car $_POST remplace les espaces par des "_")
                    $folderNewName = $_SESSION['folder_rename']['new_name']; // Nouveau nom du dossier
                    unset($_SESSION['folder_rename']);

                    $folderNewPath = explode('/', $folderOldPath);
                    array_pop($folderNewPath); // On retire le dernier index de $folerNewPath (le dernier index est l'ancien nom du dossier)
                    array_push($folderNewPath, $folderNewName); // On insère le nouveau nom du dossier au dernier index de $folderNewPath

                    $folderNewPath = implode('/', $folderNewPath);

                    if (Verification::changeFolderName($folderNewName)) {
                        $data = $this->model->folderRename($folderOldPath, $folderNewName, $folderNewPath);
                        $paths = $this->model->pathsChange($folderOldPath, $folderNewPath);
                        rename($folderOldPath, $folderNewPath);
                        $_SESSION['rename_folder_message'] = $data['message'];
                        $this->model->redirect('home/index');
                    } else {
                        $_SESSION['rename_error'] = 'Le nom du dossier ne respecte pas le format standard. 1 caractères minimum, sont acceptés: . - ainsi que les espaces.';
                        $this->model->redirect('home/index');
                    }
                } else {
                    $_SESSION['error'] = "not found";
                    $this->model->redirect('home/index');
                }
            } else {
                $_SESSION['error'] = "not found";
                $this->model->redirect('home/index');
            }
        } else {
            $this->model->redirect('home/index');
        }
    }

    public function renameFile($path)
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] === 'admin') {
                if (isset($_SESSION['file_rename'])) {
                    $path = explode('~', $path);
                    array_shift($path);
                    $path = implode('/', $path);
                    $path = str_replace('%20', ' ', $path);
                    $path = str_replace('_', '.', $path);
                    $racine = '../files/';
                    $path = $racine . $path;

                    $newName = $_SESSION['file_rename']['new_name'];
                    unset($_SESSION['file_rename']);

                    if (Verification::changeFileName($newName)) {
                        $data = $this->model->renameFileByPath($path, $newName);
                        $oldPath = $data['old_path'];
                        $newPath = $data['new_path'];
                        rename($oldPath, $newPath);
                        $_SESSION['rename_message'] = $data['message'];
                        $this->model->redirect('home/index');
                    } else {
                        $_SESSION['rename_error'] = 'Le nom du fichier ne respecte pas le format standard. 2 caractères minimum, sont acceptés: . - ainsi que les espaces.';
                        $this->model->redirect('home/index');
                    }
                } else {
                    $_SESSION['error'] = "not found";
                    $this->model->redirect('home/index');
                }
            } else {
                $_SESSION['error'] = "not found";
                $this->model->redirect('home/index');
            }
        } else {
            $this->model->redirect('home/index');
        }
    }
}
