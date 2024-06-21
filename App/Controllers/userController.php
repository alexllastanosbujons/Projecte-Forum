<?php
include_once(__DIR__ . "/../Helpers/userHelper.php");
include_once(__DIR__ . "/../Models/User.php");
include_once(__DIR__ . "/../Models/Post.php");
include_once(__DIR__ . "/../Core/Store.php");
include_once(__DIR__ . "/../Core/Mailer.php");

class userController extends Controller
{


    public function index()
    {
        if (isset($_SESSION['userLogged']) && $_SESSION['userLogged']['admin'] == true) {
            $userModel = new User();
            $llista = $userModel->getAll();
            $this->render('user/index', $llista, 'site');
        } else {
            header('Location: /main/404');
        }
    }

    public function create()
    {
        $userModel = new User();
        if (isset($_SESSION['userLogged'])) {
            $userLogged = $userModel->getUserLogged();
            $llista['userLogged'] = $userLogged;
            if (isset($_SESSION['updateUser'])) {
                $llista['updateUser'] = $_SESSION['updateUser'];
            } else if (isset($_SESSION['updateUserError'])) {
                $llista['updateUserError'] = $_SESSION['updateUserError'];
            }
        } else {
            if (isset($_SESSION['signUp'])) {
                $llista['signUp'] = $_SESSION['signUp'];
                unset($_SESSION['signUp']);
            } else {
                $llista = [];
            }
        }


        $this->render('user/create', $llista, 'site');
    }
    public function login()
    {
        if (isset($_SESSION['login'])) {
            $llista = $_SESSION['login'];
            unset($_SESSION['login']);
        } else {
            $llista = [];
        }
        $this->render('user/login', $llista, 'site');
    }


    public function store()
    {
        $userModel = new User();
        $users = $userModel->getAll();
        $_SESSION['signUp']['comparePassword'] = comparePasswords($_POST['password'], $_POST['confirm_password']);
        $_SESSION['signUp']['passwordLength'] = passwordLength($_POST['password']);
        $_SESSION['signUp']['compareUsername'] = compareUsername($_POST['username'], $users);
        $_SESSION['signUp']['compareEmail'] = compareEmail($_POST['email'], $users);
        $_SESSION['signUp']['passwordEmpty'] = passwordEmpty($_POST['password']);
        $_SESSION['signUp']['password2Empty'] = password2Empty($_POST['confirm_password']);
        $_SESSION['signUp']['firsNameEmpty'] = firstNameEmpty($_POST['first_name']);
        $_SESSION['signUp']['lastNameEmpty'] = lastNameEmpty($_POST['last_name']);
        $_SESSION['signUp']['emailEmpty'] = emailEmpty($_POST['email']);
        $_SESSION['signUp']['imgEmpty'] = imgEmpty($_FILES['profile_image']);
        $_SESSION['signUp']['dateEmpty'] = imgEmpty($_POST['birthdate']);
        $_SESSION['signUp']['emailValidate'] = emailValidation($_POST['email']);
        $validador = false;
        $salt = bin2hex(random_bytes(16));
        $pepper = "alex";
        $passwordWithPepperAndSalt = $pepper . $_POST['password'] . $salt;
        $hashedPassword = password_hash($passwordWithPepperAndSalt,PASSWORD_BCRYPT);

        foreach ($_SESSION['signUp'] as $error) {
            if ($error != null) {
                $validador = true;
            }
        }
        if ($validador) {
            header('Location: /user/create/?username=' . $_POST['username'] . '&first_name=' . $_POST['first_name'] . '&last_name=' . $_POST['last_name'] . '&email=' . $_POST['email'] . '&birthdate=' . $_POST['birthdate']);
        } else {
            $user = array(
                "username" => $_POST['username'],
                "firstname" => $_POST['first_name'],
                "lastname" => $_POST['last_name'],
                "email" => $_POST['email'],
                "birthdate" => $_POST['birthdate'],
                "pass" => $hashedPassword,
                "profile" => null,
                "token" => generateToken(),
                "verified" => 0,
                "admin" => 0,
                "salt" => $salt
            );
            $file = $_FILES['profile_image']['name'];
            if ($file == null || empty($filename)) {
                $file = 'default.png';
                $user['profile']= $file;
            } else {
                
                $nameFileArray = explode('.', $file);
                $user['profile'] = $user['username'] . "." . $nameFileArray[1];
            }
            $url_temp = $_FILES['profile_image']['tmp_name'];
            $url_dest = "img/profiles/";
            Store::file($url_temp, $url_dest, $user['profile']);
            $mailer = new Mailer();
            $mailer->mailServerSetup();
            $mailer->addRec(array($user['email']));
            $mailer->addVerifyContent($user);
            $mailer->send();
            $userModel = new User();
            $userModel->store($user);
            header('Location: /user/login');
        }
    }



