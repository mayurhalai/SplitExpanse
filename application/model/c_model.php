<?php

class C_Model
{
    public function retriveUser() {
        if (isset($_COOKIE["user"])) {
            return $_COOKIE["user"];
        } else {
            return FALSE;
        }
    }
    public function setUser($username) {
        setcookie("user", md5($username), time() + (3600 * 24), "/");
    }
    
    public function unsetUser() {
        setcookie("user", NULL, time() - 3600, "/");
    }
}