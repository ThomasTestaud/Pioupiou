<?php

namespace Models;

class Articles extends Database {
    
    public function getAllArticles()
    {
        $req = "SELECT articles.title, articles.content, articles.time_stamp, users.username
                FROM articles
                JOIN users
                ON articles.user_id = users.id
                WHERE articles.validate != 0 AND users.validate != 0;";
        return $this->findAll($req);
    }
    
    public function getOneArticle($art_id)
    {
        $req = "SELECT * FROM articles WHERE art_id = :id";
        $params = [
            'id' => $_GET['id']
        ];
        return $this->findAll($req, $params);
    }
}