<?php

use App\Components\Component;
?>

<?php
Component::categories();
?>
<section class="search">
    <h2 class="section__title">Résultat de la recherche</h2>

    <?php
    if (!isset($data['search']['message'])) {
        echo '<p class="search__message"> Résultats pour <span class="search__message-keyword">' . $data['search']['search_keyword'] . '</span> :</p>';
        unset($data['search']['search_keyword']);
        Component::fileTable();
        foreach ($data['search'] as $key => $value) {
            Component::fileTableItem($value);
        }
    } else {
        Component::searchMessageEmpty($data['search']['message']);
    }
    ?>
</section>