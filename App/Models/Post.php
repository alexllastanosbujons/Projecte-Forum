<?php
require_once("Orm.php");

class Post extends Orm
{
    public function __construct()
    {
        parent::__construct('posts');
    }

    public static function createTable()
    {
        $db = new Database();
        $sql = "CREATE TABLE `foro`.`posts` 
        (`id` INT NOT NULL AUTO_INCREMENT , 
        `idUser` INT NOT NULL , 
        `title` VARCHAR(255) NOT NULL ,
        `subtitle` VARCHAR(255) NOT NULL ,
        `description` TEXT NOT NULL , 
        `date` TIMESTAMP NOT NULL ,
        `likes` INT NOT NULL , 
        `dislikes` INT NOT NULL , 
        PRIMARY KEY (`id`),
        FOREIGN KEY (`idUser`) REFERENCES `foro`.`users`(`id`) ON DELETE CASCADE);";
        $db->queryDataBase($sql);
        $db = new Database();
        $sql = "CREATE TABLE `postLikes` (
            `idUser` int NOT NULL,
            `idPost` int NOT NULL,
            FOREIGN KEY (`idUser`) REFERENCES `foro`.`users`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`idPost`) REFERENCES `foro`.`posts`(`id`) ON DELETE CASCADE);";
        $db->queryDataBase($sql);
        $db = new Database();
        $sql = "CREATE TABLE `postDislikes` (
            `idUser` int NOT NULL,
            `idPost` int NOT NULL,
            FOREIGN KEY (`idUser`) REFERENCES `foro`.`users`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`idPost`) REFERENCES `foro`.`posts`(`id`) ON DELETE CASCADE);";
        $db->queryDataBase($sql);
    }
    public function likes($idPost, $idUser)
    {
        $postModel = new Post();
        $postLikes = $postModel->getPostLikes($idPost);
        $postModel = new Post();
        $postDislikes = $postModel->getPostDislikes($idPost);

        foreach ($postLikes as $like) {
            if ($like['idPost'] == $idPost && $like['idUser'] == $idUser) {
                $this->updateLikeORDislikePost($idPost, $idUser, 'postLikes', 'DELETE');
                $this->updateTablePostComment($idPost, 'post', 'likes', '-');
                $this->db->closeConnection();
                header('Location: /post/index/?idPost=' . $idPost);
            }
        }
        foreach ($postDislikes as  $disLike) {
            if ($disLike['idPost'] == $idPost && $disLike['idUser'] == $idUser) {
                $this->updateLikeORDislikePost($idPost, $idUser, 'postDislikes', 'DELETE');
                $this->updateTablePostComment($idPost, 'post', 'dislikes', '-');
                $this->updateLikeORDislikePost($idPost, $idUser, 'postLikes', 'INSERT');
                $this->updateTablePostComment($idPost, 'post', 'likes', '+');
                $this->db->closeConnection();
                header('Location: /post/index/?idPost=' . $idPost);
            }
        }

        $this->updateLikeORDislikePost($idPost, $idUser, 'postLikes', 'INSERT');
        $this->updateTablePostComment($idPost, 'post', 'likes', '+');
        $this->db->closeConnection();
        header('Location: /post/index/?idPost=' . $idPost);
    }


    public function dislikes($idPost, $idUser)
    {

        $postModel = new Post();
        $postLikes = $postModel->getPostLikes($idPost);
        $postModel = new Post();
        $postDislikes = $postModel->getPostDislikes($idPost);

        foreach ($postLikes as $like) {
            if ($like['idPost'] == $idPost && $like['idUser'] == $idUser) {

                $this->updateLikeORDislikePost($idPost, $idUser, 'postLikes', 'DELETE');
                $this->updateTablePostComment($idPost, 'post', 'likes', '-');
                $this->updateLikeORDislikePost($idPost, $idUser, 'postDislikes', 'INSERT');
                $this->updateTablePostComment($idPost, 'post', 'dislikes', '+');
                $this->db->closeConnection();
                header('Location: /post/index/?idPost=' . $idPost);
            }
        }
        foreach ($postDislikes as  $disLike) {
            if ($disLike['idPost'] == $idPost && $disLike['idUser'] == $idUser) {
                $this->updateLikeORDislikePost($idPost, $idUser, 'postDislikes', 'DELETE');
                $this->updateTablePostComment($idPost, 'post', 'dislikes', '-');
                $this->db->closeConnection();
                header('Location: /post/index/?idPost=' . $idPost);
            }
        }

        $this->updateLikeORDislikePost($idPost, $idUser, 'postDislikes', 'INSERT');
        $this->updateTablePostComment($idPost, 'post', 'dislikes', '+');
        $this->db->closeConnection();
        header('Location: /post/index/?idPost=' . $idPost);
    }
    public function comprovarLikeODislike($idPost)
    {
        $userModel = new User();
        $userLogged = $userModel->getUserLogged();
        $postModel = new Post();
        $post = $postModel->getById($idPost);
        $postModel = new Post();
        $postLikes = $postModel->getPostLikes($idPost);
        $postModel = new Post();
        $postDislikes = $postModel->getPostDislikes($idPost);
        foreach ($postLikes as $like) {
            if ($userLogged['id'] == $like['idUser'] && $post['id'] == $like['idPost']) {
                return "like";
            }
        }
        foreach ($postDislikes as $disLike) {
            if ($userLogged['id'] == $disLike['idUser'] && $post['id'] == $disLike['idPost']) {
                return "dislike";
            }
        }
        return "nothing";
    }

    public function getPostLikes($idPost)
    {
        $sql = "SELECT * FROM postLikes WHERE idPost= :idPost";
        $params = [
            ":idPost" => $idPost
        ];
        $result = $this->db->queryDataBase($sql, $params);
        return $result->fetchAll();
    }
    public function getPostDislikes($idPost)
    {
        $sql = "SELECT * FROM postDislikes WHERE idPost= :idPost";
        $params = [
            ":idPost" => $idPost
        ];
        $result = $this->db->queryDataBase($sql, $params);
        return $result->fetchAll();
    }



    // public function deleteLikes ($idPost, $idUser){
    //     $db = new Database();
    //     $sql = "DELETE FROM  postLikes WHERE idPost = :idPost AND idUser = :idUser;";
    //     $params = [
    //         ":idPost" => $idPost,
    //         ":idUser" => $idUser
    //     ];
    //     $result = $this->db->queryDataBase($sql, $params);
    //     return $result;
    // }

    // public function insertDislikes ($idPost, $idUser){
    //     $db = new Database();
    //     $sql = "INSERT INTO  postDisikes (idPost,idUser) VALUES ( :idPost, :idUser);";
    //     $params = [
    //         ":idPost" => $idPost,
    //         ":idUser" => $idUser
    //     ];
    //     $result = $this->db->queryDataBase($sql, $params);
    //     return $result;
    // }
    // public function deleteDislikes ($idPost, $idUser){
    //     $db = new Database();
    //     $sql = "DELETE FROM  postDislikes WHERE idPost = :idPost AND idUser = :idUser;";
    //     $params = [
    //         ":idPost" => $idPost,
    //         ":idUser" => $idUser
    //     ];
    //     $result = $this->db->queryDataBase($sql, $params);
    //     return $result;
    // }

    // public function comprovarLikeODislike($idPost)
    // {
    //     $userModel = new User();
    //     $userLogged = $userModel->getUserLogged();
    //     $postModel = new Post();
    //     $posts = $postModel->getById($idPost);
    //     foreach ($posts as $post) {
    //         if ($post['id'] == $idPost) {
    //             if (in_array($userLogged['id'], $post['usersLikes'])) {
    //                 return "like";
    //             } elseif (in_array($userLogged['id'], $post['usersDislikes'])) {
    //                 return "dislike";
    //             } else {
    //                 return "nothing";
    //             }
    //             break;
    //         }
    //     }
    // }


}
