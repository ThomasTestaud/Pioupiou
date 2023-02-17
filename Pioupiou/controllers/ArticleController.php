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
        
        if(array_key_exists('article-content', $_POST)) {
        
            $newArticle = [
                'user' => trim($_SESSION['user_data']['user_id']),
                'title' => trim($_POST['article-title']),
                'content' => trim($_POST['article-content']),
                'audio' => null,
                'image' => null
            ];
        }
        
        if(empty($newArticle['content'])) {
            $errors[] = "Veuillez saisir un texte";
        }
    
        if(!empty($_FILES['article-audio']['name'])) {
            
            $fileName = bin2hex(random_bytes(7)) . basename($_FILES['article-audio']['name']);
            $target_path = 'public/uploads/audio_files/' . $fileName;
            move_uploaded_file($_FILES['article-audio']['tmp_name'], $target_path);
            $newArticle['audio'] = $fileName;
            
        }
        
        if(!empty($_FILES['article-image']['name'])) {
            
            $fileName = bin2hex(random_bytes(7)) . basename($_FILES['article-image']['name']);
            $target_path = 'public/uploads/article_img/' . $fileName;
            
            move_uploaded_file($_FILES['article-image']['tmp_name'], $target_path);
            
            $newArticle['image'] = $fileName;
        }
        
        if(count($errors) == 0) {
            
            $model = new \Models\Articles();
            $model->writeArticle($newArticle);
            
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
            $errors[] = "Erreur, vous n'avez pas les droits pour supprimer cet article";
        }
        
        if(count($errors) === 0) {
            $model = new \Models\Articles();
            $model->deleteArticle($_POST['article-id']);
        }
        
        header('Location: index.php?route=dashboard');
        exit;
    }

}