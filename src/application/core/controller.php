<?php

class Controller
{
    public $db = null;
    public $model = null;
    public $c_model = null;
    
    function __construct() {
        $this->openDatabaseConnection();
        $this->loadModel();
        $this->loadCookieModel();
    }
    
    private function openDatabaseConnection()
    {
        // $option = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        // $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $option);
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=3306';
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        ); 
        
        $this->db = new PDO($dsn, DB_USER, DB_PASS, $options);
    }
    private function loadModel()
    {
        require APP . '/model/model.php';
        $this->model = new Model($this->db);
    }
    private function loadCookieModel()
    {
        require APP . '/model/c_model.php';
        $this->c_model = new C_Model();
    }
}