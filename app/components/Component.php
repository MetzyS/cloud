<?php

namespace App\Components;

define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);

class Component
{
    protected $result;
    /**
     * Converti les tailles : bytes => b, kb, mb, gb..
     * @param int $bytes = Taille du fichier en bytes
     */
    public static function human_filesize(int $bytes, $dec = 2): string
    {

        $size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        if ($factor == 0) $dec = 0;


        return sprintf("%.{$dec}f %s", $bytes / (1024 ** $factor), $size[$factor]);
    }

    /**
     * Affiche le menu "comptes"
     */
    public static function menuAccount()
    {
        $url_get = filter_input(INPUT_GET, 'url');
        $active = explode('/', $url_get);
        $active = $active[0];
        echo '<li class="nav__item">
<a href="/www/cloud/public/account/index" class="nav__link';
        if ($active === "account") {
            echo ' nav__link--active';
        }
        echo '"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="11">
        <path d="M10.5 9.533V11H0V9.533S0 6.6 5.25 6.6s5.25 2.933 5.25 2.933zM7.875 2.567c0-.508-.154-1.004-.442-1.426S6.734.39 6.255.195A2.68 2.68 0 0 0 4.738.049c-.509.099-.977.343-1.344.702a2.55 2.55 0 0 0-.718 1.314c-.101.498-.049 1.014.149 1.483a2.58 2.58 0 0 0 .967 1.152c.432.282.939.433 1.458.433.696 0 1.364-.27 1.856-.752a2.54 2.54 0 0 0 .769-1.815zm2.58 4.033c.461.349.838.792 1.105 1.299s.417 1.065.44 1.634V11h3V9.533s0-2.662-4.545-2.933zM9.75 0c-.516-.003-1.021.148-1.447.433a3.61 3.61 0 0 1 .701 2.134 3.61 3.61 0 0 1-.701 2.134c.426.285.931.435 1.447.433.696 0 1.364-.27 1.856-.752a2.54 2.54 0 0 0 .769-1.815 2.54 2.54 0 0 0-.769-1.815C11.114.27 10.446 0 9.75 0z" />
    </svg>Comptes</a>
</li>';
    }

    /**
     * Affiche le menu "historique"
     */
    public static function menuLogs()
    {
        $url_get = filter_input(INPUT_GET, 'url');
        $active = explode('/', $url_get);
        $active = $active[0];
        echo '<li class="nav__item display-none">
<a href="/www/cloud/public/logs/index" class="nav__link';
        if ($active === "logs") {
            echo ' nav__link--active';
        }
        echo '"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="14"><defs></defs><title>notepad-2-glyph</title><path d="M396.8,25.6h0V12.8a12.8,12.8,0,0,0-25.6,0V25.6H332.8V12.8a12.8,12.8,0,0,0-25.6,0V25.6H268.8V12.8a12.8,12.8,0,0,0-25.6,0V25.6H204.8V12.8a12.8,12.8,0,0,0-25.6,0V25.6H140.8V12.8a12.8,12.8,0,0,0-25.6,0V25.6h0a64,64,0,0,0-64,64V448a64,64,0,0,0,64,64H307.34L460.8,358.4V89.6A64,64,0,0,0,396.8,25.6Zm-230.4,128H345.22a12.8,12.8,0,0,1,0,25.6H166.4a12.8,12.8,0,0,1,0-25.6Zm0,51.2H345.6a12.8,12.8,0,0,1,0,25.6H166.4a12.8,12.8,0,0,1,0-25.6Zm102.4,76.8H166.4a12.8,12.8,0,0,1,0-25.6H268.8a12.8,12.8,0,0,1,0,25.6Zm25.6,204.8V409.6a64,64,0,0,1,64-64h76.8Z"/></svg>Logs</a>
</li>';
    }

    /**
     * Génère début tableau HTML (table + thead)
     */
    public static function fileTable()
    {
        echo '
        <table class="files__table">
            <thead>
                <tr>
                    <th class="files__cell">Nom</th>
                    <th class="files__cell files__cell--mobile">Date</th>
                    <th class="files__cell files__cell--desktop">Type</th>
                    <th class="files__cell files__cell--desktop">Taille</th>
                    <th class="files__cell files__cell--desktop">Dernière modification</th>
                    <th class="toggle-display"></th>
                </tr>
            </thead>';
    }

