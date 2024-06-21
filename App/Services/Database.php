<?php

class Database
{
    private $connection;
    private $db_host = "mysql";
    private $db_name = "foro";
    private $db_user = "alexllastanos";
    private $password = "alexllastanos";


    public function __construct()
    {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        
        $this->connection = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_user, $this->password, $options);

        $this->connection->exec("SET CHARACTER SET UTF8");
    }

    public function getConnection()
    {
        return $this->connection;
    }



    public function closeConnection()
    {
        $this->connection = null;
    }

    public function queryDataBase($sql, $params = null, $close = true)
    {
        
        try {
            $statement = $this->connection->prepare($sql);
            if($params != null) {
                $statement->execute($params);
            } else {
                $statement->execute();
            }
            if($close){
                $this->connection = null;
            }
            
            return $statement;
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            echo $ex->getMessage();
            $this->connection = null;
            return null;
        }

    }


}