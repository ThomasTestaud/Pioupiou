<?php

namespace Controllers;

class GameController {
    
    public function displayCarCity(): void
    {
        $model = new \Models\Users();
        $model->isConnected();
        
        $template = "car_city.phtml";
        include_once 'views/layout.phtml';
    }

}