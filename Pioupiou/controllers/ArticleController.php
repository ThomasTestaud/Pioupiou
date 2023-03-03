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
        $allComments = true;
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
        
        if(empty($newArticle['content']) && empty($_FILES['article-audio']['name']) && empty($_FILES['article-image']['name'])) {
            $errors[] = "Veuillez remplir au moins l'un des trois champs";
        }
        
        if(strlen($newArticle['content']) > 500) {
            $errors[] = "Le texte ne dois pas dépasser les 500 charactère. Vous en avez saisi " . strlen($newArticle['content']);
        }
        
        if(!empty($_FILES['article-audio']['name'])) {
            $fileName = bin2hex(random_bytes(13)) . "mp3";
            $target_path = 'public/uploads/audio_files/' . $fileName;
            move_uploaded_file($_FILES['article-audio']['tmp_name'], $target_path);
            $newArticle['audio'] = $fileName;
        }
        
        if(!empty($_FILES['article-image']['name'])) {
            $fileName = bin2hex(random_bytes(13)) . "png";
            $target_path = 'public/uploads/article_img/' . $fileName;
            move_uploaded_file($_FILES['article-image']['tmp_name'], $target_path);
            $newArticle['image'] = $fileName;
        }
        
        if(count($errors) == 0) {
            $model = new \Models\Articles();
            $model->writeArticle($newArticle);
            
            $_SESSION['flying-notifications'][] = 'Votre post a bien été ajouté';
            
            
            
            header('Location: index.php?route=dashboard');
            exit;
        }
        
        $articles_data = $this->getAllArticles();
        
        $articles = $articles_data[0];
        $comments = $articles_data[1];
        
        $template = "dashboard.phtml";
        include_once 'views/layout.phtml';
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
            
            $_SESSION['flying-notifications'][] = 'Votre post a bien été supprimé';
        }
        
        header('Location: index.php?route=dashboard');
        exit;
    }
    
    private function sortArticles($articles_data)
    {
        $utilities = new \Models\Utilities();
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
                    'time_stamp' => $utilities->calculateDate($data['time_stamp'])
                ];
            }
            
            $comments[] = [
                'id' => $data['comment_id'],
                'article_id' => $data['comment_article_id'],
                'validate' => $data['comment_validate'],
                'content' => $data['comment_content'],
                'username' => $data['comment_username'],
                'image_path' => $data['comment_image'],
                'time_stamp' => $utilities->calculateDate($data['comment_time_stamp'])
            ];
        }
        
        
        //Create a 'filtered comments array' to only display a certain amount of comments under each post
        $lastId = 0;
        $count = 0;
        //Maximun number of comments displayed under each post
        $commentsAmount = 2;
        //Reverse the array to keep only the most recent comments
        $reverseComments = array_reverse($comments);
        
        foreach($reverseComments as $comment){
            //var_dump($comment);
            if($comment['article_id'] !== $lastId){
                $count = 0;
            }
            if($comment['validate'] !== 0 && $comment['article_id'] !== null){
                if($count < $commentsAmount){
                    if($comment['article_id'] === $lastId) {
                        $count++;
                        //echo'count<br>';
                        //echo $count;
                    }
                    //echo 'push<br>';
                    $filteredComment[] = $comment;
                    
                }
            }
            $lastId = $comment['article_id'];
        }
        
        
        //Reverse the array again to put back the comments in the chronologicle order
        $filteredComment = array_reverse($filteredComment);
        //var_dump($filteredComment);
        //die();
        
        //create tokens for each article and each comment
        $_SESSION['article-tokens'] = [];
        foreach($articles as $article) {
            $_SESSION['article-tokens'][] = [
                'id' => $article['id'],
                'token' => bin2hex(random_bytes(10))
            ];
        }
        $_SESSION['comment-tokens'] = [];
        foreach($comments as $comment) {
            $_SESSION['comment-tokens'][] = [
                'id' => $comment['id'],
                'token' => bin2hex(random_bytes(10))
            ];
        }
        
        //prepare for output
        $articles_data = [
            $articles,
            $comments,
            $filteredComment
        ];
        
        return $articles_data;
    }

}