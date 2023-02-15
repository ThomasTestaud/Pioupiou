<?php

namespace Models;

class Comments extends Database {
    
    public function getAllComments()
    {
        $req = "SELECT comments.article_id, comments.id, comments.content, comments.time_stamp, users.username
                FROM comments
                INNER JOIN users
                ON comments.user_id = users.id
                WHERE comments.validate != 0 AND users.validate != 0";
        return $this->findAll($req);
    }
    
    public function writeComment($data)
    {
        $req = "INSERT INTO comments (user_id, article_id, content) 
                VALUES (:user, :article, :content)";
        
        $this->createNew($req, $data);
    }
}