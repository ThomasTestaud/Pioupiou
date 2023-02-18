<?php

namespace Models;

class Profile extends Database {
    
    public function getProfileInfos($user)
    {
        $req = "SELECT user_profile.description, user_profile.last_login_date, user_profile.image_path,  user_profile.banner_image, users.username, users.email, users.creation_date
                FROM users
                LEFT JOIN user_profile
                ON user_profile.user_id = users.id
                WHERE users.username = :username";
                
        $params = [
            'username' => $user
        ];
        
        return $this->findOne($req, $params);
    }
    
}