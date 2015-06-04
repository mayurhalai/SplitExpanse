<?php

class Admin extends Controller {
    
    public $errormessage = null;
    private $username = null;
    private $password = null;
    
    public function index() {
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
                if ($this->model->validateAdmin($this->username, $this->password)) {
                    @session_start();
                    $_SESSION["logged"] = TRUE;
                    $_SESSION["name"] = "Admin";
                    header('location: ' . URL . 'admin/home');
                }
                else
                {
                    $this->errormessage = 'Incorrect Username or Password';
                }
            }
        }
        require APP . 'view/_templates/header.php';
        require APP . 'view/admin/index.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function home() {
        $this->checkLogin();
        $users = $this->model->fetchUser();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/adminheader.php';
        require APP . 'view/admin/home.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function receive() {
        $this->checkLogin();
        $user = $_POST['username'];
        $balance = $_POST['balance'];
        $amount = $_POST['amount'];
        $new_balance = $balance + $amount;
        $this->model->updateBalance($user, $new_balance, 'receive', -$amount);
        
        header('location: ' . URL . 'admin/home');
    }
    
    public function send() {
        $this->checkLogin();
        $user = $_POST['username'];
        $balance = $_POST['balance'];
        $amount = $_POST['amount'];
        $new_balance = $balance - $amount;
        $this->model->updateBalance($user, $new_balance, 'send', $amount);
        
        header('location: ' . URL . 'admin/home');
    }
    
    public function dat() {
        $this->checkLogin();
        $transactions = $this->model->fetchAdminTransaction();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/adminheader.php';
        require APP . 'view/admin/dat.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function delete($id) {
        $this->checkLogin();
        $this->model->deleteTransaction($id);
        
        header('location: ' . URL . 'admin/dat');
    }

    public function sdt() {
        $this->checkLogin();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/adminheader.php';
        require APP . 'view/admin/sdt.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function fetchallbills() {
        $this->checkLogin();
        $bills = $this->model->fetchAllBills();
        $bills = $this->sqltojson($bills, 'bills');
        echo $bills;
    }
    
    public function safedelete($id) {
        $this->checkLogin();
        $this->model->safeDeleteTransaction($id);
        
        header('location: ' . URL . 'admin/sdt');
    }
    
    public function cms() {
        $this->checkLogin();
        $users = $this->model->fetchUser();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/adminheader.php';
        require APP . 'view/admin/cms.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function updatecommon($username, $common) {
        $this->checkLogin();
        $this->model->updateCommon($username, $common);
        
        header('location: ' . URL . 'admin/cms');
    }
    
    public function fpr() {
        $this->checkLogin();
        $users = $this->model->fetchUser();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/adminheader.php';
        require APP . 'view/admin/fpr.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function changepassword() {
        $this->checkLogin();
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $this->model->updatePassword($username, $password);
        
        header('location: ' . URL . 'admin/fpr');
    }
    
    public function deletefpr($username) {
        $this->checkLogin();
        $this->model->unsetFpr($username);
        
        header('location: ' . URL . 'admin/fpr');
    }

    public function uar() {
        $this->checkLogin();
        $users = $this->model->fetchUnapproved();
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/adminheader.php';
        require APP . 'view/admin/uar.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function approveuser($username) {
        $this->checkLogin();
        $this->model->approveUser($username);
        
        header('location: ' . URL . 'admin/uar');
    }
    
    public function deleteuser($username) {
        $this->checkLogin();
        $this->model->deleteUser($username);
        
        header('location: ' . URL . 'admin/uar');
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('location: ' . URL . 'admin');
        exit();
    }
    
    private function checkLogin() {
        @session_start();
        $logged = $_SESSION["logged"];
        $name = $_SESSION["name"];
        if ($logged == FALSE || $name != 'Admin') {
            session_unset();
            session_destroy();
            header('location: ' . URL . 'admin');
            exit();
        }
    }
    
    private function sqltojson($data, $name) {
        $out = '{"' . $name . '": [';
        foreach ($data as $obj) {
            $out = $out . '{';
            foreach ($obj as $key => $value) {
                $out = $out . '"' . $key . '" : "' . $value . '",';
            }
            $out = rtrim($out, ',');
            $out = $out . '},';
        }
        $out = rtrim($out, ',');
        $out = $out . ']}';
        return $out;    
    }
}