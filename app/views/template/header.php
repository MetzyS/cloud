<main>
    <section class="header">
        <h2 class="visibility-hidden">profil</h2>
        <form action="/www/cloud/app/views/files/traitement/search.php" method="POST" class="header__form">
            <input type="text" name="search" id="desktop_search" placeholder="Chercher un fichier">
            <span class="header__form-icon"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15">
                    <path d="M13.833 15l-5.25-5.25c-.417.333-.896.597-1.437.792a5.08 5.08 0 0 1-1.729.292c-1.514 0-2.795-.524-3.843-1.573A5.23 5.23 0 0 1 0 5.417c0-1.514.524-2.795 1.573-3.843A5.23 5.23 0 0 1 5.417 0C6.931 0 8.212.524 9.26 1.573s1.573 2.33 1.573 3.843a5.08 5.08 0 0 1-.292 1.729c-.194.542-.458 1.021-.792 1.438l5.25 5.25L13.833 15zM5.417 9.167c1.042 0 1.927-.365 2.657-1.094a3.61 3.61 0 0 0 1.093-2.656c0-1.042-.365-1.927-1.094-2.657a3.61 3.61 0 0 0-2.656-1.093c-1.042 0-1.927.365-2.657 1.094a3.61 3.61 0 0 0-1.093 2.656c0 1.042.365 1.927 1.094 2.657a3.61 3.61 0 0 0 2.656 1.093z" />
                </svg></span>
            <div class="suggestion"></div>
        </form>
        <div class="profile">
            <img src="/www/cloud/public/assets/image/avatar/avatar_1.jpg" alt="Avatar" width="40" height="40" class="profile__image">
            <span>Bonjour <?php echo $_SESSION['user']['user_name'] ?></span>
            <ul class="profile__list">
                <li class="profile__item">
                    <a href="#" class="profile__item--infos tooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15">
                            <path d="M9.803 6.938l-.675.69c-.54.54-.877.997-.877 2.122h-1.5v-.375a2.99 2.99 0 0 1 .878-2.122l.93-.945C8.835 6.038 9 5.663 9 5.25a1.5 1.5 0 0 0-1.5-1.5A1.5 1.5 0 0 0 6 5.25H4.5a3 3 0 0 1 3-3 3 3 0 0 1 3 3 2.4 2.4 0 0 1-.697 1.688zM8.25 12.75h-1.5v-1.5h1.5M7.5 0a7.5 7.5 0 0 0-5.303 2.197 7.5 7.5 0 0 0 0 10.607A7.5 7.5 0 0 0 7.5 15 7.5 7.5 0 0 0 15 7.5 7.51 7.51 0 0 0 7.5 0z" />
                        </svg>
                        <span class="tooltip__text">Informations</span>
                    </a>
                </li>
                <li class="profile__item">
                    <a href="/www/cloud/public/profile/index/" class="profile__item--settings tooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14">
                            <path d="M4.781 12.75l-.25-2c-.135-.052-.263-.115-.383-.187l-.351-.234-1.859.781L.219 8.141l1.609-1.219c-.01-.073-.016-.143-.016-.211v-.421c0-.068.005-.138.016-.211L.219 4.859l1.719-2.969 1.859.781a4.26 4.26 0 0 1 .359-.234c.125-.073.25-.135.375-.187l.25-2h3.438l.25 2c.135.052.263.115.383.188l.351.234 1.859-.781 1.719 2.969-1.609 1.219c.01.073.016.143.016.211v.421a.77.77 0 0 1-.031.211l1.609 1.219-1.719 2.969-1.844-.781a4.24 4.24 0 0 1-.359.234 3.15 3.15 0 0 1-.375.188l-.25 2H4.781zm1.75-4.062c.604 0 1.12-.214 1.547-.641s.641-.943.641-1.547-.214-1.12-.641-1.547-.943-.641-1.547-.641a2.1 2.1 0 0 0-1.555.641c-.422.427-.633.943-.633 1.547a2.12 2.12 0 0 0 .633 1.547 2.1 2.1 0 0 0 1.555.641z" />
                        </svg>
                        <span class="tooltip__text">Profil</span>
                    </a>
                </li>
                <li class="profile__item">
                    <a href="/www/cloud/app/views/home/connexion/deconnexion.php" class="profile__item--disconnect tooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15">
                            <path d="M11.667 4.167l-1.175 1.175 1.317 1.325H5v1.667h6.808L10.492 9.65l1.175 1.183L15 7.5l-3.333-3.333zm-10-2.5H7.5V0H1.667A1.67 1.67 0 0 0 0 1.667v11.667A1.67 1.67 0 0 0 1.667 15H7.5v-1.667H1.667V1.667z" />
                        </svg>
                        <span class="tooltip__text">Déconnexion</span>
                    </a>
                </li>
            </ul>
        </div>
    </section>