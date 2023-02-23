<?php

namespace Models;

class Search extends Database {
    
    public function searchUsers($user)
    {
        $req = "SELECT users.id, username, image_path
                FROM users
                LEFT JOIN user_profile
                ON users.id = user_id
                WHERE username LIKE :user;";
        
        $use = "%". $user . "%";
                
        $params = [
            'user' => $use
        ];
        
        return $this->findAll($req, $params);
    }

}