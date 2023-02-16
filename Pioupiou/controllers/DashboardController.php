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
        //var_dump($comments);die();
        
        $template = "dashboard.phtml";
        include_once 'views/layout.phtml';
    }

}