<?php
class Router
{

    private $controller;
    private $method;

    public function __construct()
    {
        $this->matchRoute();
    }

    public function matchRoute()
    {
        $rutes = array(
            "main/home",
            "comment/store",
            "comment/delete",
            "comment/home",
            "comment/likes",
            "comment/dislikes",
            "post/postView",
            "post/index",
            "post/create",
            "post/store",
            "post/delete",
            "post/updateView",
            "post/update",
            "post/likes",
            "post/dislikes",
            "user/userProfile",
            "user/signOut",
            "user/login",
            "user/create",
            "user/loginCheck",
            "user/index",
            "user/cambiarEstatAdmin",
            "user/store",
            "user/verify",
            "user/update",
            "user/userDelete",
            "reset/run",
        );
        
        $url = explode('/', URL);
        $this->controller = !empty($url[1]) ? $url[1] : 'main';
        $this->controller = $this->controller . 'Controller';
        $this->method = !empty($url[2]) ? $url[2] : 'home';

        $trobat = false;
        foreach ($rutes as $ruta) {

            if ($ruta == $url[1] . "/" . $this->method) {
                require_once("App/Controllers/" . $this->controller . ".php");
                $trobat = true;
                break;
            }
        }
        if (!$trobat) {
            $this->controller = 'mainController';
            $this->method = 'error';
            require_once("App/Controllers/" . $this->controller . ".php");
        }
    }

    public function run()
    {
        $controller = new $this->controller();
        $method = $this->method;
        $controller->$method();
    }

}

?>