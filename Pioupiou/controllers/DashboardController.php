<?php

namespace Controllers;

class DashboardController {
    
    public function displayDashboard(): void
    {
        
        $model = new \Models\Users();
        $model->isConnected();
        $template = "dashboard.phtml";
        include_once 'views/layout.phtml';
    }

}