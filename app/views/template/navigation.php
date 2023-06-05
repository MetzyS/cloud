<?php

use App\Components\Component;

$url_get = filter_input(INPUT_GET, 'url');
$active = explode('/', $url_get);
$active = $active[0];

if ($_SESSION['storage_infos']) {
    $server_storage = Component::human_filesize($_SESSION['storage_infos']['server_capacity']); // récupère infos capacité de stockage serveur
    $used_storage = Component::human_filesize($_SESSION['storage_infos']['storage_used']); // récupère info stockage utilisé
    $storage_ratio = round(($_SESSION['storage_infos']['storage_used'] / $_SESSION['storage_infos']['server_capacity']) * 100);
}
?>
<nav class="nav">
    <div class="nav-container">
        <a href="/www/cloud/public/home/index" class="nav__logo">
            <svg id="efe29cc6-e768-434b-86a3-3cd729f46cf4" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1854.6 1570.05">
                <path d="M1524.53,0c5,.24,5.93.78,8.81,2.2,5.86,39.39-30,99.57-22,141,3.77,19.72,16,46.72,29.75,57.29,24.5,18.91,125.21,45.91,158.65,26.45,33.73-19.64,46.69-45.14,56.19-89.24,5.62-26.1,2-76,26.44-82.63,37.86,55,93.54,138,63.9,234.66-11.36,37.05-33,64-55.09,90.34-33.82,40.34-106.43,50.8-124.49,105.77l-18.73,96.95c-22.35,88-41.5,176.72-72.72,256.7-90.64,232.25-258.16,409.78-490.26,501.29l1.1,2.2c51.38,21,101.55,90.59,126.7,139.92,7.91,15.52,24.64,40.33,24.24,58.39-31.37,5.68-62.3,17.74-97,24.24-119.19,22.37-217-43-263.31-102.46l-43-65c-115.1,1.43-241.45,10.09-349.24-8.82-56.66-9.93-105.1-10.81-149.84-28.64-113.51-45.23-194-133.71-254.5-232.46-30.62-50-117.94-206.42-61.69-277.64,35.11-44.46,129.62-90.29,197.21-101.36l82.63,15.43,117.88,20.93c42.8,9.38,109.42,23.78,167.46,14.32l231.36-11,145.43-15.42c155.67-29.23,301.53-52.9,408.74-128.9,55.22-39.14,98-89.15,98.06-184,0-20.61,1.33-40.63-5.51-57.29-7.67-18.66-24.88-31.09-36.36-46.27-26.76-35.38-63.45-91.7-51.78-158.65C1391.36,96.35,1454.87,50,1524.53,0Z" style="fill:#6fa7da;fill-rule:evenodd" />
            </svg>
        </a>
        <div class="nav__btns">
            <button class="nav__btn nav__btn--open" id="burger_btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" id="btn-open">
                    <path d="M0 0h20v3H0zm0 7h20v3H0zm0 8h20v3H0z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="5 5 20 20" class="toggle-display" id="btn-close">
                    <path d="M20.152 22.274L6.01 8.132 8.132 6.01l14.142 14.142z" />
                    <path d="M6.01 20.152L20.153 6.01l2.121 2.121L8.132 22.274z" />
                </svg>
                <span class="visibility-hidden">Ouvrir/Fermer</span>
            </button>
            <form action="/www/cloud/app/views/files/traitement/search.php" method="POST" class="nav__form-search display-none-search" id="form_toggle">
                <input type="text" name="search" id="nav_search">
                <button type="submit" class="visibility-hidden">Chercher</button>
                <div class="suggestion-mobile"></div>
            </form>
            <button class="nav__btn nav__btn--search" id="search_btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15">
                    <path d="M13.833 15l-5.25-5.25c-.417.333-.896.597-1.437.792a5.08 5.08 0 0 1-1.729.292c-1.514 0-2.795-.524-3.843-1.573A5.23 5.23 0 0 1 0 5.417c0-1.514.524-2.795 1.573-3.843A5.23 5.23 0 0 1 5.417 0C6.931 0 8.212.524 9.26 1.573s1.573 2.33 1.573 3.843a5.08 5.08 0 0 1-.292 1.729c-.194.542-.458 1.021-.792 1.438l5.25 5.25L13.833 15zM5.417 9.167c1.042 0 1.927-.365 2.657-1.094a3.61 3.61 0 0 0 1.093-2.656c0-1.042-.365-1.927-1.094-2.657a3.61 3.61 0 0 0-2.656-1.093c-1.042 0-1.927.365-2.657 1.094a3.61 3.61 0 0 0-1.093 2.656c0 1.042.365 1.927 1.094 2.657a3.61 3.61 0 0 0 2.656 1.093z" />
                </svg>
                <span class="visibility-hidden">Chercher</span>
            </button>
        </div>
        <ul class="nav__list display-none-menu" id="menu_toggle">
            <?php
            if ($_SESSION['user']['role'] == 'admin') {
                echo '<li class="nav__item">
                <a href="/www/cloud/public/file/index" class="btn btn--primary nav__link nav__link--upload">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none">
                        <path d="M6 1v10m5-5H1" stroke-width="2" stroke-linecap="square" stroke-linejoin="round" />
                    </svg>
                    Téléverser
                </a>
            </li>';
            }
            ?>
            <li class="nav__item">
                <a href="/www/cloud/public/home/index" class="nav__link<?php if ($active === "home") {
                                                                            echo ' nav__link--active';
                                                                        } ?>"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="12">
                        <path d="M0 12V4l5.5-4L11 4v8H6.875V7.333h-2.75V12H0z" />
                    </svg>Accueil</a>
            </li>
            <li class=" nav__item">
                <a href="/www/cloud/public/profile/index" class="nav__link<?php if ($active === "profile") {
                                                                                echo ' nav__link--active';
                                                                            } ?>"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13">
                        <path d="M4.781 12.75l-.25-2c-.135-.052-.263-.115-.383-.187l-.351-.234-1.859.781L.219 8.141l1.609-1.219c-.01-.073-.016-.143-.016-.211v-.421c0-.068.005-.138.016-.211L.219 4.859l1.719-2.969 1.859.781a4.26 4.26 0 0 1 .359-.234c.125-.073.25-.135.375-.187l.25-2h3.438l.25 2c.135.052.263.115.383.188l.351.234 1.859-.781 1.719 2.969-1.609 1.219c.01.073.016.143.016.211v.421a.77.77 0 0 1-.031.211l1.609 1.219-1.719 2.969-1.844-.781a4.24 4.24 0 0 1-.359.234 3.15 3.15 0 0 1-.375.188l-.25 2H4.781zm1.75-4.062c.604 0 1.12-.214 1.547-.641s.641-.943.641-1.547-.214-1.12-.641-1.547-.943-.641-1.547-.641a2.1 2.1 0 0 0-1.555.641c-.422.427-.633.943-.633 1.547a2.12 2.12 0 0 0 .633 1.547 2.1 2.1 0 0 0 1.555.641z" />
                    </svg>Profil</a>
            </li>
            <?php
            if (isset($_SESSION['user']['superadmin']) && $_SESSION['user']['superadmin'] == '1') {
                Component::menuAccount();
            }
            if ($_SESSION['user']['role'] == 'admin') {
                Component::menuLogs();
            }
            ?>
            <li class="nav__item nav__item--logout">
                <a href="/www/cloud/app/views/home/connexion/deconnexion.php" class="nav__link"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15">
                        <path d="M11.667 4.167l-1.175 1.175 1.317 1.325H5v1.667h6.808L10.492 9.65l1.175 1.183L15 7.5l-3.333-3.333zm-10-2.5H7.5V0H1.667A1.67 1.67 0 0 0 0 1.667v11.667A1.67 1.67 0 0 0 1.667 15H7.5v-1.667H1.667V1.667z" />
                    </svg>Deconnexion</a>
            </li>
        </ul>
        <div class="storage">
            <div class="storage__item">
                <span class="storage__block--current<?php if ($storage_ratio >= 85) {
                                                        echo '-warning';
                                                    } ?>">
                    <?= $used_storage ?>
                </span> sur
                <?= $server_storage ?>
            </div>
            <input type="range" name="storage-range" class="storage-range<?php if ($storage_ratio >= 85) {
                                                                                echo ' storage-range-warning';
                                                                            } ?>
            " value="<?= $storage_ratio ?>" disabled>
        </div>
    </div>
</nav>