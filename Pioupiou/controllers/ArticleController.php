<?php

namespace Controllers;

class ArticleController {
    
    public function getAllArticles()
    {
        $articlesModel = new \Models\Articles();
        $articles = $articlesModel->getAllArticles();
        
        $_SESSION['article-tokens'] = [];
        
        foreach($articles as $article) {
            $_SESSION['article-tokens'][] = [
                'id' => $article['id'],
                'token' => bin2hex(random_bytes(5))
            ];
            //var_dump($_SESSION['article-tokens']);
        }
         //var_dump($_SESSION['article-tokens']);
        //die();
        return $articles;
    }
    
    public function writeNewArticle(): void
    {
        
        $model = new \Models\Users();
        $model->isConnected();
        
        $errors = [];
        
        if(array_key_exists('article-title', $_POST) && array_key_exists('article-content', $_POST)) {
        
            $newArticle = [
                'user_id' => trim($_SESSION['user_data']['user_id']),
                'article-title' => trim($_POST['article-title']),
                'article-content' => trim($_POST['article-content'])
            ];
        }
            
        if(empty($newArticle['article-title'])) {
            $errors[] = "Veuillez saisir un titre";
        }
        
        if(empty($newArticle['article-content'])) {
            $errors[] = "Veuillez saisir un texte";
        }
        
        if(count($errors) == 0) {
            
            $data = [
                'user' => $newArticle['user_id'],
                'title' => $newArticle['article-title'],
                'content' => $newArticle['article-content']
            ];
                    
            $model = new \Models\Articles();
            $model->writeArticle($data);
            
        }
        
        header('Location: index.php?route=dashboard');
        exit;
        
    }

}