<?php

class Signup extends Controller
{
    public function index() {
        require APP . 'view/_templates/header.php';
        require APP . 'view/signup/index.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function register() {
        $first = $_POST['firstname'];
        $last = $_POST['lastname'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];
        $email = $_POST['email'];
        
        $name = $first . ' ' . $last;
        
        if ($password != $repassword) {
            header('location: ' . URL . 'signup');
        }
        
        $this->model->registerUser($name, $username, $password, $email);
        header('location: ' . URL);
    }
    
    public function checkuser($user) {
        $count = $this->model->checkUser($user);
        echo $count;
    }
}