    public function verify()
    {
        $userModel = new User();
        $user = $userModel->getByUsername($_GET['idUser']);
        if ($user['token'] == $_GET['token']) {
            $user['verified'] = 1;
        }
        $userModel = new User();
        $userModel->store($user);
        header('Location: /user/login');
    }

    public function loginCheck()
    {


        if (!empty($_POST['email']) && !empty($_POST['pass'])) {
        $email = $_POST['email'];
        $pass =  $_POST['pass'];
        $pepper = "alex";
            $db = new Database();
            $sql = "SELECT * FROM users WHERE email = :email";
            $params = [
                ":email" => $email
            ];

            $result = $db->queryDataBase($sql,$params);

            $userDB = $result->fetch();
            if ($userDB != null) {
                $saltDB = $userDB['salt'];
                $passDB = $userDB['pass'];
                $passwordToCheck = $pepper . $pass . $saltDB;
                if(password_verify($passwordToCheck,$passDB)){
                    $_SESSION['userLogged'] =  $userDB;
                    $_SESSION['login'] = null;
                    header('Location: /main/home');
                    die();
                }else{
                    if (!$userDB['verified']) {
                        $_SESSION['login']['validate'] = "Has de validar el correu electronic per poder entrar a la web";
                    }else{
                        $_SESSION['login']['credentials'] = "Credencials Incorrectes";
                    }
                }
            }

            
        }else if (empty($_POST['email']) && empty($_POST['pass'])) {
            $_SESSION['login']['labelsEmpty'] = "Camps Buits!!";
        }elseif(empty($_POST['email'])){
            $_SESSION['login']['emailEmpty'] = "Correu Buit!!";
        }elseif(empty($_POST['pass'])){
            $_SESSION['login']['passEmpty'] = "Contrasenya buida!!";
        }
            header("Location: /user/login/?email=" . $_POST['email']);

    }
    public function signOut()
    {
        if (isset($_SESSION['userLogged'])) {
            unset($_SESSION['userLogged']);
            $this->render('/user/login', [], 'site');
        } else {
            header('Location: /main/404');
        }
    }

