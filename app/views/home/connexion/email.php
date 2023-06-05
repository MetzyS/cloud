<body class="login">
    <form action="/www/cloud/app/views/home/traitement/email.php" method="POST" class="login__form">
        <span class="login__step">1/2</span>
        <fieldset>
            <legend class="login__title">Connexion</legend>
            <?php
            if (isset($data['confirm'])) {
                echo '<p class="login__message message message-confirm">' . $data['confirm'] . '</p>';
            }
            if (isset($_SESSION['bdd_error'])) {
                echo '<p class="login__message message message-error">' . $_SESSION['bdd_error'] . '</p>';
                unset($_SESSION['bdd_error']);
            }
            if (isset($data['message'])) {
                echo '<p class="login__message message message-error">' . $data['message'] . '</p>';
            } elseif (isset($_SESSION['message'])) {
                echo '<p class="login__message message message-confirm">' . $_SESSION['message'] . '</p>';
                unset($_SESSION['message']);
            } else {
                echo '<p class="login__message message">Veuillez entrer une adresse mail associée à un compte.</p>';
            }
            ?>
            <label class="login__label label" for="email">
                <input class="login__input " type="email" name="email" id="email" placeholder="Votre Email" pattern="^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,5})$" maxlength="50" autocomplete="on">
                <span class=" label__text">Email</span>
            </label>
            <button class="btn btn--primary login__btn" type="submit" id="connect_btn" disabled>Suivant</button>
        </fieldset>
    </form>
</body>