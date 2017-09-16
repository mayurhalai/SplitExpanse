<?php

class Mobilehome extends Controller {
    public function index() {
        if (isset($_POST['username']) && $_POST['username'] != "" && isset($_POST['password']) && $_POST['password'] != "")
        {
            $username = $_POST['username'];
            $password = md5($_POST['password']);
            if ($this->model->validateUser($username, $password)) {
                $token = md5($username);
                $this->model->setMobileUser($token, $username);
                echo $token;
            }
            else
            {
                echo 'fail';
            }
        }
        else {
            echo 'fail';
        }
    }
}