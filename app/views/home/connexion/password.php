<body class="login">
    <form action="/www/cloud/app/views/home/traitement/password.php" method="POST" class="login__form">
        <span class="login__step">2/2</span>
        <fieldset>
            <legend class="login__title">Connexion</legend>
            <?php
            if (isset($data['message'])) {
                echo '<p class="login__message message message-error">' . $data['message'] . '</p>';
            } else {
                echo '<p class="login__message message">Veuillez entrer votre mot de passe.</p>';
            }
            ?>
            <label class="login__label label" for="password">
                <span class="login__input-container">
                    <input class="login__input login__input--password" type="password" name="password" id="password" placeholder="Votre Mot de passe" pattern="^(?=^.{8,16}$)((?=.*\d)(?=.*\w+))(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[$&+,:;=?@#_*-]).*$" maxlength="16" autocomplete="on">
                    <span class="label__text">Mot de passe</span>
                    <span class="label-icon-container">
                        <svg width="28" height="19" viewBox="0 0 28 19" xmlns="http://www.w3.org/2000/svg" class="login__input-icon" id="icon-show">
                            <path d="M13.75 5.625C12.7554 5.625 11.8016 6.02009 11.0983 6.72335C10.3951 7.42661 10 8.38044 10 9.375C10 10.3696 10.3951 11.3234 11.0983 12.0267C11.8016 12.7299 12.7554 13.125 13.75 13.125C14.7446 13.125 15.6984 12.7299 16.4017 12.0267C17.1049 11.3234 17.5 10.3696 17.5 9.375C17.5 8.38044 17.1049 7.42661 16.4017 6.72335C15.6984 6.02009 14.7446 5.625 13.75 5.625ZM13.75 15.625C12.0924 15.625 10.5027 14.9665 9.33058 13.7944C8.15848 12.6223 7.5 11.0326 7.5 9.375C7.5 7.7174 8.15848 6.12769 9.33058 4.95558C10.5027 3.78348 12.0924 3.125 13.75 3.125C15.4076 3.125 16.9973 3.78348 18.1694 4.95558C19.3415 6.12769 20 7.7174 20 9.375C20 11.0326 19.3415 12.6223 18.1694 13.7944C16.9973 14.9665 15.4076 15.625 13.75 15.625ZM13.75 0C7.5 0 2.1625 3.8875 0 9.375C2.1625 14.8625 7.5 18.75 13.75 18.75C20 18.75 25.3375 14.8625 27.5 9.375C25.3375 3.8875 20 0 13.75 0Z" />
                        </svg>
                        <svg width="28" height="19" viewBox="0 2 28 19" xmlns="http://www.w3.org/2000/svg" class="login__input-icon toggle-display" id="icon-hide">
                            <path d="M13.5375 7.5L17.5 11.45V11.25C17.5 10.2554 17.1049 9.30161 16.4017 8.59835C15.6984 7.89509 14.7446 7.5 13.75 7.5H13.5375ZM8.1625 8.5L10.1 10.4375C10.0375 10.7 10 10.9625 10 11.25C10 12.2446 10.3951 13.1984 11.0983 13.9017C11.8016 14.6049 12.7554 15 13.75 15C14.025 15 14.3 14.9625 14.5625 14.9L16.5 16.8375C15.6625 17.25 14.7375 17.5 13.75 17.5C12.0924 17.5 10.5027 16.8415 9.33058 15.6694C8.15848 14.4973 7.5 12.9076 7.5 11.25C7.5 10.2625 7.75 9.3375 8.1625 8.5ZM1.25 1.5875L4.1 4.4375L4.6625 5C2.6 6.625 0.975 8.75 0 11.25C2.1625 16.7375 7.5 20.625 13.75 20.625C15.6875 20.625 17.5375 20.25 19.225 19.575L19.7625 20.1L23.4125 23.75L25 22.1625L2.8375 0M13.75 5C15.4076 5 16.9973 5.65848 18.1694 6.83058C19.3415 8.00269 20 9.5924 20 11.25C20 12.05 19.8375 12.825 19.55 13.525L23.2125 17.1875C25.0875 15.625 26.5875 13.575 27.5 11.25C25.3375 5.7625 20 1.875 13.75 1.875C12 1.875 10.325 2.1875 8.75 2.75L11.4625 5.4375C12.175 5.1625 12.9375 5 13.75 5Z" />
                        </svg>
                    </span>
                </span>
            </label>
            <div class="login-forgot-container">
                <a href="/www/cloud/public/home/forgot/" class="login__link--forgot">Mot de passe oublié ?</a>
                <button class="btn btn--primary login__btn" type="submit" id="connect_btn" disabled>Envoyer</button>
            </div>
        </fieldset>
    </form>
</body>