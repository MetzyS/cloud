    <?php

    use App\Components\Component;
    ?>
    <section class="upload">
        <?php
        Component::sectionTitle('Téléverser');
        if (isset($_SESSION['upload'])) {
            echo '<p class="upload__message message message-confirm">' . $_SESSION['upload']['message'] . '</p>';
            unset($_SESSION['upload']['message']);
        }
        if (isset($_SESSION['upload_error'])) {
            Component::errorMessage($_SESSION['upload_error']);
            unset($_SESSION['upload_error']);
        }
        if (isset($data['message'])) {
            echo '<p class="upload__message message">' . $data['message'] . '</p>';
            unset($data['message']);
        }
        if (isset($_SESSION['delete_message'])) {
            Component::errorMessage($_SESSION['delete_message']);
            unset($_SESSION['delete_message']);
        }

        Component::uploadForm(); // Affiche le formulaire de téléversement

        ?>
    </section>
    <section class="files">
        <h2 class="section__title">Fichiers téléversés</h2>

        <?php

        if (isset($_SESSION['upload'])) {

            Component::fileTable();
            echo '<tbody>';

            foreach ($_SESSION['upload'] as $key => $value) {
                foreach ($value as $key2 => $values) {
                    Component::fileTableItem($values);
                }
            }

            unset($_SESSION['upload']);
            echo '</tbody>
        </table>';
        } else {
            echo '<p class="padding-mobile"> Aucun fichier récent </p>';
        }

        ?>
    </section>