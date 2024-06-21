<?php
if (!isset($_SESSION)) session_start();
    require_once("config.php");
    require_once("Router.php");
    require_once("Core/Controller.php");
    require_once("Models/User.php");
    require_once("Models/Post.php");
    require_once("Models/Comment.php");
    require_once(__DIR__ . "/Services/Database.php");

    
    ?>