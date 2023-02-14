<?php

namespace Controllers;

class ArticleController {
    
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
            var_dump($newArticle);
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