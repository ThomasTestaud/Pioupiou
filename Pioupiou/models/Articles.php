<?php

namespace Models;

class Articles extends Database {
    
    public function getAllArticles()
    {
        $req = "SELECT articles.title, articles.content, articles.time_stamp, users.username
                FROM articles
                INNER JOIN users
                ON articles.user_id = users.id
                WHERE articles.validate != 0 AND users.validate != 0 ORDER BY articles.id DESC;";
        return $this->findAll($req);
    }
    
    public function writeArticle($data)
    {
        $req = "INSERT INTO articles (user_id, title, content) 
                VALUES (:user, :title, :content)";
        
        $this->createNew($req, $data);
    }
}