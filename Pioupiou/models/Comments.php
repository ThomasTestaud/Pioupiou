<?php

namespace Models;

class Comments extends Database {
    
    public function getAllComments()
    {
        $req = "SELECT comments.article_id, comments.id, comments.content, comments.time_stamp, users.username, user_profile.image_path
                FROM comments
                INNER JOIN users
                ON comments.user_id = users.id
                LEFT JOIN user_profile
                ON user_profile.user_id = comments.user_id
                WHERE comments.validate != 0 AND users.validate != 0 ORDER BY id";
        return $this->findAll($req);
    }
    
    public function writeComment($data)
    {
        $req = "INSERT INTO comments (user_id, article_id, content) 
                VALUES (:user, :article, :content)";
        
        $this->createNew($req, $data);
    }
    
    public function deleteComment($commentId)
    {
        $req = "UPDATE `comments` SET `validate`= 0 WHERE id = :id";
        
        $params = [
            'id' => $commentId    
        ];
        
        $this->createNew($req, $params);
    }
}