<?php

class Dashboard extends Controller
{
    private $user = null;
    public function __construct() {
        parent::__construct();
        @session_start();
        $logged = $_SESSION["logged"];
        if ($logged == FALSE) {
            session_unset();
            session_destroy();
            header('location: ' . URL);
            exit();
        } else {
            $this->user = $_SESSION["user"];
        }
    }
    
    public function index() {
        $users = $this->model->fetchUser();
        require APP . 'view/_templates/header.php';
        require APP . 'view/dashboard/index.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function allbills() {
        require APP . 'view/_templates/header.php';
        require APP . 'view/dashboard/allbills.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function allbalances() {
        require APP . 'view/_templates/header.php';
        require APP . 'view/dashboard/allbalances.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function add() {
        $id = null;
        if (isset($_POST['id']) && $_POST['id'] != '') {
            $id = $_POST['id'];
        }
        $amount = $_POST['amount'];
        $description = $_POST['description'];
        $date = $_POST['date'];
        $members = $_POST['members'];
        if ($id == NULL) {
            $this->model->addBill($date, $description, $amount, $this->user, $members);
        } else {
            $this->model->editBill($id, $date, $description, $amount, $members);
        }
        
        header('location: ' . URL . 'dashboard');
    }
    
    public function delete($id) {
        $this->model->deleteBill($id);
        
        header('location: ' . URL . 'dashboard');
    }
    
    public function edituser() {
        $userinfo = $this->model->retriveUser($this->user);
        $name = explode(" ", $userinfo->name);
        $email = $userinfo->email;
        require APP . 'view/_templates/header.php';
        require APP . 'view/dashboard/edituser.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function checkpass($pass) {
        $count = $this->model->checkPass(md5($pass));
        echo $count;
    }
    
    public function edituserdetail() {
        $first = $_POST['firstname'];
        $last = $_POST['lastname'];
        $old_pass = $_POST['oldpass'];
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];
        $email = $_POST['email'];
        
        $name = $first . ' ' . $last;
        
        if ($password != $repassword) {
            header('location: ' . URL . 'error');
        }
        
        $this->model->editUser($name, $username, $password, $email);
        header('location: ' . URL . 'dashboard');
    }

    public function logout() {
        $this->model->unsetUser($this->user);
        $this->c_model->unsetUser();
        session_unset();
        session_destroy();
        header('location: ' . URL);
        exit();
    }
    public function fetchbills() {
        $bills = $this->model->fetchBillsByUsers($this->user);
        $bills = $this->sqltojson($bills, 'bills');
        echo $bills;
    }
    
    public function fetchallbills() {
        $bills = $this->model->fetchAllBills();
        $bills = $this->sqltojson($bills, 'bills');
        echo $bills;
    }
    
    public function fetchsplit($id) {
        $names = $this->model->fetchSplit($id);
        $names = $this->sqltojson($names, 'names');
        echo $names;
    }
    
    public function fetchbalance() {
        $balance = $this->model->fetchBalance($this->user);
        echo $balance->balance;
    }
    
    public function fetchallbalance() {
        $users = $this->model->fetchUser();
        $users = $this->sqltojson($users, 'users');
        echo $users;
    }
    
    public function fetchbillsbyuser($username) {
        $bills = $this->model->fetchBillsByUsers($username);
        $bills = $this->sqltojson($bills, 'bills');
        echo $bills;
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