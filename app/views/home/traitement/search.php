<?php

require_once '../../../core/DB.php';

use App\Core\DB;

session_start();

if (isset($_SESSION['user'])) {
    if (isset($_POST['query'])) {
        $search = filter_input(INPUT_POST, 'query', FILTER_DEFAULT);
        $db = DB::getPdo();
        $sql = $db->prepare(
            'SELECT file_name FROM file WHERE file_name LIKE :search'
        );
        $sql->bindValue(':search', $search . '%');
        $sql->execute();

        $result = $sql->fetchAll(PDO::FETCH_NAMED);

        $html = '<ul class="suggestion__list">';
        if (!empty($result)) {
            foreach ($result as $key => $values) {
                $html .= '<li class="suggestion__item"><a href="/www/cloud/public/file/search/' . $values['file_name'] . '" class="suggestion__link">' . $values['file_name'] . '</a></li>';
            }
        }
        $html .= '</ul>';

        echo $html;
    } else {
        header('Location: /www/cloud/public/home/index');
        exit();
    }
} else {
    header('Location: /www/cloud/public/home/connect');
    exit();
}
