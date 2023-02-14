<?php

namespace Controllers;

class ProfilController {
    
    public function displayProfil(): void
    {
        
        $model = new \Models\Users();
        $model->isConnected();
        $template = "profil.phtml";
        include_once 'views/layout.phtml';
    }

}