    public function userDelete()
    {
        if (isset($_SESSION['userLogged'])) {
            $userModel = new User();
            $user = $userModel->getById($_GET['user']);
            // $postModel = new Post();
            // $posts = $postModel->getAll();
            // $commentModel = new Comment();
            // $comments = $commentModel->getAll();
            // foreach ($posts as $post) {
            //     if ($post['idUser'] == $_GET['user']) {
            //         foreach ($comments as $comment) {
            //             if ($comment['idPost'] == $post['id']) {
            //                 $commentModel->delete($comment['id']);
            //             }
            //         }
            //         $postModel = new Post();
            //         $postModel->delete($post['id']);
            //     }
            // }
            // foreach ($comments as $comment) {
            //     if ($comment['idUser'] == $_GET['user']) {
            //         $commentModel = new Comment();
            //         $commentModel->delete($comment['id']);
            //     }
            // }
            $postModel = new Post();
            $posts = $postModel->getAll();
            
            foreach ($posts as $post) {
                $postModel = new Post();
                $postLikes = $postModel->getPostLikes($post['id']);
                
                foreach ($postLikes as $like) {
                    if ($like['idUser'] === $user['id']) {
                        $postModel = new Post();
                        $post['likes']--;
                        $postModel->store($post);
                    }
                }
                $postModel = new Post();
                $postDisLikes = $postModel->getPostDislikes($post['id']);
                foreach ($postDisLikes as $dislike) {
                    if ($dislike['idUser'] === $user['id']) {
                        $postModel = new Post();
                        $post['dislikes']--;
                        $postModel->store($post);
                    }
                }
                
            }
            $commentModel = new Comment();
            $comments = $commentModel->getAll();
            
            foreach ($comments as $comment) {
                $commentModel = new Comment();
                $commentLikes = $commentModel->getCommentLikes($comment['id']);
                foreach ($commentLikes as $like) {
                    if ($like['idUser'] === $user['id']) {
                        $commentModel = new Comment();
                        $comment['likes']--;
                        $commentModel->store($comment);
                    }
                }
                $commentModel = new Comment();
                $commentDislikes = $commentModel->getCommentDislikes($comment['id']);
                foreach ($commentDislikes as $dislike) {
                    if ($dislike['idUser'] === $user['id']) {
                        $commentModel = new Comment();
                        $comment['dislikes']--;
                        $commentModel->store($comment);
                    }
                }
                
            }
            $userModel = new User();
            $userModel->delete($_GET['user']);
            $url_dest = "img/profiles/";
            Store::deleteFile($url_dest, $user['profile']);
            header('Location: /user/index');
        } else {
            header('Location: /main/404');
        }
    }
    public function cambiarEstatAdmin()
    {
        if (isset($_SESSION['userLogged'])) {
            $userModel = new User();
            $user = $userModel->getById($_GET['user']);
            if ($user['admin']) {
                $user['admin'] = 0;
            } else {
                $user['admin'] = 1;
            }
            $userModel = new User();
            $userModel->store($user);
            $userModel = new User();
            $users = $userModel->getAll();
            header('Location: /user/index');
        } else {
            header('Location: /main/404');
        }
    }

    public function update()
    {
        if (isset($_SESSION['userLogged'])) {
            $userModel = new User();
            $userLogged = $userModel->getUserLogged();
            $userModel = new User();
            $users = $userModel->getAll();
            $userModel = new User();
            $user = $userModel->getById($userLogged['id']);
            if ($user['username'] != $_POST['username']) {
                $_SESSION['updateUserError']['usernameExists'] = compareUsername($_POST['username'], $users);
                if ($_SESSION['updateUserError']['usernameExists'] == null) {
                    $user['username'] = $_POST['username'];
                    $_SESSION['updateUser']['usernameUpdated'] = "Nom d'usuari actualitzat correctament";
                    unset($_SESSION['updateUserError']['usernameExists']);
                }
            }
            $_SESSION['updateUserError']['passwordSame'] = comparePasswords($_POST['password'], $_POST['confirm_password']);

            if ($_POST['password'] == $_POST['confirm_password'] && $_POST['password'] != null) {
                if ($_POST['password'] != $user['pass']) {
                    $_SESSION['updateUserError']['passwordLength'] = passwordLength($_POST['password']);
                    if ($_SESSION['updateUserError']['passwordLength'] == null && $_SESSION['updateUserError']['passwordLength'] == null) {
                        $user['pass'] = $_POST['password'];
                        $_SESSION['updateUser']['passwordUpdated'] = "Contrasenya d'usuari actualitzada correctament";
                        unset($_SESSION['updateUserError']['usernameExists']);
                    }
                }
            }
            $userModel = new User();
            $userModel->store($user);
            $_SESSION['userLogged'] = $user;
            header('Location: /user/create');
        } else {
            header('Location: /main/404');
        }
    }

    public function userProfile()
    {
        if (isset($_SESSION['userLogged'])) {
            $userModel = new User();
            $llista['userLogged'] = $userModel->getUserLogged();
            $llista['user'] = $userModel->getById($_GET['idUser']);
            $postModel = new Post();
            $llista['posts'] = $postModel->getAll();
            $this->render('/user/profile', $llista, 'site');
        } else {
            header('Location: /main/404');
        }
    }
}
