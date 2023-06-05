<?php

use App\Components\Component;

if ($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['superadmin'] == '1') {
    echo '<section>';
    Component::sectionTitle($data['logs']['message']);
    if (!empty($data['logs']['list'])) {
        echo '<div class="log">';
        echo '<p class="log__page"> Page: ' . $data['page'] . '</p>';
        echo '<table class="logs__table">';
        echo '<thead>
        <tr><th class="logs__cell">ID</th>
        <th class="logs__cell">Utilisateur</th>
        <th class="logs__cell">Action</th>
        <th class="logs__cell">Chemin</th>
        <th class="logs__cell">Nouveau Chemin</th>
        <th class="logs__cell">Date</th></tr>
        </thead>
        <tbody>';
        foreach ($data['logs']['list'] as $key => $values) {
            echo '<tr>';
            foreach ($values as $key2 => $value) {
                echo '<td class="logs__cell">' . $value . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '<ul class="pagination">';
        echo '<li class="pagination__item">';
        echo '<a ';
        if ($data['page'] != 1) {
            echo 'href="' . $data['page'] - 1 . '"';
        }
        echo 'class="pagination__link">&lt;&lt;</a>';
        for ($i = 1; $i <= $data['total_pages']; $i++) {
            echo '<li class="pagination__item ';
            if ($data['page'] == $i) {
                echo 'pagination__item--active';
            }
            echo '">';
            echo '<a href="/www/cloud/public/logs/index/' . $i . '" class="pagination__link">' . $i . '</a>';
        }
        echo '<li class="pagination__item">';
        echo '<a ';
        if ($data['page'] != $data['total_pages']) {
            echo 'href="' . $data['page'] + 1 . '" ';
        }
        echo 'class="pagination__link">&gt;&gt;</a>';
    } else {
        Component::searchMessageEmpty('Aucune modification enregistrée.');
    }
    echo '</ul>';
    echo '</section>';
} else {
    header('Location: /www/cloud/public/home/index');
    exit;
}
