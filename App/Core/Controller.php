<?php

    class Controller{

        protected function render($path, $parameters=[], $layout=""){
            $llista = $parameters;
            if (isset($_SESSION['userLogged'])) {
                $userLogged = $_SESSION['userLogged'];
            }
            ob_start();
            require_once(__DIR__ . "/../Views/" . $path . ".view.php");
            $content = ob_get_clean();
            require_once(__DIR__ . "/../Views/layouts/" . $layout. ".layout.php");
        }

    }

?>