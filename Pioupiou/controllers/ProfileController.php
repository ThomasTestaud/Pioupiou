<?php

namespace Controllers;

class ProfileController {
    
    public function displayProfile(): void
    {
        
        $model = new \Models\Users();
        $model->isConnected();
        
        $model = new \Models\Profile();
        $profileInfos = $model->getProfileInfos($_GET['user']);
        
        if($profileInfos['image_path'] === null){
            $profileInfos['image_path'] = 'default_profile.png';
            $activated = false;
        }else{
            $activated = true;
        }
        
        
        
        $template = "profil.phtml";
        include_once 'views/layout.phtml';
    }

}