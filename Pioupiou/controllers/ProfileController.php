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

}