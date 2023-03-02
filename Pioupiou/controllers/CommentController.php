<?php

namespace Controllers;

class CommentController {
    
    public function writeNewComment(): void
    {
        
        $model = new \Models\Users();
        $model->isConnected();
        
        $errors = [];
        
        if(array_key_exists('comment', $_POST)) {
        
            $newComment = [
                'user' => trim($_SESSION['user_data']['user_id']),
                'article' => trim($_POST['article-id']),
                'content' => trim($_POST['comment'])
            ];
        }
        
        $tokenSession = '0';
        
        foreach($_SESSION['article-tokens'] as $entry) {
            if($entry['id'] == $newComment['article']) {
                $tokenSession = $entry['token'];
                break;
            }
        }
        
        if(strlen($newComment['content']) > 500) {
            $errors[] = "Le texte ne dois pas dépasser les 500 charactère. Vous en avez saisi " . strlen($newComment['content']);
        }
        
        if($tokenSession !== $_POST['article-token']){
            $errors[] = "Erreur, vous n'avez pas les droits pour commenter cet article";
        }
            
        if(empty($newComment['content'])) {
            $errors[] = "Veuillez saisir un texte";
        }
        
        if(empty($newComment['article'])) {
            $errors[] = "L'article que vous tentez de commenter n'a pas été trouvé...";
        }
        
        if(count($errors) == 0) {
            $model = new \Models\Comments();
            $model->writeComment($newComment);
            
        }
            header('Location: index.php?route=article&id='.$_POST['article-id']);
            exit;
        
    }
    
    public function deleteComment(): void
    {
        $errors = [];
        $tokenSession = '0';
        
        foreach($_SESSION['comment-tokens'] as $entry) {
            if($entry['id'] == $_POST['comment-id']) {
                $tokenSession = $entry['token'];
                break;
            }
        }
        
        if(empty($_POST['comment-id'])){
            $errors[] = "Erreur";
        }
        
        if($tokenSession !== $_POST['comment-token']){
            $errors[] = "Erreur, vous n'avez pas les droits pour commenter cet article";
        }
        
        if(count($errors) === 0) {
            $model = new \Models\Comments();
            $model->deleteComment($_POST['comment-id']);
        }
        
        header('Location: index.php?route=dashboard');
        exit;
    }
    
    public function getCommentsofArticle()
    {
        $content = file_get_contents("php://input");
        $data = json_decode($content, true);
        
        $id = $data['id'];
        
        $model = new \Models\Comments();
        $results = $model->getCommentsFromArticle($id);
        $utilities = new \Models\Utilities();
        
        $comments = [];
        
        foreach($results as $result){
            
            
            $article['id'] = $result['article_id'];
            $article['username'] = $result['article_username'];
            
            $comments[] = [
                'id' => $result['id'],
                'username' => $result['username'],
                'content' => $result['content'],
                'time_stamp' => $utilities->calculateDate($result['time_stamp']),
                'image_path' => $result['image_path'],
                'article_id' => $result['article_id'],
                'validate' => $result['validate']
            ];
        }
        
        foreach($comments as $comment) {
            $_SESSION['comment-tokens'][] = [
                'id' => $comment['id'],
                'token' => bin2hex(random_bytes(10))
            ];
        }
        
        $allComments = true;
        
        require 'views/_comments.phtml';
    }

}