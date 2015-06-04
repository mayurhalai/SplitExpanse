<?php

class Home extends Controller
{
    public $errormessage = null;
    private $username = null;
    private $password = null;


    public function __construct() {
        parent::__construct();
    }

    public function index()
    {
        $this->checklogin();
        if (isset($_POST['username']) && $_POST['username'] != "")
        {
            if (!isset($_POST['password']) || $_POST['password'] == "")
            {
                $this->errormessage = 'Type valid password';
            }
            else
            {
                $this->username = $_POST['username'];
                $this->password = md5($_POST['password']);
                if ($this->model->validateUser($this->username, $this->password)) {
                    $this->model->setUser($this->username);
                    $this->c_model->setUser($this->username);
                    @session_start();
                    $_SESSION["logged"] = TRUE;
                    $_SESSION["user"] = $this->username;
                    $name = $this->model->fetchName($this->username);
                    $_SESSION["name"] = $name->name;
                    header('location: ' . URL . 'dashboard');
                }
                else
                {
                    $this->errormessage = 'Incorrect Username or Password';
                }
            }
        }
        require APP . 'view/_templates/header.php';
        require APP . 'view/home/index.php';
        require APP . 'view/_templates/footer.php';
    }
    
    private function checklogin() {
        if ($this->c_model->retriveUser()) {
            $user = $this->model->retriveUserByCookie($this->c_model->retriveUser());
            if (!empty($user)) {
                $this->username = $user->username;
                @session_start();
                $_SESSION["logged"] = TRUE;
                $_SESSION["user"] = $this->username;
                $name = $this->model->fetchName($this->username);
                $_SESSION["name"] = $name->name;
                header('location: ' . URL . 'dashboard');
            }
        }
    }
    
    public function forgotpassword() {
        $username = $_POST['username'];
        $this->model->setForgotUser($username);
        header('location: ' . URL . 'error/adminthing');
    }
}