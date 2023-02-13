<?php

class User {
    
    private $username;
    private $password;
    private $email;
    private $validate;
    
    public function connection(): void
    {
        //controller du formulaire de connexion
        if(isset($_POST['login-username']) && isset($_POST['login-password'])){
            $connect->login();
        }
    }
    
    public function register(): void
    {
        //controller du formulaire de création de compte
        if(isset($_POST['register-username']) && isset($_POST['register-password']) && isset($_POST['register-email'])){
            $connect->register();
        }
    }
    
}

