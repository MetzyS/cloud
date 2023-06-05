<?php

use App\Components\Component;
?>
<section class="profil">
    <?php
    Component::sectionTitle('Profil');
    if (isset($data['message'])) {
        echo '<p class="message message-confirm">' . $data['message'] . '</p>';
    }
    if (isset($data['error'])) {
        echo '<p class="message message-error">' . $data['error'] . '</p>';
    }
    if (isset($_SESSION['update_error'])) {
        echo '<p class="message message-error">' . $_SESSION['update_error'] . '</p>';
        unset($_SESSION['update_error']);
    }
    ?>

    <form action="/www/cloud/app/views/profile/traitement/profile.php" method="POST" class="profil__form">
        <div class="profil-container">
            <label for="nom" class="profil__label">Nom</label>
            <input type="text" name="nom" id="nom" placeholder="Nom" class="profil__input" value="<?= $_SESSION['user']['user_surname'] ?>" maxlength="100" required>
        </div>
        <div class="profil-container">
            <label for="prenom" class="profil__label">Prenom</label>
            <input type="text" name="prenom" id="prenom" placeholder="Prenom" class="profil__input" value="<?= $_SESSION['user']['user_name'] ?>" maxlength="100" required>
        </div>
        <div class="profil-container">
            <label for="email" class="profil__label">Mail</label>
            <input type="email" name="email" id="email" placeholder="Mail" class="profil__input" value="<?= $_SESSION['user']['email'] ?>" pattern="^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,5})$" maxlength="145" required>
        </div>
        <div class="profil-container">
            <label class="profil__label" for="password_new">Mot de passe</label>
            <input class="profil__input profil__input--password" type="text" name="password_new" id="password_new" placeholder="Mot de passe" pattern="^(?=^.{8,16}$)((?=.*\d)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[$&+,:;=?@#_*-]).*$" maxlength="16" autocomplete="on" disabled>
        </div>
        <div class="profil-container profil-container--mdp-new">
            <label class="profil__label" for="password_checkbox">Changer mot de passe</label>
            <input type="checkbox" class="profil__input--checkbox" name="password_checkbox" id="password_checkbox">
        </div>
        <button type="submit" class="btn btn--primary profil__btn">Modifier</button>
    </form>
</section>