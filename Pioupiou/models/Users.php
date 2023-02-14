<?php

namespace Models;

class Users extends Database {
    
    public function checkIfUserExist($data)
    {
        $req = "SELECT `username`, `email` FROM `users` WHERE username = :user OR email = :mail";
        $params = [
            'user' => $data['register-username'],
            'mail' => $data['register-email']
        ];
        
        return $this->findOne($req, $params);
    }
    
    public function createAccount($data): void
    {
        $req = "INSERT INTO users (username, password, email, validate) 
                VALUES (:user, :password, :email, '1');
                INSERT INTO user_profile (user_id) 
                VALUES (LAST_INSERT_ID());";
        $params = [
            'user' => $data['register-username'],
            'password' => $data['register-password'],
            'email' => $data['register-email']
        ];
        $this->createNew($req, $params);
    }
    
    public function login($data)
    {
        $req = "SELECT `id`, `username`, `email`, `password`, `creation_date`, `validate` FROM `users` WHERE username = :id OR email = :id";
        $params = [
            'id' => $data['login-id']
        ];
        
        return $this->findOne($req, $params);
    }
    
    public function isConnected(): void 
    {
        if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true){
            header('Location: index.php?route=welcome&action=login');
            exit;
        }
    }
    
    public function isNotConnected(): void 
    {
        if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true){
        }else{
            header('Location: index.php?route=dashboard');
            exit;
        }
    }
}