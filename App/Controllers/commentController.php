<?php
include_once(__DIR__ . "/../Models/Comment.php");
include_once(__DIR__ . "/../Core/Mailer.php");
include_once(__DIR__ . "/../Helpers/commentHelper.php");



class commentController extends Controller
{

    public function home()
    {
        if (isset($_SESSION['userLogged'])) {
            $postModel = new Post();
            $llista['post'] = $postModel->getById($_GET['idPost']);
            $commentModel = new Comment();
            $llista['comments'] = $commentModel->getAll();
            $userModel = new User();
            $llista['users'] = $userModel->getAll();
            $llista['userLogged'] = $userModel->getUserLogged();
            $llista['comprovacioLikesP'] = $postModel->comprovarLikeODislike($_GET['idPost']);
            if (isset($_SESSION['createComment'])) {
                $llista['createComment'] = $_SESSION['createComment'];
                unset($_SESSION['createComment']);
            }
            $this->render('post/index', $llista, 'site');
        } else {
            header('Location: /main/404');
        }
    }


    public function store()
    {
        if (isset($_SESSION['userLogged'])) {
            date_default_timezone_set('Europe/Madrid');
            $date = new DateTime('now', new DateTimeZone('Europe/Madrid'));
            $dateFormatted = $date->format('Y-m-d H:i:s');
            $postModel = new Post();
            $llista['post'] = $postModel->getById($_POST['idPost']);
            $userModel = new User();
            $llista['users'] = $userModel->getAll();
            $commentModel = new Comment();
            $llista['comments'] = $commentModel->getAll();
            if (!empty($llista['comments'])) {
                $ultimComment = end($llista['comments']);
                $seguentId = $ultimComment['id'] + 1;
            } else {
                $seguentId = 1;
            }
            
            $userLogged = $userModel->getUserLogged();
            $_SESSION['createComment'] = textCommentEmpty($_POST['text']);
            if ($_SESSION['createComment'] == null) {
                $comment = array(
                    "idUser" => $userLogged['id'],
                    "idPost" => $_POST['idPost'],
                    "text" => $_POST['text'],
                    "date" => $dateFormatted,
                    "likes" => 0,
                    "dislikes" => 0,
                );
                $commentModel = new Comment();
                $commentModel->store($comment);
                $llista['userLogged'] = $userLogged;
                $commentModel = new Comment();
                $llista['comments'] = $commentModel->getAll();
                $userModel = new User();
                $user = $userModel->getById($llista['post']['idUser']);
                $mailer = new Mailer();
                $mailer->mailServerSetup();
                $mailer->addRec(array($user['email']));
                $mailer->addCommentContent($llista['post'], $userLogged);
                $mailer->send();
            }
            header('Location: /comment/home/?idPost=' . $llista['post']['id']);
        } else {
            header('Location: /main/404');
        }
    }



    public function delete()
    {
        if (isset($_SESSION['userLogged'])) {
            $commentModel = new Comment();
            $comment = $commentModel->getById($_GET['commentId']);
            $commentModel = new Comment();
            $commentModel->delete($_GET['commentId']);
            header('Location: /comment/home/?idPost=' . $_GET['postId']);
        } else {
            header('Location: /main/404');
        }
    }

    public function likes()
    {
        if (isset($_SESSION['userLogged'])) {
            $userModel = new User();
            $userLogged = $userModel->getUserLogged();
            $commentModel = new Comment();
            $commentModel->likes($_GET['idComment'], $userLogged['id'], $_GET['idPost']);
            header('Location: /comment/home/?idPost=' . $_GET['idPost'] . '&idComment=' . $_GET['idComment']);
        } else {
            header('Location: /main/404');
        }
    }
    public function dislikes()
    {
        if (isset($_SESSION['userLogged'])) {
            $userModel = new User();
            $userLogged = $userModel->getUserLogged();
            $commentModel = new Comment();
            $commentModel->dislikes($_GET['idComment'], $userLogged['id'], $_GET['idPost']);
            header('Location: /comment/home/?idPost=' . $_GET['idPost'] . '&idComment=' . $_GET['idComment']);
        } else {
            header('Location: /main/404');
        }
    }
}
