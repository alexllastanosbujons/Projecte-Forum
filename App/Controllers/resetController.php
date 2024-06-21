<?php 

    include_once(__DIR__ . "/../Models/Post.php");
    include_once(__DIR__ . "/../Models/User.php");
    include_once(__DIR__ . "/../Models/Comment.php");
    include_once(__DIR__ . "/../Helpers/userHelper.php");
    class resetController extends Controller{
            
            public function run(){


                $sql = 'DROP TABLE IF EXISTS comments,commentLikes,commentDislikes,posts,postLikes,postDislikes,users;';
                
                $db = new Database();
                $db->queryDataBase($sql);
                
              
                unset($_SESSION['userLogged']);
                User::createTable();
                Post::createTable();
                Comment::createTable();
                
                $userModel = new User();
                $salt = bin2hex(random_bytes(16));
                $pepper = 'alex';
                $passwordWithPepperAndSalt = $pepper . 'admin' . $salt;
                $hashedPassword = password_hash($passwordWithPepperAndSalt,PASSWORD_BCRYPT);
                $user = [
                    "username" => 'admin',
                    "firstname" => 'admin',
                    "lastname" => 'admin',
                    "email" => 'admin@gmail.com',
                    "birthdate" => '1999-01-01',
                    "pass" => $hashedPassword,
                    "profile" => 'default.png' ,
                    "token" => generateToken(),
                    "verified" => 1,
                    "admin" => 1,
                    "salt" => $salt
                
                ];
                $userModel->store($user);
                $userModel = new User();
                $salt = bin2hex(random_bytes(16));
                $passwordWithPepperAndSalt = $pepper . 'alexllastanos' . $salt;
                $hashedPassword2 = password_hash($passwordWithPepperAndSalt,PASSWORD_BCRYPT);
                $user = [
                    "username" => 'alexllastanos',
                    "firstname" => 'alexllastanos',
                    "lastname" => 'alexllastanos',
                    "email" => 'alexllastanos@gmail.com',
                    "birthdate" => '1999-01-01',
                    "pass" => $hashedPassword2,
                    "profile" => 'default.png' ,
                    "token" => generateToken(),
                    "verified" => 1,
                    "admin" => 0,
                    "salt" => $salt
                
                ];
                $userModel->store($user);
                $postModel = new Post();
    


                $post = [
                    "idUser" => '1',
                    "title" => 'Projecte Uf 3',
                    "subtitle" => 'PHP',
                    "description" => 'El meu projecte de la UF3 cambiant el de la UF 2 a BBDD',
                    "date" => '2023-11-28 21:13:00',
                    "likes" => 0,
                    "dislikes" => 0,

                ];
                $postModel->store($post);
               $commentModel = new Comment();
               $comment = [
                'idUser' => 1,
                'idPost' => 1,
                'text' => 'En mario es tonto',
                'date' => '2023-11-28 21:13:00',
                'likes' => '0',
                'dislikes' => '0'
               ];
               $commentModel->store($comment);

               header('Location: /user/login');

               die();
            }
        }
        
        ?>