    /**
     * Génère les <tr> pour l'affichage des items dans le tableau de fichiers
     */
    public static function fileTableItem(array $array)
    {
        $url_get = filter_input(INPUT_GET, 'url', FILTER_DEFAULT);
        $active = explode('/', $url_get);
        $active = $active[0];
        $id = $array['id_file'];
        $name = $array['file_name'];
        $date = strtotime($array['created_at']);
        $date = date('d-m-Y', $date);
        $path = $array['path'];
        $path = '/www/cloud/' . substr($path, 3);
        $size = $array['size'];
        $size = Component::human_filesize($size);
        $category = ucfirst($array['category_name']);

        echo '<tr>
        <td class="files__cell filename">
            <span class="filename__icon filename__icon--' . $array['category_name'] . '"></span>
            <span class="filename__text">' . $name . '</span> 
        </td>
        <td class="files__cell files__cell--mobile">' . $date . '</td>
        <td class="files__cell files__cell--download files__cell--mobile">
            <a class="btn btn--primary link--download" href="' . $path . '" download>Télécharger</a>
        </td>
        <td class="files__cell files__cell--desktop">' . $category . '</td>
        <td class="files__cell files__cell--desktop">' . $size . '</td>
        <td class="files__cell files__cell--desktop">' . $date . '</td>
        <td class="files__cell files__cell--desktop file-action">
            <button class="file-action__btn file-action__btn-js">
                <svg xmlns="http://www.w3.org/2000/svg" width="3" height="12" class="file-action__btn-js">
                    <path class="file-action__btn-js" d="M1.5 12c-.413 0-.766-.147-1.06-.441A1.44 1.44 0 0 1 0 10.5c0-.412.147-.766.441-1.06A1.44 1.44 0 0 1 1.5 9c.413 0 .766.147 1.06.441A1.44 1.44 0 0 1 3 10.5c0 .412-.147.766-.441 1.06A1.44 1.44 0 0 1 1.5 12zm0-4.5c-.413 0-.766-.147-1.06-.441A1.44 1.44 0 0 1 0 6c0-.412.147-.766.441-1.06A1.44 1.44 0 0 1 1.5 4.5c.413 0 .766.147 1.06.441A1.44 1.44 0 0 1 3 6c0 .412-.147.766-.441 1.06A1.44 1.44 0 0 1 1.5 7.5zm0-4.5c-.413 0-.766-.147-1.06-.441A1.44 1.44 0 0 1 0 1.5C0 1.088.147.734.441.44A1.44 1.44 0 0 1 1.5 0c.413 0 .766.147 1.06.441A1.44 1.44 0 0 1 3 1.5c0 .413-.147.766-.441 1.06A1.44 1.44 0 0 1 1.5 3z" />
                </svg>
                <span class="visibility-hidden">Click</span>
            </button>
            <ul class="file-action__list ';
        if ($_SESSION['user']['role'] == 'user') {
            echo 'file-action__list--user';
        }
        echo '">
                <li class="file-action__item">
                    <a href="' . $path . '" class="file-action__link link--download" download>Télécharger</a>
                </li>';
        if ($_SESSION['user']['role'] == 'admin') {
            echo '<li class="file-action__item">
                    <button type="button" class="file-action__link link-rename" id="rename_' . $id . '">Renommer</button>
                </li>
                <li class="file-action__item">';
            if ($active !== "file") {
                echo '<button type="button" class="file-action__link file-move-js" id="move_' . $id . '">Déplacer</button>';
            }
            echo '</li>
                <li class="file-action__item">
                    <a href="/www/cloud/public/file/delete/' . $id . '" class="file-action__link link--delete">Supprimer</a>
                </li>';
        }
        echo '</ul>
        </td>
    </tr>';
    }

