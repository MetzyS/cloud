<?php

use App\Components\Component;

?>
<?php
Component::categories();

?>


<section class="files">
    <?php
    if (isset($_SESSION['rename_message'])) {
        echo '<p class="upload__message message message-confirm">' . $_SESSION['rename_message'] . '</p>';
        unset($_SESSION['rename_message']);
    }
    if (isset($_SESSION['rename_error'])) {
        echo '<p class="upload__message message message-error">' . $_SESSION['rename_error'] . '</p>';
        unset($_SESSION['rename_error']);
    }
    ?>
    <h2 class="section__title">Résultat de la recherche</h2>
    <?php
    Component::fileTable();
    echo '<tbody>';
    foreach ($data['files'] as $key => $value) {
        Component::fileTableItem($value);
    }
    echo '</tbody>';
    echo '</table>';
    ?>
</section>