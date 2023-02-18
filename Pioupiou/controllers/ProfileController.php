<?php

namespace Controllers;

class ProfileController {
    
    public function displayProfile(): void
    {
        
        $model = new \Models\Users();
        $model->isConnected();
        
        $model = new \Models\Profile();
        $profileInfos = $model->getProfileInfos($_GET['user']);
        //var_dump($profileInfos);die();
        
        if(!$profileInfos) {
            header('Location: index.php?route=404');
            exit;
        }
        
        $profileInfos['banner_image'] = $profileInfos['banner_image'] ?? 'default_banner.png';
        $profileInfos['image_path'] = $profileInfos['image_path'] ?? 'default_profile.png';
        $profileInfos['description'] = $profileInfos['description'] ?? 'Cette description est vide...';
        
        $model = new \Models\Articles();
        $articles = $model->getAllArticlesFromUser($_GET['user']);
        
        foreach($articles as $article) {
            $_SESSION['article-tokens'][] = [
                'id' => $article['id'],
                'token' => bin2hex(random_bytes(5))
            ];
        }
        
        $model = new \Models\Comments();
        $comments = $model->getAllComments();
        
        
        $template = "profil.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function editProfile(): void
    {
        
        $model = new \Models\Users();
        $model->isConnected();
        
        $model = new \Models\Profile();
        $profileInfos = $model->getProfileInfos($_SESSION['user_data']['username']);
        //var_dump($profileInfos);die();
        
        if($profileInfos['image_path'] === null){
            $profileInfos['image_path'] = 'default_profile.png';
            /////////***************CREER LA TABLE USER_PROFILE ICI*************///////////
        }
        $profileInfos['banner_image'] = $profileInfos['banner_image'] ?? 'default_banner.png';
        $profileInfos['image_path'] = $profileInfos['image_path'] ?? 'default_profile.png';
        $profileInfos['description'] = $profileInfos['description'] ?? 'Cette description est vide...';
        
        $template = "profil_edit.phtml";
        include_once 'views/layout.phtml';
    }

}