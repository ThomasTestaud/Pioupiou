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
        }
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
    
    public function deleteArticle(): void
    {
        $errors = [];
        $tokenSession = '0';
        
        foreach($_SESSION['article-tokens'] as $entry) {
            if($entry['id'] == $_POST['article-id']) {
                $tokenSession = $entry['token'];
                break;
            }
        }
        
        if(empty($_POST['article-id'])){
            $errors[] = "Erreur";
        }
        
        if($tokenSession !== $_POST['article-token']){
            $errors[] = "Erreur, vous n'avez pas les droits pour commenter cet article";
        }
        
        var_dump($_SESSION['article-tokens']);
        var_dump($tokenSession);
        var_dump($_POST['article-token']);
        var_dump($errors);
        
        if(count($errors) === 0) {
            $model = new \Models\Articles();
            $model->deleteArticle($_POST['article-id']);
        }
        
        
        
        header('Location: index.php?route=dashboard');
        exit;
    }

}