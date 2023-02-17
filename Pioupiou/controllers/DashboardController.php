<?php

namespace Controllers;

class DashboardController {
    
    public function displayDashboard(): void
    {
        
        $model = new \Models\Users();
        $model->isConnected();
        
        $articlescontroller = new ArticleController();
        $articles = $articlescontroller->getAllArticles();
        
        $commentsModel = new \Models\Comments();
        $comments = $commentsModel->getAllComments();
        
        $_SESSION['comment-tokens'] = [];
        
        
        foreach($comments as $comment) {
            $_SESSION['comment-tokens'][] = [
                'id' => $comment['id'],
                'token' => bin2hex(random_bytes(5))
            ];
        }
        
        //var_dump($_SESSION['comment-tokens']);var_dump($comments);die();
        
        $template = "dashboard.phtml";
        include_once 'views/layout.phtml';
    }

}