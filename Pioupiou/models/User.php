<?php

class Connexion {
    
    public function register(): void
    {
        require './config/ddb.php';
        
        $query = $db->prepare("INSERT INTO 
        `users`(`username`, `password`, `email`) 
        VALUES (:user, :pass, :mail)");
        
        $query->execute([
            'user' => $_POST['register-username'],
            'pass' => password_hash($_POST['register-password'], PASSWORD_DEFAULT),
            'mail' => $_POST['register-email']
        ]);
        
    }
    
    public function login(): void
    {
        require './config/ddb.php';
        
        $query = $db->prepare('SELECT username, password FROM `users` WHERE username = :user OR email = :mail');
        
        $query->execute([
            'user' => $_POST['login-username'],
            'mail' => $_POST['login-username']
        ]);
        $userdata = $query->fetch();
        
        
        if(isset($userdata['username']) && isset($userdata['password'])){
            if($_POST['login-username'] === $userdata['username'] && password_verify($_POST['login-password'], $userdata['password']) === true){
                $_SESSION['connected'] = true;
            }
        }else{
            $_SESSION['connected'] = false;
        }
    }
    
}