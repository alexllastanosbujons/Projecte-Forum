<?php

class mainController extends Controller
{

    public function home()
    {
        if (isset($_SESSION['userLogged'])) {
            $postModel = new Post();
            $llista['posts'] = $postModel->getAll();
            $userModel = new User();
            $llista['userLogged'] = $userModel->getUserLogged();
            $llista['users'] = $userModel->getAll();
            $this->render('home', $llista, 'site');
        } else {
            header('Location: /user/login');
        }
    }
    public function error()
    {
        $this->render('404', [], 'site');
    }
}