    /**
     * Génère la section "categories" => "Tous les fichiers", "Images"...
     */
    public static function categories()
    {

        echo '<section class="categories">
        <h2 class="section__title">Catégories</h2>
        <ul class="categories__list ';
        if ($_SESSION['user']['role'] == 'user') {
            echo 'categories__list--user';
        }
        echo '">';
        if ($_SESSION['user']['role'] == 'admin') {
            echo '<li class="categories__item categories__item--desktop display-none">
                <button type="button" class="categories__link" id="add-folder-btn">
                    <span class="categories__block categories__block--add-folder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none">
                            <path d="M9 15h2v-4h4V9h-4V5H9v4H5v2h4v4zm1 5c-1.383 0-2.683-.263-3.9-.788s-2.275-1.238-3.175-2.137A10.09 10.09 0 0 1 .788 13.9C.263 12.683.001 11.383 0 10s.263-2.683.788-3.9 1.238-2.275 2.137-3.175S4.883 1.313 6.1.788 8.617.001 10 0s2.683.263 3.9.788 2.275 1.238 3.175 2.137 1.613 1.958 2.138 3.175A9.72 9.72 0 0 1 20 10c0 1.383-.263 2.683-.788 3.9s-1.238 2.275-2.137 3.175-1.958 1.613-3.175 2.138A9.72 9.72 0 0 1 10 20z" />
                        </svg>
                        <span class="categories__title display-none">Créer un dossier</span>
                    </span>
                </button>
            </li>';
        }
        echo '<li class="categories__item categories__item--desktop display-none">
                <a href="/www/cloud/public/home/show/" class="categories__link">
                    <div class="categories__block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="24" fill="none">
                            <path d="M18.443 9.595V20.38a2.65 2.65 0 0 1-.784 1.88c-.502.498-1.183.779-1.893.779H3.273c-.71 0-1.391-.28-1.893-.779a2.65 2.65 0 0 1-.784-1.88V2.658A2.65 2.65 0 0 1 1.38.779C1.882.28 2.563 0 3.273 0H8.78a1.79 1.79 0 0 1 1.262.519l7.878 7.823c.335.332.523.783.523 1.253z" fill="#eaeae4" />
                            <path d="M9.357.324v6.66c0 .471.187.923.52 1.256s.785.52 1.256.52h6.66" fill="#babab9" />
                        </svg>
                        <span class="categories__title display-none">Tous les fichiers</span>
                    </div>
                </a>
            </li>
            <li class="categories__item">
                <a href="/www/cloud/public/home/show/1" class="categories__link">
                    <div class="categories__block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="55" fill="none">
                            <path d="M47.95 22.505v25.298c0 1.654-.662 3.24-1.839 4.409a6.3 6.3 0 0 1-4.44 1.826H12.368a6.3 6.3 0 0 1-4.44-1.826c-1.178-1.169-1.839-2.755-1.839-4.409V6.235c0-1.654.662-3.24 1.839-4.409A6.3 6.3 0 0 1 12.368 0h12.918a4.2 4.2 0 0 1 2.959 1.217l18.479 18.35c.785.779 1.225 1.836 1.226 2.938z" fill="#eaeae4" />
                            <path d="M26.638.761v15.623c0 1.105.439 2.164 1.22 2.946s1.841 1.22 2.946 1.22h15.623" fill="#babab9" />
                            <rect y="27.4" width="26.639" height="18.267" rx="1" fill="#9315c0" />
                            <path d="M4.499 40.403v-7.139h1.514v7.139H4.499zm6.333 0l-1.719-5.601H9.07l.029.605.044.855.02.82v3.32H7.81v-7.139H9.87l1.689 5.459h.029l1.792-5.459h2.061v7.139h-1.411v-3.379l.01-.776.034-.835.029-.601h-.044l-1.841 5.591h-1.387zm9.121-4.009h2.832v3.701c-.374.124-.763.223-1.167.298s-.861.107-1.372.107c-.71 0-1.312-.14-1.807-.42s-.871-.693-1.128-1.24-.386-1.219-.386-2.017c0-.749.143-1.396.43-1.943a3.03 3.03 0 0 1 1.265-1.27c.557-.3 1.235-.449 2.036-.449a5.16 5.16 0 0 1 1.118.122c.368.081.701.187 1.001.317l-.503 1.211c-.218-.111-.467-.203-.747-.278s-.573-.112-.879-.112c-.439 0-.822.101-1.147.303s-.573.485-.752.85-.264.788-.264 1.279c0 .466.063.879.19 1.24s.327.64.601.845.63.303 1.069.303a4.21 4.21 0 0 0 .542-.029l.42-.068v-1.489h-1.353v-1.26z" fill="#fff" />
                        </svg>
                        <span class="categories__title display-none">Images</span>
                    </div>
                </a>
            </li>
            <li class="categories__item">
                <a href="/www/cloud/public/home/show/2" class="categories__link">
                    <div class="categories__block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="55" fill="none">
                            <path d="M47.95 22.505v25.298c0 1.654-.662 3.24-1.839 4.409a6.3 6.3 0 0 1-4.44 1.826H12.368a6.3 6.3 0 0 1-4.44-1.826c-1.178-1.169-1.839-2.755-1.839-4.409V6.235c0-1.654.662-3.24 1.839-4.409A6.3 6.3 0 0 1 12.368 0h12.918a4.2 4.2 0 0 1 2.959 1.217l18.479 18.35c.785.779 1.225 1.836 1.226 2.938z" fill="#eaeae4" />
                            <path d="M26.639.761v15.623c0 1.105.439 2.164 1.22 2.946s1.841 1.22 2.946 1.22h15.623" fill="#babab9" />
                            <rect y="27.4" width="26.639" height="18.267" rx="1" fill="#f24646" />
                            <path d="M7.19 33.265c.921 0 1.593.199 2.017.596s.635.938.635 1.631a2.85 2.85 0 0 1-.142.898 1.97 1.97 0 0 1-.464.757c-.212.221-.495.397-.85.527s-.793.191-1.313.191h-.649v2.539H4.909v-7.139h2.28zm-.078 1.24h-.688v2.119h.498a2.18 2.18 0 0 0 .737-.112c.208-.075.369-.192.483-.351s.171-.365.171-.615c0-.352-.098-.612-.293-.781s-.498-.259-.908-.259zm10 2.261c0 .804-.155 1.476-.464 2.017s-.75.942-1.333 1.216-1.284.405-2.104.405h-2.021v-7.139h2.241c.749 0 1.398.133 1.948.4a2.89 2.89 0 0 1 1.279 1.177c.303.518.454 1.159.454 1.924zm-1.572.039c0-.527-.078-.96-.234-1.299s-.381-.594-.684-.757-.671-.244-1.113-.244h-.806v4.648h.65c.739 0 1.287-.197 1.645-.591s.542-.98.542-1.758zm4.541 3.599h-1.489v-7.139h4.092v1.24H20.08v1.841h2.422v1.235H20.08v2.822z" fill="#fff" />
                        </svg>
                        <span class="categories__title display-none">Pdf</span>
                    </div>
                </a>
            </li>
            <li class="categories__item">
                <a href="/www/cloud/public/home/show/3" class="categories__link">
                    <div class="categories__block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="55" fill="none">
                            <path d="M47.95 22.505v25.298c0 1.654-.662 3.24-1.839 4.409a6.3 6.3 0 0 1-4.44 1.826H12.368a6.3 6.3 0 0 1-4.44-1.826c-1.178-1.169-1.839-2.755-1.839-4.409V6.235c0-1.654.662-3.24 1.839-4.409A6.3 6.3 0 0 1 12.368 0h12.918a4.2 4.2 0 0 1 2.959 1.217l18.479 18.35c.785.779 1.225 1.836 1.226 2.938z" fill="#eaeae4" />
                            <path d="M26.639.761v15.623c0 1.105.439 2.164 1.22 2.946s1.841 1.22 2.946 1.22h15.623" fill="#babab9" />
                            <rect y="27.4" width="26.639" height="18.267" rx="1" fill="#1565c0" />
                            <path d="M14.729 33.265l-1.816 7.139h-1.724l-.967-3.75-.078-.327-.103-.478-.098-.493-.054-.371a4.75 4.75 0 0 1-.059.366l-.093.488-.098.483-.078.342-.962 3.74H6.882l-1.821-7.139H6.55l.913 3.896.088.42.103.513.093.513.063.425.063-.43.083-.503.098-.478.088-.356 1.04-3.999h1.431l1.04 3.999.078.356.098.478.088.508.063.425.093-.591.132-.698.127-.581.908-3.896h1.489zm6.821 3.501c0 .804-.155 1.476-.464 2.017s-.75.942-1.333 1.216-1.284.405-2.105.405h-2.021v-7.139h2.241c.749 0 1.398.133 1.948.4a2.89 2.89 0 0 1 1.279 1.177c.303.518.454 1.159.454 1.924zm-1.572.039c0-.527-.078-.96-.234-1.299s-.381-.594-.684-.757-.671-.244-1.113-.244h-.806v4.648h.649c.739 0 1.288-.197 1.646-.591s.542-.98.542-1.758z" fill="#fff" />
                        </svg>
                        <span class="categories__title display-none">Word</span>
                    </div>
                </a>
            </li>
            <li class="categories__item">
                <a href="/www/cloud/public/home/show/4" class="categories__link">
                    <div class="categories__block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="55" fill="none">
                            <path d="M47.95 22.505v25.298c0 1.654-.662 3.24-1.839 4.409a6.3 6.3 0 0 1-4.44 1.826H12.368a6.3 6.3 0 0 1-4.44-1.826c-1.178-1.169-1.839-2.755-1.839-4.409V6.235c0-1.654.662-3.24 1.839-4.409A6.3 6.3 0 0 1 12.368 0h12.918a4.2 4.2 0 0 1 2.959 1.217l18.479 18.35c.785.779 1.225 1.836 1.226 2.938z" fill="#eaeae4" />
                            <path d="M26.639.761v15.623c0 1.105.439 2.164 1.22 2.946s1.841 1.22 2.946 1.22h15.623" fill="#babab9" />
                            <rect y="27.4" width="26.639" height="18.267" rx="1" fill="#2e7d32" />
                            <path d="M12.468 40.403H8.357v-7.139h4.111v1.24H9.87v1.567h2.417v1.24H9.87v1.841h2.598v1.25zm7.266 0h-1.729l-1.66-2.7-1.66 2.7h-1.621l2.368-3.682-2.217-3.457h1.67l1.538 2.568 1.509-2.568h1.631l-2.241 3.54 2.412 3.599z" fill="#fff" />
                        </svg>
                        <span class="categories__title display-none">Exel</span>
                    </div>
                </a>
            </li>
        </ul>';
        if ($_SESSION['user']['role'] == 'admin') {
            echo '        <form action="/www/cloud/app/views/files/traitement/create_folder.php" class="add-folder__form toggle-display" method="POST">
            <input type="text" class="add-folder__iput" name="folderName" id="folderAddInput" placeholder="Nom du dossier">
            <button type="submit" class="btn btn--primary add-folder__btn">Ok</button>
        </form>';
        }
        echo '</section>';
    }

