<?php
class Orm
{

    protected $model;
    protected $db;

    public function __construct($model)
    {
        
        $this->model = $model;
        $this->db = new Database();
    }

    public function getById($id){
        $sql = "SELECT * FROM $this->model WHERE id= :id";
        $params = [
            ":id" => $id
        ];
        $result = $this->db->queryDataBase($sql, $params);
        return $result->fetch();
    }

    public function getByUsername($username){
        $sql = "SELECT * FROM $this->model WHERE username= :username";
        $params = [
            ":username" => $username
        ];
        $result = $this->db->queryDataBase($sql, $params);
        return $result->fetch();
    }

    public function getAll(){
        $sql = "SELECT * FROM " . $this->model;
        $params = [];
        $result = $this->db->queryDataBase($sql, $params);
        return $result->fetchAll();
    }

    public function store($item){
        if(isset($item['id'])){
            $values_sql_update = "";
            foreach($item as $key => $value){
                if($key!='id'){
                    $values_sql_update .= "$key = :$key, ";
                }
            }
            $values_sql_update = substr($values_sql_update,0,-2);
            $sql = "UPDATE $this->model SET $values_sql_update WHERE id=:id";

        }else{
            $r = array_keys($item);
            $column = implode(", ", $r);
            $values = ":" . implode(", :", $r);
    
            $db = new Database();
            $sql = "INSERT INTO `" . $this->model . "` ($column) VALUES ($values);";
    
        
        }

        foreach($item as $key => $value){
            $params[":$key"] = $value;
        }
        
        //update mps set mp_number=:mp_number,mp_name=:mp_name WHERE id = :id;
        $result = $this->db->queryDataBase($sql, $params);
        return $result;
    }

    public function delete($id){
        $sql = "DELETE FROM $this->model WHERE id= :id";
        $params = [
            ":id" => $id
        ];

        $result = $this->db->queryDataBase($sql, $params);
        return $result;
    }
    public function updateLikeORDislikePost ($idPost, $idUser, $table, $action){
        $db = new Database();
        if($action == 'INSERT'){
            $sql = "INSERT INTO " . $table . " (idPost, idUser) VALUES (:idPost, :idUser)";
            $params = [
                ":idPost" => $idPost,
                ":idUser" => $idUser
            ];
        }elseif ($action == 'DELETE') {
        $sql = "DELETE FROM  " . $table ." WHERE idPost = :idPost AND idUser = :idUser;";
        $params = [
            ":idPost" => $idPost,
            ":idUser" => $idUser
        ];
        }
        
        $result = $this->db->queryDataBase($sql, $params, false);
        return $result;
    }
    public function updateLikeORDislikeComment ($idComment, $idUser, $table, $action){
        $db = new Database();
        if($action == 'INSERT'){
            $sql = "INSERT INTO " . $table . " (idUser , idComment) VALUES ( :idUser , :idComment)";
            $params = [
                ":idUser" => $idUser,
                ":idComment" => $idComment
            ];
        }elseif ($action == 'DELETE') {
        $sql = "DELETE FROM  " . $table ." WHERE  idUser = :idUser AND idComment = :idComment;";
        $params = [
            ":idUser" => $idUser,
            ":idComment" => $idComment
        ];
        }
        
        $result = $this->db->queryDataBase($sql, $params, false);
        return $result;
    }

    public function updateTablePostComment($id, $type, $likeOrDislike, $operator){
        if ($type == 'comment') {
            $commentModel = new Comment();
            $update = $commentModel->getById($id);
        }elseif($type  == 'post'){
            $postModel = new Post();
            $update = $postModel->getById($id);
        }
        if ($operator=='+') {
            $update[$likeOrDislike]++;
        }elseif ($operator == '-') {
            $update[$likeOrDislike]--;
        }
        if ($type == 'comment') {
            $commentModel = new Comment();
            $commentModel->store($update);
        }elseif($type  == 'post'){
            $postModel = new Post();
            $postModel->store($update);
        }
        

    }


}