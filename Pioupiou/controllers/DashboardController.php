<?php

namespace Controllers;

class DashboardController {
    
    public function displayDashboard($articles_data): void
    {
        
        $model = new \Models\Users();
        $model->isConnected();
        
        $articles = $articles_data[0];
        $comments = $articles_data[1];
        
        $template = "dashboard.phtml";
        include_once 'views/layout.phtml';
    }

}