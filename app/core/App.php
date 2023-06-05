<?php

namespace App\Core;

//Systeme de routage
class App
{
    //Lors de l'initialisation de l'application, on utilisera home et index par défaut 
    protected $controller = 'Home';
    protected $method = 'connect';
    protected $params = [];


    public function __construct()
    {
        $url = $this->parseUrl();



        if (is_null($url)) {
            $url[0] = 'Home';
        }

        //Verifie si un controleur qui porte le nom $url[0] existe
        //Si il existe, change la valeur de $controller 
        if (file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]);

            unset($url[0]);
        }


        //Créer une instance du controleur (pour utiliser les methodes du controleur)
        require('../app/controllers/' . $this->controller . 'Controller.php');
        $this->controller = new $this->controller;


        //Verifie si la méthode qui porte le nom $url[1] existe dans le controleur 
        //Si elle existe, change la valeur de $method 
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        //Verifie si $url existe 
        //Existe : reorganise index 
        $this->params = $url ? array_values($url) : [];

        //Permet d'utiliser la methode qui existe dans le controleur avec des parametres si besoin
        call_user_func_array([$this->controller, $this->method], $this->params);
        include_once '../app/views/template/footer.php';
    }

    /**
     * Permet de decouper l'url filtré dans un tableau
     *
     * @return array
     */
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            $url = filter_var(str_replace(' ', '%20', $_GET['url']), FILTER_SANITIZE_URL);
            $url = explode('/', rtrim($url, '/'));
            return $url;
        }
    }
}