    /**
     * Fonction récursive qui permet de scanner un dossier.
     * Vérifie si le dossier contient des élements (fichiers ou dossiers)
     * Garde les informations dans un tableau associatif
     * @param string $dir = chemin exemple '../files/'
     * @return array $result = tableau associatif
     */
    public static function scanDirectory(string $dir)
    {
        $result = [];

        // Ouvre le dossier
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) { // Boucle qui tourne tant que $entry est true (donc tant que readdir trouve des elements)
                if ($entry != "." && $entry != "..") { // Ingore '.' et '..' qui sont égal a : 'ici' et 'parent'
                    $entryPath = $dir . '/' . $entry; // Concaténation du chemin
                    // Vérification si l'element trouvé est un fichier ou un dossier
                    if (is_file($entryPath)) { // Si c'est un fichier
                        // Ajoute le nom du fichier dans l'array
                        $keyPath = str_replace('..', '', $entryPath);
                        $keyPath = str_replace('/', '~', $keyPath);
                        $keyPath = str_replace(' ', '+', $keyPath);
                        $keyPath = substr($keyPath, 1);
                        $result[$keyPath] = $entry;
                    } else if (is_dir($entryPath)) { // Si c'est un dossier
                        // Crée un array dans l'array et le rempli et répète la fonction (pour scanner l'interieur de ce dossier)
                        // C'est une fonction récursive
                        $folderPath = explode('/', $entryPath);
                        array_shift($folderPath);
                        array_shift($folderPath);
                        $folderPath = implode('/', $folderPath);
                        $result[$folderPath] = Component::scanDirectory($entryPath);
                    }
                }
            }
            closedir($handle);
        }

        return $result;
    }

    /**
     * Permet de trier un tableau dans l'ordre alphabétique ou numérique (problème avec MAC)
     * @param array $array = tableau a trier
     * @return array $newarray = tableau trié
     */
    public static function arksort($array)
    {
        arsort($array);
        $newarray = array();
        $temp = array();
        $on = current($array);
        foreach ($array as $key => $val) {
            if ($val === $on) $temp[$key] = $val;
            else {
                ksort($temp);
                $newarray = $newarray + $temp;
                $temp = array();
                $on = $val;
                $temp[$key] = $val;
            }
        }
        ksort($temp);
        $newarray = $newarray + $temp;
        reset($newarray);
        ksort($newarray);
        return $newarray;
    }

    /**
     * Génère la liste des dossiers et fichiers présent dans '../files'
     * Fonction récursive (elle cherche dans chaque dossiers)
     * @param array $array = tableau généré par scanDirectory()
     * @param string|null $parent = nom du dossier parent
     */
    public static function generateFilesList($array, $parent = null)
    {
        $image = ['jpeg', 'jpg', 'png', 'gif', 'jfif', 'bmp', 'raw', 'tff', 'heic'];
        $pdf = ['pdf', 'xps'];
        $word = ['docx', 'doc', 'docm', 'dotx', 'txt', 'odt', 'ott'];
        $excel = ['xls', 'xlxm', 'xlsx', 'xml', 'ods', 'ots'];
        $html = '<ul class="drop-down drop-down--none">';
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $folder_name = explode('/', $key);
                $folder_name = end($folder_name);
                $delete = str_replace('/', '$', $key);
                $delete = str_replace(' ', '+', $delete);
                $menu = 'toggle-display';
                if ($_SESSION['user']['role'] == 'admin') {
                    $menu = 'folder-move-js';
                }
                $html .= '<li class="folder-js"><div class="folder-button-wrapper"><span class="folder-status"></span><span class="filename__icon filename__icon--folder"></span><button class="btn-folder-js" type="button" data-path-folder="' . $key . '">' . $folder_name . '</button><button class="' . $menu . '" type="button"></button><ul class="folder-action__list display-none-action-list">
        <li class="folder-action__item">
            <button type="button" class="folder-action__link link-rename-folder" id="' . $key . '">Renommer</button>
        </li>
        <li class="folder-action__item">
            <button type="button" class="folder-action__link folder-move-js2">Déplacer</button>
        </li>
        <li class="folder-action__item">
            <a href="/www/cloud/public/file/deleteFolder/' . $delete . '" class="folder-action__link link--delete">Supprimer</a>
        </li>
    </ul></div>';
                $html .= Component::generateFilesList($value, $folder_name);
            } else {
                $x = explode('.', $value);
                $x = end($x);
                $delete = str_replace('/', '$', $key); // chemin sous forme files~dossier~fichier
                $menu = 'toggle-display';
                if ($_SESSION['user']['role'] == 'admin') {
                    $menu = 'files-move-js';
                }
                if (in_array($x, $image)) {
                    $html .= '<li class="files-js"><div class="file-button-wrapper"><span class="filename__icon filename__icon--images"></span><a href="/www/cloud/public/file/download/' . $key . '" data-path-file="' . $key . '" class="btn-files-js" download>' . $value . '</a><button class="' . $menu . '"  type="button"></button><ul class="files-action__list display-none-action-list">
                    <li class="files-action__item">
                    <button type="button" class="files-action__link link-rename-file" id="' . $key . '">Renommer</button>
                    </li>
                    <li class="files-action__item">
                    <button type="button" class="files-action__link files-move-js2">Déplacer</button>
                    </li>
                    <li class="files-action__item">
                    <a href="/www/cloud/public/file/deleteFile/' . $delete . '" class="files-action__link link--delete">Supprimer</a>
                    </li>
                    </ul></div>';
                }
                if (in_array($x, $pdf)) {
                    $html .= '<li class="files-js"><div class="file-button-wrapper"><span class="filename__icon filename__icon--pdf"></span><a href="/www/cloud/public/file/download/' . $key . '" data-path-file="' . $key . '" class="btn-files-js" download>' . $value . '</a><button class="' . $menu . '"  type="button"></button><ul class="files-action__list display-none-action-list">
                    <li class="files-action__item">
                        <button type="button" class="files-action__link link-rename-file" id="' . $key . '">Renommer</button>
                    </li>
                    <li class="files-action__item">
                        <button type="button" class="files-action__link files-move-js2">Déplacer</button>
                    </li>
                    <li class="files-action__item">
                        <a href="/www/cloud/public/file/deleteFile/' . $delete . '" class="files-action__link link--delete">Supprimer</a>
                    </li>
                </ul></div>';
                }
                if (in_array($x, $word)) {
                    $html .= '<li class="files-js"><div class="file-button-wrapper"><span class="filename__icon filename__icon--word"></span><a href="/www/cloud/public/file/download/' . $key . '" data-path-file="' . $key . '" class="btn-files-js" download>' . $value . '</a><button class="' . $menu . '"  type="button"></button><ul class="files-action__list display-none-action-list">
                    <li class="files-action__item">
                        <button type="button" class="files-action__link link-rename-file" id="' . $key . '">Renommer</button>
                    </li>
                    <li class="files-action__item">
                        <button type="button" class="files-action__link files-move-js2">Déplacer</button>
                    </li>
                    <li class="files-action__item">
                        <a href="/www/cloud/public/file/deleteFile/' . $delete . '" class="files-action__link link--delete">Supprimer</a>
                    </li>
                </ul></div>';
                }
                if (in_array($x, $excel)) {
                    $html .= '<li class="files-js"><div class="file-button-wrapper"><span class="filename__icon filename__icon--excel"></span><a href="/www/cloud/public/file/download/' . $key . '" data-path-file="' . $key . '" class="btn-files-js" download>' . $value . '</a><button class="' . $menu . '"  type="button"></button><ul class="files-action__list display-none-action-list">
                    <li class="files-action__item">
                        <button type="button" class="files-action__link link-rename-file" id="' . $key . '">Renommer</button>
                    </li>
                    <li class="files-action__item">
                        <button type="button" class="files-action__link files-move-js2">Déplacer</button>
                    </li>
                    <li class="files-action__item">
                        <a href="/www/cloud/public/file/deleteFile/' . $delete . '" class="files-action__link link--delete">Supprimer</a>
                    </li>
                </ul></div>';
                }
            }
            // $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * Génère un titre de section
     * @param string $title = Titre de la section
     */
    public static function sectionTitle(string $title)
    {
        echo '<h2 class="section__title">' . $title . '</h2>';
    }

    /**
     * Affiche un message d'erreur en rouge sur fond rose
     * @param string $message = message d'erreur
     */
    public static function errorMessage(string $message)
    {
        echo '<p class="upload__message message message-error">' . $message . '</p>';
    }

    /**
     * Afffiche un message d'erreur si la recherche ne donne pas de résultat
     * @param string $message = Message d'info
     */
    public static function searchMessageEmpty(string $message)
    {
        echo '<p class="upload__message">' . $message . '</p>';
    }

    /**
     * Génère un formulaire de téléversemment
     */
    public static function uploadForm()
    {
        echo '<div class="upload-wrapper">
        <form action="/www/cloud/app/views/files/traitement/upload.php" class="upload__form" method="POST" enctype="multipart/form-data">
            <div class="upload-container">
                <input type="file" name="userFile[]" id="userFile" class="upload__input" multiple required>
                <svg xmlns="http://www.w3.org/2000/svg" class="upload__svg" width="92" height="67" fill="none">
                    <path d="M41.818 67H23c-6.342 0-11.762-2.198-16.259-6.595S-.003 50.634 0 44.283c0-5.444 1.638-10.294 4.914-14.551s7.562-6.979 12.859-8.166C19.515 15.145 23 9.945 28.227 5.967S39.379 0 46 0c8.154 0 15.073 2.845 20.754 8.534s8.521 12.616 8.518 20.778c4.809.558 8.8 2.635 11.973 6.231s4.757 7.8 4.755 12.613c0 5.234-1.83 9.684-5.491 13.35S78.406 67.003 73.182 67h-23V37.059l6.691 6.491 5.855-5.862L46 20.938l-16.727 16.75 5.855 5.862 6.691-6.491V67z" fill="#c9ccff" />
                </svg>
                <span class="upload__text">Glisser-déposer un fichier ou</span>
                <label for="userFile" class="btn btn--secondary upload__label">
                    Parcourir
                </label>
            </div>
            <ul class="upload__list"></ul>
            <input type="submit" id="uploadFile" value="Téléverser" class="btn btn--primary upload__submit" disabled>
        </form>
    </div>';
    }
}
