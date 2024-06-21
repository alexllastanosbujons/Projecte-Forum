<?php
require_once("Orm.php");

class Comment extends Orm{
    public function __construct()
    {
        parent::__construct('comments');
    }
    public static function createTable(){
        $db = new Database();
        $connection = $db->getConnection();
        $sql = "CREATE TABLE `foro`.`comments` 
        (`id` INT NOT NULL AUTO_INCREMENT , 
        `idUser` INT NOT NULL , 
        `idPost` INT NOT NULL , 
        `text` VARCHAR(255) NOT NULL , 
        `date` TIMESTAMP NOT NULL , 
        `likes` INT NOT NULL , 
        `dislikes` INT NOT NULL , 
        PRIMARY KEY (`id`),
        FOREIGN KEY (`idUser`) REFERENCES `foro`.`users`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`idPost`) REFERENCES `foro`.`posts`(`id`) ON DELETE CASCADE);";
        $db->queryDataBase($sql);
        $db = new Database();
        $sql = "CREATE TABLE `foro`.`commentLikes` 
        (`idUser` INT NOT NULL , 
        `idComment` INT NOT NULL ,
        FOREIGN KEY (`idUser`) REFERENCES `foro`.`users`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`idComment`) REFERENCES `foro`.`comments`(`id`) ON DELETE CASCADE);";
        $db->queryDataBase($sql);
        $db = new Database();
        $sql = "CREATE TABLE `foro`.`commentDislikes` 
        (`idUser` INT NOT NULL , 
        `idComment` INT NOT NULL ,
        FOREIGN KEY (`idUser`) REFERENCES `foro`.`users`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`idComment`) REFERENCES `foro`.`comments`(`id`) ON DELETE CASCADE);";
        $db->queryDataBase($sql);
    }

    public function likes($idComment, $idUser,$idPost){
        $commentModel = new Comment();
        $commentLikes = $commentModel->getCommentLikes($idComment);
        $commentModel = new Comment();
        $commentDislikes = $commentModel->getCommentDislikes($idComment);

        foreach ($commentLikes as $like) {
            if ($like['idComment'] == $idComment && $like['idUser'] == $idUser) {
                $this->updateLikeORDislikeComment($idComment, $idUser, 'commentLikes', 'DELETE');
                $this->updateTablePostComment($idComment, 'comment', 'likes', '-');
                $this->db->closeConnection();
                header('Location: /post/index/?idPost=' . $idPost);
            }
        }
        foreach ($commentDislikes as  $disLike) {
            if ($disLike['idComment'] == $idComment && $disLike['idUser'] == $idUser) {
                $this->updateLikeORDislikeComment($idComment, $idUser, 'commentDislikes', 'DELETE');
                $this->updateTablePostComment($idComment, 'comment', 'dislikes', '-');
                $this->updateLikeORDislikeComment($idComment, $idUser, 'commentLikes', 'INSERT');
                $this->updateTablePostComment($idComment, 'comment', 'likes', '+');
                $this->db->closeConnection();
                header('Location: /post/index/?idPost=' . $idComment);
            }
        }
        $this->updateLikeORDislikeComment($idComment, $idUser, 'commentLikes', 'INSERT');
        $this->updateTablePostComment($idComment, 'comment', 'likes', '+');
        $this->db->closeConnection();
        header('Location: /post/index/?idPost=' . $idPost);
    }
    public function dislikes($idComment, $idUser, $idPost){
        $commentModel = new Comment();
        $commentLikes = $commentModel->getCommentLikes($idComment);
        $commentModel = new Comment();
        $commentDislikes = $commentModel->getCommentDislikes($idComment);

        foreach ($commentLikes as $like) {
            if ($like['idComment'] == $idComment && $like['idUser'] == $idUser) {

                $this->updateLikeORDislikeComment($idComment, $idUser, 'commentLikes', 'DELETE');
                $this->updateTablePostComment($idComment, 'comment', 'likes', '-');
                $this->updateLikeORDislikeComment($idComment, $idUser, 'commentDislikes', 'INSERT');
                $this->updateTablePostComment($idComment, 'comment', 'dislikes', '+');
                $this->db->closeConnection();
                header('Location: /post/index/?idPost=' . $idPost);
            }
        }
        foreach ($commentDislikes as  $disLike) {
            if ($disLike['idComment'] == $idComment && $disLike['idUser'] == $idUser) {
                $this->updateLikeORDislikeComment($idComment, $idUser, 'commentDislikes', 'DELETE');
                $this->updateTablePostComment($idComment, 'comment', 'dislikes', '-');
                $this->db->closeConnection();
                header('Location: /post/index/?idPost=' . $idComment);
            }
        }
        $this->updateLikeORDislikeComment($idComment, $idUser, 'commentDislikes', 'INSERT');
        $this->updateTablePostComment($idComment, 'comment', 'dislikes', '+');
        $this->db->closeConnection();
        header('Location: /post/index/?idPost=' . $idPost);
    }
    public function getCommentLikes($idComment){
        $sql = "SELECT * FROM commentLikes WHERE idComment = :idComment";
        $params = [
            ":idComment" => $idComment
        ];
        $result = $this->db->queryDataBase($sql, $params);

        return $result->fetchAll();
    }
    public function getCommentDislikes($idComment){
        $sql = "SELECT * FROM commentDislikes WHERE idComment = :idComment";
        $params = [
            ":idComment" => $idComment
        ];
        $result = $this->db->queryDataBase($sql, $params);
        return $result->fetchAll();
    }
}
?>