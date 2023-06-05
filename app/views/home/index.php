<?php

use App\Components\Component;

?>

<?php
if (isset($_SESSION['file_rename'])) {
    unset($_SESSION['file_rename']);
}
Component::categories();
if (isset($_SESSION['bdd_error'])) {
    echo '<p class="login__message message message-error">' . $_SESSION['bdd_error'] . '</p>';
    unset($_SESSION['bdd_error']);
}
if (isset($_SESSION['message'])) {
    echo '<p class="upload__message message message-error">' . $_SESSION['message'] . '</p>';
    unset($_SESSION['message']);
}
if (isset($_SESSION['create_error'])) {
    echo '<p class="upload__message message message-error">' . $_SESSION['create_error'] . '</p>';
    unset($_SESSION['create_error']);
}
if (isset($_SESSION['create_valid'])) {
    echo '<p class="upload__message message message-confirm">' . $_SESSION['create_valid'] . '</p>';
    unset($_SESSION['create_valid']);
}
?>


<section class="folders">
    <?php
    if (isset($_SESSION['rename_folder_message'])) {
        echo '<p class="upload__message message message-confirm">' . $_SESSION['rename_folder_message'] . '</p>';
        unset($_SESSION['rename_folder_message']);
    }
    if (isset($_SESSION['delete_message'])) {
        Component::errorMessage($_SESSION['delete_message']);
        unset($_SESSION['delete_message']);
    }
    ?>
    <h2 class="section__title">Tous les fichiers</h2>
    <?php
    $dir = '../files';
    $result = Component::scanDirectory($dir);
    $result = Component::arksort($result);
    echo Component::generateFilesList($result);
    ?>
</section>


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
    <h2 class="section__title">Fichiers récents</h2>
    <?php
    Component::fileTable();
    echo '<tbody>';
    foreach ($data['recent_files'] as $key => $value) {
        Component::fileTableItem($value);
    }
    echo '</tbody>';
    echo '</table>';
    ?>
</section>