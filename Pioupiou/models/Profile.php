<?php

namespace Models;

class Profile extends Database {
    
    public function getProfileInfos($user)
    {
        $req = "SELECT users.id, users.username, users.email, users.creation_date, users.validate, user_profile.description, user_profile.image_path, user_profile.banner_image, articles.id AS article_id, articles.content AS article_content, articles.time_stamp AS article_time_stamp, articles.image_path AS article_image, articles.audio_file AS article_audio, articles.validate AS article_validate, comments.id AS comment_id, comments.user_id AS comment_user_id, comments.article_id AS comment_article_id, comments.content AS comment_content, comments.time_stamp AS comment_time_stamp, comments.validate AS comment_validate, userB.username AS comment_author, userB_profile.image_path AS comment_author_photo
                FROM `users`
                LEFT JOIN user_profile
                ON users.id = user_profile.user_id
                LEFT JOIN articles
                ON users.id = articles.user_id
                LEFT JOIN comments
                ON articles.id = comments.article_id
                LEFT JOIN users userB
                ON comments.user_id = userB.id
                LEFT JOIN user_profile userB_profile
                ON comments.user_id = userB_profile.id
                WHERE users.username = :username
                AND users.validate != 0
                ORDER BY articles.id DESC;";
                
        $params = [
            'username' => $user
        ];
        
        return $this->findAll($req, $params);
    }
    
}