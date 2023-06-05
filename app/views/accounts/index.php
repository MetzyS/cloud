<?php

use App\Components\Component;
// Lorsqu'un nouvel utilisateur est crée avec un mot de passe généré aléatoirement, le mot de passe sera affiché.
// Cette fonctionnalité sera a supprimer lorsque vous passerez de dev à prod afin de respecter le RGPD (lignes 23 à 27 et lignes 38 à 42)
?>
<section class="comptes">
    <?php
    Component::sectionTitle('Comptes');
    if (isset($_SESSION['bdd_error'])) {
        echo '<p class="login__message message message-error">' . $_SESSION['bdd_error'] . '</p>';
        unset($_SESSION['bdd_error']);
    }
    if (isset($_SESSION['delete'])) {
        echo '<p class="message">' . $_SESSION['delete'] . '</p>';
        unset($_SESSION['delete']);
    }
    if (isset($_SESSION['update']['update_message'])) {
        echo '<p class="message message-confirm">' . $_SESSION['update']['update_message'] . '</p>';
        unset($_SESSION['update']['update_message']);
    }
    if (isset($_SESSION['update']['update_password'])) {
        echo '<table class="password__table"><thead><tr><td>Mail</td><td>Mdp</td></tr></thead>';
        foreach ($_SESSION['update']['update_password'] as $key => $values) {
            echo '<tr><td>' . $key . '</td><td>' . $values . '</td></tr>';
        }
        echo '</table>';
        unset($_SESSION['update']['update_password']);
    }
    if (isset($_SESSION['create_error'])) {
        echo '<p class="message message-error">' . $_SESSION['create_error'] . '</p>';
        unset($_SESSION['create_error']);
    }
    if (isset($_SESSION['create'])) {
        echo '<p class="message">' . $_SESSION['create']['message'] . '</p>';

        if (isset($_SESSION['create']['password'])) {
            echo '<table class="password__table"><thead><tr><td>Mail</td><td>Mdp</td></tr></thead>';
            foreach ($_SESSION['create']['password'] as $key => $values) {
                echo '<tr><td>' . $key . '</td><td>' . $values . '</td></tr>';
            }
            echo '</table>';
        }

        unset($_SESSION['create']);
    }
    ?>
    <form action="/www/cloud/app/views/accounts/traitement/modify.php" class="comptes__form" method="POST">
        <table class="comptes__table">
            <thead>
                <tr class="comptes__row">
                    <td class="comptes__cell">id</td>
                    <td class="comptes__cell">Nom</td>
                    <td class="comptes__cell">Prenom</td>
                    <td class="comptes__cell">Mail</td>
                    <td class="comptes__cell">Mdp</td>
                    <td class="comptes__cell">Role</td>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php
                foreach ($data as $keys => $values) {
                    for ($i = 0; $i < count($values); $i++) {
                        if ($values[$i]['superadmin'] == '1') {
                            continue;
                        }
                        $id = $i + 1;
                        echo '<tr class="comptes__row compte';
                        if ($values[$i]['role'] === 'admin') {
                            echo ' compte--admin';
                        };
                        echo '">';
                        echo '<td class="comptes__cell">';
                        echo '<span class="compte__label">id</span>';
                        echo '<span class="compte__id">' . $values[$i]['id_user'] . '</span>';
                        echo '</td>';

                        echo '<td class="comptes__cell">';
                        echo '<label for="userLastName_' . $id . '" class="compte__label">Nom</label>';
                        echo '<input type="text" class="compte__input" maxlength="100" required name="user_exist[' . $values[$i]['id_user'] . ']' . '[user_surname]" id="userLastName_' . $id . '" value="' . $values[$i]['user_surname'] . '">';
                        echo '</td>';
                        echo '<td class="comptes__cell">';
                        echo '<label for="userFirstName_' . $id . '" class="compte__label">Prenom</label>';
                        echo '<input type="text" class="compte__input" maxlength="100" required name="user_exist[' . $values[$i]['id_user'] . ']' . '[user_name]" id="userFirstName_' . $id . '" value="' . $values[$i]['user_name'] . '">';
                        echo '</td>';
                        echo '<td class="comptes__cell comptes__cell--mail">';
                        echo '<label for="userMail_' . $id . '" class="compte__label">Mail</label>';
                        echo '<input type="text" class="compte__input" maxlength="145" required name="user_exist[' . $values[$i]['id_user'] . ']' . '[email]" id="userMail_' . $id . '" value="' . $values[$i]['email'] . '"';
                        echo ' pattern="^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,5})$">';
                        echo '</td>';
                        echo '<td class="comptes__cell comptes__cell--mdp">
                        <div class="wrapper">
                            <div class="container">
                                <label for="userMdp_' . $id . '" class="compte__label">Mdp</label>
                                <input type="text" class="compte__input account__input--password" name="user_exist[' . $values[$i]['id_user'] . ']' . '[password]" id="userMdp_' . $id . '" pattern="^(?=^.{8,16}$)((?=.*\d)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[$&+,:;=?@#_*-]).*$" maxlength="16" disabled>
                            </div>
                            <div class="container">
                                <label class="compte__label" for="userMdpCheckbox_' . $id . '">Changer mot de pass</label>
                                <input type="checkbox" class="profil__input--checkbox account-checkbox" name="user_exist[' . $values[$i]['id_user'] . ']' . '[userMdpCheckbox]" id="userMdpCheckbox_' . $id . '">
                            </div>
                        </div>
                        </td>';
                        echo '<td class="comptes__cell compte__cell--role">';
                        echo '<label for="userRole_' . $id . '" class="compte__label">Role</label>';
                        echo '<select name="user_exist[' . $values[$i]['id_user'] . ']' . '[role]" id="userRole_' . $id . '">';
                        echo '<option value="user"';
                        if ($values[$i]['role'] === 'user') {
                            echo ' selected';
                        };
                        echo '>User</option>';
                        echo '<option value="admin"';
                        if ($values[$i]['role'] === 'admin') {
                            echo ' selected';
                        };
                        echo '>Admin</option>';
                        echo '</select>';
                        if ($values[$i]['superadmin'] != '1') {
                            echo '<a href="/www/cloud/public/account/delete/' . $values[$i]['id_user'] . '" class="btn--delete compte__link">Supprimer</a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                }
                if (isset($_SESSION['accounts'])) {
                    unset($_SESSION['accounts']);
                }
                ?>
            </tbody>
        </table>
        <button type="button" class="btn btn--secondary comptes__btn--add" id="comptes--add">Ajouter un compte</button>
        <button type="submit" class="btn btn--primary comptes__btn" id="comptes--submit">Sauvegarder</button>
    </form>
</section>