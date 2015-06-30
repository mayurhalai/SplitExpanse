<?php

class Mobile extends Controller {
    private $user = null;
    function __construct() {
        parent::__construct();
        if (!empty($_POST['token'])) {
            $token = $_POST['token'];
            $user = $this->model->retriveMobileUser($token);
            if (empty($user)) {
                echo 'You are not Logged in.';
                exit();
            } else {
                $this->user = $user->username;
            }
        } else {
            echo 'You are not Logged in.';
            exit();
        }
    }
    
    public function index() {
        echo 'success';
    }
    
    public function add() {
        $image = NULL;
        $data = $_POST['data'];
        $data = json_decode($data);
        $amount = $data->amount;
        $description = $data->description;
        $date = $data->date;
        $members = $data->members;
        if (!empty($_FILES['image'])) {
            $image = $_FILES['image'];
        }
        if ($image == NULL) {
            $this->model->addBill($date, $description, $amount, $this->user, $members);
        } else {
            $this->model->addBillWithImage($date, $description, $amount, $this->user, $members, $image);
        }
    }
    
    public function edit() {
        $image = NULL;
        $data = $_POST['data'];
        $data = json_decode($data);
        $id = $data->id;
        $amount = $data->amount;
        $description = $data->description;
        $date = $data->date;
        $members = $data->members;
        if (!empty($_FILES['image'])) {
            $image = $_FILES['image'];
        }
        if ($image == NULL) {
                $this->model->editBill($id, $date, $description, $amount, $members);
            } else {
                $this->model->editBillWithImage($id, $date, $description, $amount, $members, $image);
            }
    }
    
    public function delete($id) {
        print 'done';
        $this->model->deleteBill($id);
    }

    public function fetchuser() {
        $users = $this->model->fetchUser();
        echo json_encode($users, JSON_UNESCAPED_SLASHES);
    }
    
    public function fetchbills() {
        $balance = $this->model->fetchBalance($this->user);
        $bills = $this->model->fetchBillsByUsers($this->user);
        echo '{"bills":' . json_encode($bills, JSON_UNESCAPED_SLASHES) . ', "balance":"' . $balance->balance . '"}';
    }
    
    public function fetchsplit($id) {
        $names = $this->model->fetchSplit($id);
        echo json_encode($names, JSON_UNESCAPED_SLASHES);
    }
    
    public function fetchallbalance() {
        $users = $this->model->fetchUser();
        echo json_encode($users, JSON_UNESCAPED_SLASHES);
    }
    
    public function fetchbillsbyuser($username) {
        $bills = $this->model->fetchBillsByUsers($username);
        echo json_encode($bills, JSON_UNESCAPED_SLASHES);
    }

    public function logout() {
        $this->model->unsetMobileUser($this->user);
    }
}