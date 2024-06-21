<?php
require_once("Orm.php");

class User extends Orm{
    public function __construct()
    {
        parent::__construct('users');
    }
    public static function createTable(){
        $db = new Database();
        $connection = $db->getConnection();
        $sql = "CREATE TABLE `foro`. `users` (
            `id` INT NOT NULL AUTO_INCREMENT , 
            `username` VARCHAR(255) NOT NULL , 
            `firstname` VARCHAR(255) NOT NULL , 
            `lastname` VARCHAR(255) NOT NULL , 
            `email` VARCHAR(255) NOT NULL , 
            `birthdate` DATE NOT NULL , 
            `pass` VARCHAR(255) NOT NULL , 
            `profile` VARCHAR(255) DEFAULT 'default.png' , 
            `token` VARCHAR(255) NOT NULL , 
            `verified` BOOLEAN NOT NULL DEFAULT FALSE , 
            `admin` BOOLEAN NOT NULL DEFAULT FALSE , 
            `salt` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id`)) ENGINE = InnoDB;";
        $db->queryDataBase($sql);
    }
    public function getUserLogged(){
        return $_SESSION['userLogged'];
    }
}
?>