<?php

namespace Controllers;

class ArticleController {
    
    public function getAllArticles()
    {
        //fetch articles and comments
        $articlesModel = new \Models\Articles();
        $articles_data = $articlesModel->getAllArticles();
        
        //sort and return data
        return $this->sortArticles($articles_data);
    }
    
    public function displayOneArticle()
    {
        //verify user is connecter
        $model = new \Models\Users();
        $model->isConnected();
        
        //verify route is correct
        if(!isset($_GET['id'])) {
            header('Location: index.php?route=404');
            exit;
        }
        
        //fetch the article
        $articlesModel = new \Models\Articles();
        $articles_data = $articlesModel->getOneArticleFromId($_GET['id']);
        
        //sort and prepare data
        $articles_data = $this->sortArticles($articles_data);
        $articles = $articles_data[0];
        $comments = $articles_data[1];
        
        //display view
        $template = "article.phtml";
        include_once 'views/layout.phtml';
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
    
    private function sortArticles($articles_data)
    {
        if(empty($articles_data)){
            header('Location: index.php?route=404');
            exit;
        }
        
        $articles = [];
        $comments = [];
        
        //separate and store the data from the articles and the data from the comments
        foreach($articles_data as $data){
            
            //if an article is already present in the $articles table, do not add it in
            $push = true;
            foreach($articles as $article){
                if($data['id'] === $article['id']){
                    $push = false;
                }
            }
            if($push){
                $articles[] = [
                    'id' => $data['id'],
                    'username' => $data['username'],
                    'validate' => $data['article_validate'],
                    'image_path' => $data['image_path'],
                    'content' => $data['content'],
                    'article_image' => $data['article_image'],
                    'audio_file' => $data['audio_file'],
                    'time_stamp' => $data['time_stamp']
                ];
            }
            
            $comments[] = [
                'id' => $data['comment_id'],
                'article_id' => $data['comment_article_id'],
                'validate' => $data['comment_validate'],
                'content' => $data['comment_content'],
                'username' => $data['comment_username'],
                'image_path' => $data['comment_image'],
                'time_stamp' => $data['comment_time_stamp']
            ];
        }
        
        //create tokens for each article and each comment
        $_SESSION['article-tokens'] = [];
        foreach($articles as $article) {
            $_SESSION['article-tokens'][] = [
                'id' => $article['id'],
                'token' => bin2hex(random_bytes(5))
            ];
        }
        $_SESSION['comment-tokens'] = [];
        foreach($comments as $comment) {
            $_SESSION['comment-tokens'][] = [
                'id' => $comment['id'],
                'token' => bin2hex(random_bytes(5))
            ];
        }
        
        //prepare for output
        $articles_data = [
            $articles,
            $comments
        ];
        
        return $articles_data;
    }

}