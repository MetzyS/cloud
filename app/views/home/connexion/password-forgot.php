<body class="login">
    <form action="/www/cloud/app/views/home/traitement/password-forgot.php" method="POST" class="login__form">
        <fieldset>
            <legend class="login__title">Mot de passe oublié</legend>
            <?php
            if (isset($data['error'])) {
                echo '<p class="login__message message message-error">' . $data['error'] . '</p>';
            } elseif (isset($data['message'])) {
                echo '<p class="login__message message message-confirm">' . $data['message'] . '</p>';
                unset($data['message']);
            } else {
                echo '<p class="login__message message">Veuillez entrer une adresse mail associée à un compte.</p>';
            }
            ?>
            <label class="login__label label" for="email">
                <input class="login__input" type="email" name="email" id="email" placeholder="Votre Email" pattern="^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$" maxlength="50" <?php if (isset($data['confirmation'])) {
                                                                                                                                                                                                                echo ' disabled';
                                                                                                                                                                                                            } ?>>
                <?php if (isset($data['confirmation'])) {
                    echo '<span class="label__text"></span>';
                } else {
                    echo '<span class="label__text">Email</span>';
                } ?>
            </label>
            <?php
            if (isset($data['confirmation'])) {
                echo '<a href="/www/cloud/public/home/connect" class="login__link">Se connecter a nouveau</a>';
                unset($data['confirmation']);
            } else {
                echo '<button class="btn btn--primary login__btn" type="submit" id="connect_btn" disabled>Envoyer</button>';
            }
            ?>
        </fieldset>
    </form>
</body>