<?php
include_once(__DIR__ . "/../Helpers/postHelper.php");
include_once(__DIR__ . "/../Models/Post.php");
class postController extends Controller
{

    public function index()
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
            $llista['comprovacioLikesC'] = "";
            if (isset($_SESSION['updatePost'])) {
                $llista['updatePost'] = $_SESSION['updatePost'];
                unset($_SESSION['updatePost']);
            }

            $this->render('post/index', $llista, 'site');
        } else {
            header('Location: /main/404');
        }
    }

    public function create()
    {
        if (isset($_SESSION['userLogged'])) {
            if (isset($_SESSION['createPost'])) {
                $llista['createPost'] = $_SESSION['createPost'];
                unset($_SESSION['createPost']);
            } else {
                $llista = [];
            }
            $this->render('post/create', $llista, 'site');
        } else {
            header('Location: /main/404');
        }
    }

    public function store()
    {
        if (isset($_SESSION['userLogged'])) {
            $postModel = new Post();
            $llista['posts'] = $postModel->getAll();
            $userModel = new User();
            $llista['users'] = $userModel->getAll();
            $commentModel = new Comment();
            $llista['comments'] = $commentModel->getAll();
            $date = new DateTime('now', new DateTimeZone('Europe/Madrid'));
            $dateFormatted = $date->format('Y-m-d H:i:s');
            $_SESSION['createPost']['title'] = titleEmpty($_POST['title']);
            $_SESSION['createPost']['subtitle'] = subtitleEmpty($_POST['subtitle']);
            $_SESSION['createPost']['description'] = descriptionEmpty($_POST['description']);
            $validador = false;
            foreach ($_SESSION['createPost'] as $error) {
                if ($error != null) {
                    $validador = true;
                }
            }
            if ($validador) {
                header('Location: /post/create/?title=' . $_POST['title'] . '&subtitle=' . $_POST['subtitle'] . '&description=' . $_POST['description']);
            } else {
                $post = array(
                    "idUser" => $_SESSION['userLogged']['id'],
                    "title" => $_POST['title'],
                    "subtitle" => $_POST['subtitle'],
                    "description" => $_POST['description'],
                    "date" => $dateFormatted,
                    "likes" => 0,
                    "dislikes" => 0,
                );
                $postModel = new Post();
                $postModel->store($post);
                $postModel = new Post();
                $llista['posts'] = $postModel->getAll();
                header('Location: /main/home');
            }
        } else {
            header('Location: /main/404');
        }
    }



    public function delete()
    {
        if (isset($_SESSION['userLogged'])) {
            $postModel = new Post();
            
            $postModel->delete($_GET['idPost']);
            header('Location: /main/home');
        } else {
            header('Location: /main/404');
        }
    }

    public function postView()
    {
        if (isset($_SESSION['userLogged'])) {
            header('Location: /post/index/?idPost=' . $_GET['idPost']);
        } else {
            header('Location: /main/404');
        }
    }

    public function update()
    {
        if (isset($_SESSION['userLogged'])) {
            $postModel = new Post();
            $post = $postModel->getById($_GET['idPost']);
            if ($post['title'] != $_POST['title'] && $_POST['title'] != null) {
                $post['title'] = $_POST['title'];
                $_SESSION['updatePost']['titleUpdated'] = "Titol actualitzat correctament";
            }

            if ($post['subtitle'] != $_POST['subtitle'] && $_POST['subtitle'] != null) {
                $post['subtitle'] = $_POST['subtitle'];
                $_SESSION['updatePost']['subtitledUpdated'] = "Subtitol actualitzat correctament";
            }

            if ($post['description'] != $_POST['description'] && $_POST['description'] != null) {
                $post['description'] = $_POST['description'];
                $_SESSION['updatePost']['descriptionUpdated'] = "Descripció actualitzada correctament";
            }
            $postModel = new Post();
            $postModel->store($post);
            header('Location: /post/index/?idPost=' . $post['id']);
        } else {
            header('Location: /main/404');
        }
    }

    public function updateView()
    {
        if (isset($_SESSION['userLogged'])) {
            $postModel = new Post();
            $llista['post'] = $postModel->getById($_GET['idPost']);
            $this->render('post/create', $llista, 'site');
        } else {
            header('Location: /main/404');
        }
    }

    public function likes()
    {
        if (isset($_SESSION['userLogged'])) {
            
            $userModel = new User();
            $userLogged = $userModel->getUserLogged();
            $postModel = new Post();
            $postModel->likes($_GET['idPost'], $userLogged['id']);
            header('Location: /post/index/?idPost=' . $_GET['idPost']);
        } else {
            header('Location: /main/404');
        }
    }

    public function dislikes()
    {
        if (isset($_SESSION['userLogged'])) {
            
            $userModel = new User();
            $userLogged = $userModel->getUserLogged();
            $postModel = new Post();
            $postModel->dislikes($_GET['idPost'], $userLogged['id']);
            header('Location: /post/index/?idPost=' . $_GET['idPost']);
        } else {
            header('Location: /main/404');
        }
    }
}
