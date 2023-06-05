<?php

namespace App\Models;


use App\Core\Model;
use App\Core\DB;
use \PDO;
use \Exception;

class M_Logs extends Model
{
    /**
     * Récupère les données de la table de 'file_log'
     * @param int $start première ligne a afficher
     * @param int $end dernière ligne a afficher
     * @return array $data = Logs + message
     */
    public function getFileLogs(int $start, int $end)
    {
        $db = DB::getPdoLow();
        try {
            $sql = $db->prepare('SELECT id, updated_by, action, path, new_path, date_format(action_time, "%d/%m/%Y") FROM file_log ORDER BY id DESC LIMIT :firstResult, :lastResult');
            $sql->bindValue(':firstResult', $start, PDO::PARAM_INT);
            $sql->bindValue(':lastResult', $end, PDO::PARAM_INT);
            $sql->execute();
            $data['list'] = $sql->fetchAll(PDO::FETCH_NAMED);
            $data['message'] = 'Historique de toutes les modifications de fichiers';
        } catch (Exception $e) {
            $errorCode = $e->getCode();
            $_SESSION['bdd_error'] = "Une erreur est survenue lors de la communication avec la base de données. Veuillez contacter l'administrateur du système pour obtenir de l'aide. Code d'erreur: " . $errorCode;
            $this->redirect('home/index');
        }
        return $data;
    }

    /**
     * Récupère le nombre de lignes de la table 'file_log'
     * @return array
     */
    public function getFileLogsLength()
    {
        $db = DB::getPdoLow();
        $sql = 'SELECT COUNT(*) AS nb_logs FROM file_log';
        $res = $db->query($sql);
        $res->execute();

        $data = $res->fetch();

        return $data;
    }
}
