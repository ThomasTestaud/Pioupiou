<?php

namespace Models;

class Articles extends Database {
    
    
    
    public function getAllArticles()
    {
        $req = "SELECT articles.id, articles.title, articles.content, articles.time_stamp, users.username, user_profile.image_path
                FROM articles
                INNER JOIN users
                ON articles.user_id = users.id
                LEFT JOIN user_profile
                ON users.id = user_profile.user_id
                WHERE articles.validate != 0 AND users.validate != 0 ORDER BY articles.id DESC;";
        return $this->findAll($req);
    }
    
    public function getAllArticlesFromUser($user)
    {
        $req = "SELECT articles.id, articles.title, articles.content, articles.time_stamp, users.username
                FROM articles
                INNER JOIN users
                ON articles.user_id = users.id
                WHERE articles.validate != 0 AND users.validate != 0 AND users.username = :user ORDER BY articles.id DESC;";
                
        $params = [
            'user' => $user
        ];
        
        return $this->findAll($req, $params);
    }
    
    public function writeArticle($data)
    {
        $req = "INSERT INTO articles (user_id, title, content) 
                VALUES (:user, :title, :content)";
        
        $this->createNew($req, $data);
    }
        
    public function deleteArticle($articleId)
    {
        $req = "UPDATE `articles` SET `validate`= 0 WHERE id = :id";
        
        $params = [
            'id' => $articleId    
        ];
        
        $this->createNew($req, $params);
    }
}