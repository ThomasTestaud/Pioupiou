<?php

namespace Models;

class Articles extends Database {
    
    
    
    public function getAllArticles()
    {
        $req = "SELECT articles.id, articles.title, articles.content, articles.time_stamp, articles.audio_file, articles.image_path AS article_image, articles.validate AS article_validate, a.username, user_profile.image_path, comments.id AS comment_id, comments.user_id AS comment_user_id, comments.article_id AS comment_article_id, comments.content AS comment_content, comments.time_stamp  AS comment_time_stamp, comments.validate  AS comment_validate, b.username AS comment_username, c.image_path AS comment_image
                FROM articles
                INNER JOIN users a
                ON articles.user_id = a.id
                LEFT JOIN user_profile
                ON a.id = user_profile.user_id
                LEFT JOIN comments
                ON articles.id = comments.article_id
                LEFT JOIN users b
                ON comments.user_id = b.id
                LEFT JOIN user_profile c
                ON b.id = c.user_id
                WHERE articles.validate != 0 
                AND a.validate != 0  
                ORDER BY articles.id DESC;";
        return $this->findAll($req);
    }
    
    public function getOneArticleFromId($id)
    {
        $req = "SELECT articles.id, articles.title, articles.content, articles.time_stamp, articles.audio_file, articles.image_path AS article_image, articles.validate AS article_validate, a.username, user_profile.image_path, comments.id AS comment_id, comments.user_id AS comment_user_id, comments.article_id AS comment_article_id, comments.content AS comment_content, comments.time_stamp  AS comment_time_stamp, comments.validate  AS comment_validate, b.username AS comment_username, c.image_path AS comment_image
                FROM articles
                INNER JOIN users a
                ON articles.user_id = a.id
                LEFT JOIN user_profile
                ON a.id = user_profile.user_id
                LEFT JOIN comments
                ON articles.id = comments.article_id
                LEFT JOIN users b
                ON comments.user_id = b.id
                LEFT JOIN user_profile c
                ON b.id = c.user_id
                WHERE articles.validate != 0 
                AND a.validate != 0 
                AND articles.id = :id;";
                
        $params = [
            'id' => $id
        ];
        
        return $this->findAll($req, $params);
    }
    
    public function getAllArticlesFromUser($user)
    {
        $req = "SELECT articles.id, articles.title, articles.content, articles.time_stamp, articles.audio_file, articles.image_path AS article_image, users.username
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
        $req = "INSERT INTO articles (user_id, title, content, audio_file, image_path) 
                VALUES (:user, :title, :content, :audio, :image)";
        
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
    
    public function desactivateArticle($articleId)
    {
        $req = "DELETE FROM `comments` WHERE `article_id` = :id;
                DELETE FROM `articles` WHERE `id` = :id;";
        
        $params = [
            'id' => $articleId    
        ];
        
        $this->createNew($req, $params);
    }
}