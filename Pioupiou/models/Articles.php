<?php

namespace Models;

class Articles extends Database {
    
    public function getAllArticles()
    {
        $req = "SELECT * FROM articles";
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