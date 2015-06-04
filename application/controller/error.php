<?php

class Error extends Controller {
    
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        require APP . 'view/_templates/header.php';
        require APP . 'view/error/index.php';
        require APP . 'view/_templates/footer.php';
    }
    
    public function adminthing() {
        require APP . 'view/_templates/header.php';
        require APP . 'view/error/adminthing.php';
        require APP . 'view/_templates/footer.php';
    }
}