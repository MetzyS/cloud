<?php

namespace App\Models;

use App\Core\DB;
use App\Core\Model;
use PDO;
use \Exception;

class M_File extends Model
{
    /**
     * Cherche les informations d'un fichier dans la base de donnée (via son nom)
     * @param string $fileName = Nom du fichier
     * @return array $data = Résultat
     */
    public function getFile(string $fileName)
    {
        try {
            $db = DB::getPdoLow();
            $sql = $db->prepare('
            SELECT *
            FROM file
            JOIN category
            ON file.category_id = category.id_category
            WHERE file_name
            LIKE :file_name');
            $sql->bindValue(':file_name', '%' . $fileName . '%');
            $sql->execute();

            $data = $sql->fetchAll(PDO::FETCH_NAMED);
            if (empty($data)) {
                $data['message'] = 'Aucun résultat correspondant à ' . $fileName;
            } else {
                $data['search_keyword'] = $fileName;
            }
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
            exit();
        }

        return $data;
    }

    /**
     * Ajoute les informations des fichiers téléversés dans la base de données
     * @param array $files = Tableau d'informations
     * @param int $idUser = ID de l'utilisateur qui téléverse
     * @return array $data = tableau d'informations des fichiers téléversés
     */

    public function filesUpload(array $files, int $idUser)
    {
        $user_mail = $_SESSION['user']['email'];
        foreach ($files as $key => $values) {
            try {
                $db = DB::getPdo();
                $sql = $db->prepare('
                INSERT INTO file (file_name, extension, category_id, size, path, uploaded_by, updated_by)
                VALUES (:file_name, :extension, :category, :size, :path, :uploaded_by, :updated_by)');
                $sql->bindParam(':file_name', $values['name']);
                $sql->bindParam(':extension', $values['extension']);
                $sql->bindParam(':category', $values['category']);
                $sql->bindParam(':size', $values['size']);
                $sql->bindParam(':path', $values['target_file']);
                $sql->bindParam(':uploaded_by', $idUser);
                $sql->bindParam(':updated_by', $user_mail);

                $sql->execute();

                $lastId = $db->lastInsertId();

                $data[$key] = $this->getFileUpload($lastId);
            } catch (Exception $e) {
                $errorCode = $e->getCode();
                $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                $this->redirect('home/index');
                exit();
            }
        }

        $data['message'] = "Le transfert a bien été effectué.";

        return $data;
    }

    /**
     * Récupère les informations concernant un ou tout les fichiers
     * @param int $id = id du fichier voulu
     * @return array $data = infos bdd
     */
    public function getFileUpload(int $id = null)
    {
        if (is_null($id)) {
            try {
                $db = DB::getPdoLow();
                $sql = "SELECT * 
                FROM file 
                JOIN category
                ON file.category_id = category.id_category";
                $result = $db->query($sql);

                $data = $result->fetchAll(PDO::FETCH_NAMED);
            } catch (Exception $e) {
                $errorCode = $e->getCode();
                $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                $this->redirect('home/index');
                exit();
            }
        } else {
            try {
                $db = DB::getPdoLow();
                $sql = $db->prepare('
                SELECT *
                FROM file
                JOIN category
                ON file.category_id = category.id_category
                WHERE id_file = :id');
                $sql->bindParam(':id', $id);
                $sql->execute();

                $data = $sql->fetchAll(PDO::FETCH_NAMED);
            } catch (Exception $e) {
                $errorCode = $e->getCode();
                $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                $this->redirect('home/index');
                exit();
            }
        }
        return $data;
    }

    /**
     * Récupère le nom d'un fichier (via id)
     * @param int $id = id du fichier
     * @return array $data = nom du fichier
     */
    public function getFileName(int $id)
    {
        try {
            $db = DB::getPdoLow();
            $sql = $db->prepare('SELECT file_name FROM file WHERE id_file = :id');
            $sql->bindParam(':id', $id);
            $sql->execute();
            $data = $sql->fetch(PDO::FETCH_NAMED);
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
            exit();
        }

        return $data;
    }

    /**
     * Supprime un fichier de la bdd (via id)
     * @param int $id = ID du fichier
     * @return array $data = messages d'informaion
     */
    public function deleteFile(int $id)
    {
        $file = $this->getFileName($id);
        if (!$file) {
            $data['message'] = 'Impossible de supprimer un fichier inexistant.';
        } else {
            $db = DB::getPdo();
            $db->beginTransaction();
            try {
                $fileInfo = $this->getFileUpload($id);
                $fileName = $file['file_name'];
                try {
                    $user_mail = $_SESSION['user']['email'];
                    $sql1 = $db->prepare('UPDATE file SET updated_at = NOW(), updated_by = :mail WHERE id_file = :id');
                    $sql1->bindParam(':mail', $user_mail);
                    $sql1->bindParam(':id', $id);
                    $sql1->execute();
                } catch (Exception $e) {
                    $errorCode = $e->getCode();
                    $db->rollback();
                    $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                    $this->redirect('home/index');
                    exit();
                }

                try {
                    $sql = $db->prepare("DELETE FROM file WHERE id_file = :id");
                    $sql->bindParam(':id', $id);
                    $sql->execute();
                    $data['delete_message'] = 'Le fichier ' . $fileName . ' a bien été supprimé.';
                    $data['file_info'] = $fileInfo;
                } catch (Exception $e) {
                    $errorCode = $e->getCode();
                    $db->rollback();
                    $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                    $this->redirect('home/index');
                    exit();
                }
                $db->commit();
            } catch (Exception $e) {
                $errorCode = $e->getCode();
                $db->rollback();
                $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                $this->redirect('home/index');
                exit();
            }
        }
        return $data;
    }

    /**
     * Récupère l'ID et le nom d'un fichier via son chemin ($path)
     * @param string $path = chemin du fichier recherché
     * @return array|false $data = path+nom du fichier / false
     */
    public function getFileIdByPath(string $path)
    {
        try {
            $db = DB::getPdoLow();
            $sql = $db->prepare(
                'SELECT id_file, file_name
                FROM file
                WHERE path = :path'
            );
            $sql->bindParam(':path', $path);
            $sql->execute();
            $data = $sql->fetch(PDO::FETCH_NAMED);
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
            exit();
        }
        return $data;
    }

    /**
     * Supprime un fichier dans la BDD (table file) via son chemin ($path)
     * @param string $path = chemin (exemple: ../files/D1/test.png)
     * @return array $data = message d'information
     */
    public function fileDeleteByPath(string $path)
    {
        $file = $this->getFileIdByPath($path);
        if ($file) {
            $data = $this->deleteFile($file['id_file']);
        } else {
            $data['message'] = 'Impossible de supprimer un dossier inexistant.';
        }
        return $data;
    }

    /**
     * Récpère l'extension d'un fichier (dans la bdd)
     * @param int $id = id du fichier (bdd)
     * @return string $data = extension (exemple: '.jpg')
     */
    public function getFileExtension(int $id)
    {
        try {
            $db = DB::getPdoLow();
            $sql = $db->prepare('
            SELECT extension 
            FROM file 
            WHERE id_file = :id');
            $sql->bindParam(':id', $id);
            $sql->execute();

            $data = $sql->fetch(PDO::FETCH_NAMED);
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('file/index');
            exit();
        }

        return $data;
    }

    /**
     * Récpère l'extension d'un fichier (dans la bdd)
     * @param int $id = id du fichier (bdd)
     * @return string $data = path (exemple: '../file/compta/')
     */
    public function getFilePath(int $id)
    {
        try {
            $db = DB::getPdoLow();
            $sql = $db->prepare('
            SELECT path 
            FROM file 
            WHERE id_file = :id');
            $sql->bindParam(':id', $id);
            $sql->execute();

            $data = $sql->fetch(PDO::FETCH_NAMED);
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('file/index');
            exit();
        }

        return $data;
    }


    public function renameFileByPath(string $oldPath, string $newName)
    {
        $file = $this->getFileIdByPath($oldPath);
        $id = $file['id_file'];
        $extension = $this->getFileExtension($file['id_file']);
        $newPath = str_replace($file['file_name'], $newName, $oldPath);
        $newPath = $newPath . '.' . $extension['extension'];

        if (!$file && !$extension) {
            $data['message'] = 'Impossible de renommer un fichier inexistant.';
        } else {
            $user_mail = $_SESSION['user']['email'];
            $db = DB::getPdo();

            try {
                $extension = '.' . $extension['extension'];
                $newName = $newName . $extension;
                $sql = $db->prepare('
                UPDATE file
                SET file_name = :new_name, path = :path, updated_at = NOW(), updated_by = :mail
                WHERE id_file = :id');
                $sql->bindParam(':new_name', $newName);
                $sql->bindParam(':path', $newPath);
                $sql->bindParam(':id', $id);
                $sql->bindParam(':mail', $user_mail);

                $sql->execute();

                $data['new_path'] = $newPath;
                $data['old_path'] = $oldPath;
                $data['message'] = 'Le fichier a bien été renommé en: ' . $newName;
            } catch (Exception $e) {
                $errorCode = $e->getCode();
                $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                $this->redirect('home/index');
                exit();
            }
        }
        return $data;
    }

    /**
     * Change le nom d'un fichier dans la BDD (file_name), via son ID
     * Rajoute l'extension à la fin du nom
     * @param int $id = id du fichier (bdd)
     * @param string $newName = nouveau nom du fichier
     * @return array $data['message'] = message d'erreur / de confirmation
     */
    public function renameFile(int $id, string $newName)
    {
        $file = $this->getFileName($id);
        $extension = $this->getFileExtension($id);
        $oldPath = $this->getFilePath($id);
        $newPath = str_replace($file['file_name'], $newName, $oldPath);
        $newPath = $newPath['path'];
        $newPath = $newPath . '.' . $extension['extension'];
        //$path est un return de getFilePath. Cette fonction return un tableau array['path'] = string (path)
        $oldPath = $oldPath['path'];
        if (!$file && !$extension) {
            $data['message'] = 'Impossible de renommer un fichier inexistant.';
        } else {
            $user_mail = $_SESSION['user']['email'];
            try {
                $extension = '.' . $extension['extension'];
                $newName = $newName . $extension;
                $db = DB::getPdo();
                $sql = $db->prepare('
                UPDATE file
                SET file_name = :new_name, path = :path, updated_at = NOW(), updated_by = :mail
                WHERE id_file = :id');
                $sql->bindParam(':new_name', $newName);
                $sql->bindParam(':path', $newPath);
                $sql->bindParam(':id', $id);
                $sql->bindParam(':mail', $user_mail);

                $sql->execute();

                $data['new_path'] = $newPath;
                $data['old_path'] = $oldPath;
                $data['message'] = 'Le fichier a bien été renommé en: ' . $newName;
            } catch (Exception $e) {
                $errorCode = $e->getCode();
                $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                $this->redirect('home/index');
                exit();
            }
        }
        return $data;
    }


    /**
     * Change le chemin (path) d'un fichier dans la BDD (table folder)
     * @param string $folderPath = Nouveau chemin
     * @param string $pathNew = Nouveau chemin
     * @param string $pathOld = Ancien chemin
     * @param string $fileName = Nom du fichier
     */
    public function fileMove(string $folderPath, string $newPath, string $oldPath)
    {
        $folderId = $this->getFolderIdByPath($folderPath);
        $folderId = $folderId['id_folder'];
        $user_mail = $_SESSION['user']['email'];
        try {
            $db = DB::getPdo();
            $sql = $db->prepare(
                'UPDATE file
                SET path = :newPath, updated_at = NOW(), folder_id = :folderId, updated_by = :mail
                WHERE path = :oldPath'
            );
            $sql->bindParam(':newPath', $newPath);
            $sql->bindParam(':folderId', $folderId);
            $sql->bindParam(':oldPath', $oldPath);
            $sql->bindParam(':mail', $user_mail);
            $sql->execute();
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
            exit();
        }
    }

    /**
     * Cherche un fichier via son chemin (path)
     * Chaque path est unique
     * @param string $filePath = chemin du fichier
     * @return array|false = path si fichier existe, false si il n'existe pas
     */
    public function searchFilePath(string $filePath)
    {
        try {
            $db = DB::getPdoLow();
            $sql = $db->prepare(
                'SELECT path
                FROM file
                WHERE path = :filepath
                '
            );
            $sql->bindParam(':filepath', $filePath);
            $sql->execute();

            $data = $sql->fetch(PDO::FETCH_NAMED);
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
            exit();
        }
        return $data;
    }

    //GESTION DES DOSSIERS


    /**
     * Récupère l'ID d'un dossier dans la BDD (via son nom)
     * @param string $folder = nom du dossier
     * @return array $data = résultat de la requête (id_folder)
     */
    public function getFolderId(string $folder)
    {
        try {
            $db = DB::getPdoLow();
            $sql = $db->prepare('SELECT id_folder FROM folder WHERE folder_name = :folder_name');
            $sql->bindParam(':folder_name', $folder);
            $sql->execute();

            $data = $sql->fetch(PDO::FETCH_NAMED);
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
            exit();
        }

        return $data;
    }

    /**
     * Cherche les informations d'un dossier dans la base de donnée (via son chemin)
     * @param string $path = chemin du dossier
     * @return array $data = Résultat
     */
    public function getFolderIdByPath(string $path)
    {
        try {
            $db = DB::getPdoLow();
            $sql = $db->prepare('SELECT id_folder, folder_name FROM folder WHERE folder_path = :path');
            $sql->bindParam(':path', $path);
            $sql->execute();
            $data = $sql->fetch(PDO::FETCH_NAMED);
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
            exit();
        }

        return $data;
    }

    /**
     * Récupère l'ID d'un dossier dans la BDD (via son nom)
     * @param string $folder = nom du dossier
     * @return array $data = résultat de la requête
     */
    public function getFolderIdWithParent(string $folder, int $parent_id)
    {
        try {
            $db = DB::getPdoLow();
            $sql = $db->prepare('SELECT id_folder FROM folder WHERE (folder_name = :folder_name) AND (parent_id = :parent_id)');
            $sql->bindParam(':folder_name', $folder);
            $sql->bindParam(':parent_id', $parent_id);
            $sql->execute();

            $data = $sql->fetch(PDO::FETCH_NAMED);
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
            exit();
        }
        return $data;
    }

    /**
     * Récupère l'ID du dossier parent d'un dossier dans la BDD (via son nom)
     * @param string $folder = nom du dossier
     * @return array $data = résultat de la requête (parent_id)
     */
    public function getFolderParentId(string $folder)
    {
        try {
            $db = DB::getPdoLow();
            $sql = $db->prepare('SELECT parent_id FROM folder WHERE folder_name = :folder_name');
            $sql->bindParam(':folder_name', $folder);
            $sql->execute();

            $data = $sql->fetch(PDO::FETCH_NAMED);
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
            exit();
        }

        return $data;
    }

    /**
     * Crée un nouveau dossier dans la BDD (table folder)
     * @param string $folder = nom du dossier
     * @param int $id = ID utilisateur
     * @param string $path = Chemin, (toujours '../files/nom du dossier/')
     * @return int $data = id du dossier
     */
    public function createNewFolder(string $folder, int $id, string $path)
    {
        $user_mail = $_SESSION['user']['email'];
        try {
            $db = DB::getPdo();
            $sql = $db->prepare('
        INSERT INTO folder (folder_name, created_by, folder_path, updated_by)
        VALUES (:folder_name, :created_by, :folder_path, :mail)');
            $sql->bindParam(':folder_name', $folder);
            $sql->bindParam(':created_by', $id);
            $sql->bindParam(':folder_path', $path);
            $sql->bindParam(':mail', $user_mail);

            $sql->execute();

            $data = $db->lastInsertId();
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
            exit();
        }


        return $data;
    }

    /**
     * Change le chemin (path) d'un dossier dans la BDD (table folder)
     * @param string $parent = Nouveau chemin
     * @param string $pathNew = Nouveau chemin
     * @param string $pathOld = Ancien chemin
     * @param string $folderName = Nom du dossier
     */
    public function folderMove(string $parent, string $newPath, string $oldPath)
    {
        $parent_id = $this->getFolderIdByPath($parent); // Récupère l'ID du parent, si il ne trouve pas de parent alors return NULL (racine = NULL)
        $parent_id = $parent_id['id_folder']; // On ne garde que la valeur de id_folder, soit INT soit NULL
        $user_mail = $_SESSION['user']['email'];
        try {
            $db = DB::getPdo();
            $sql = $db->prepare(
                'UPDATE folder
                SET parent_id = :parent_id, folder_path = :newPath, updated_at = NOW(), updated_by = :mail
                WHERE folder_path = :oldPath'
            );
            $sql->bindParam(':parent_id', $parent_id);
            $sql->bindParam(':newPath', $newPath);
            $sql->bindParam(':oldPath', $oldPath);
            $sql->bindParam(':mail', $user_mail);
            $sql->execute();
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
            exit();
        }
    }


    /**
     * Change le nom et le chemin (path) d'un dossier dans la BDD (table folder)
     * @param string $folderOldPath = Ancien chemin
     * @param string $folderNewName = Nouveau nom
     * @param string $folderNewPath = Nouveau chemin
     */

    public function folderRename(string $folderOldPath, string $folderNewName, string $folderNewPath)
    {
        $user_mail = $_SESSION['user']['email'];
        try {
            $db = DB::getPdo();
            $sql = $db->prepare(
                'UPDATE folder
                SET folder_name = :folder_new_name,
                updated_at = NOW(),
                updated_by = :mail
                WHERE folder_path = :folder_old_path'
            );
            $sql->bindParam(':folder_new_name', $folderNewName);
            $sql->bindParam(':folder_old_path', $folderOldPath);
            $sql->bindParam(':mail', $user_mail);
            $sql->execute();

            $data['message'] = 'Le dossier a bien été renommé en: ' . $folderNewName;
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
            exit();
        }
        return $data;
    }

    /**
     * Change le chemin (path) de tout les dossiers ayant la même structure la BDD (table folder)
     * Exemple: '../files/D1/D2' => '../files/D1/D3' -------- '../files/D1/D2/D5' => '../files/D1/D3/D5' etc.
     * On va changer tout les paths ayant '../files/D1/D2' dans leur path 
     * @param string $folderOldPath = Ancien chemin
     * @param string $folderNewPath = Nouveau chemin
     */
    public function pathsChange(string $folderOldPath, string $folderNewPath)
    {
        $user_mail = $_SESSION['user']['email'];
        try {
            $db = DB::getPdo();
            // On change les paths des dossiers concernés
            $sql_folder_path = $db->prepare(
                'UPDATE folder
                SET folder_path = REPLACE(folder_path, :folderOldPath, :folderNewPath),
                updated_at = NOW(),
                updated_by = :mail
                WHERE folder_path LIKE :folderOldPathLike
            '
            );
            $sql_folder_path->bindParam(':mail', $user_mail);
            $sql_folder_path->bindParam(':folderOldPath', $folderOldPath);
            $sql_folder_path->bindParam(':folderNewPath', $folderNewPath);
            $sql_folder_path->bindValue(':folderOldPathLike', $folderOldPath . '%');
            $sql_folder_path->execute();

            // On change les paths des fichiers concernés
            $sql_file_path = $db->prepare(
                'UPDATE file
                SET path = REPLACE(path, :folderOldPath, :folderNewPath),
                updated_at = NOW(),
                updated_by = :mail
                WHERE path LIKE :folderOldPathLike
                '
            );
            $sql_file_path->bindParam(':mail', $user_mail);
            $sql_file_path->bindParam(':folderOldPath', $folderOldPath);
            $sql_file_path->bindParam(':folderNewPath', $folderNewPath);
            $sql_file_path->bindValue(':folderOldPathLike', $folderOldPath . '%');
            $sql_file_path->execute();
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
            exit();
        }
    }

    /**
     * Supprime un dossier de la bdd (via son chemin)
     * @param string $path = chemin du dossier
     * @return array $data = messages d'information
     */
    public function folderDelete(string $path)
    {
        $folder = $this->getFolderIdByPath($path);
        if ($folder) {
            $db = DB::getPdo();
            $db->beginTransaction();
            try {
                try {
                    $user_mail = $_SESSION['user']['email'];
                    $sql1 = $db->prepare('UPDATE folder SET updated_at = NOW(), updated_by = :mail WHERE folder_path = :path');
                    $sql1->bindParam(':mail', $user_mail);
                    $sql1->bindParam(':path', $path);
                    $sql1->execute();
                } catch (Exception $e) {
                    $errorCode = $e->getCode();
                    $db->rollback();
                    $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                    $this->redirect('home/index');
                    exit();
                }

                try {
                    $sql = $db->prepare("DELETE FROM folder WHERE folder_path = :path");
                    $sql->bindParam(':path', $path);
                    $sql->execute();

                    $data['delete_message'] = 'Le dossier ' . $folder['folder_name'] . ' a bien été supprimé.';
                } catch (Exception $e) {
                    $errorCode = $e->getCode();
                    $db->rollback();
                    $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                    $this->redirect('home/index');
                    exit();
                }
                $db->commit();
            } catch (Exception $e) {
                $errorCode = $e->getCode();
                $db->rollback();
                $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
                $this->redirect('home/index');
                exit();
            }
        } else {
            $data['message'] = 'Impossible de supprimer un dossier inexistant.';
        }

        return $data;
    }
}
