<?php

namespace Controllers;

class ProfileController {
    
    public function displayProfile(): void
    {
        //verify if connected
        $model = new \Models\Users();
        $model->isConnected();
        
        $profile_data = $this->fetchAndSortProfileData($_GET['user']);
        
        $profileInfos = $profile_data[0];
        //Apply default information if empty
        $profileInfos['banner_image'] = $profileInfos['banner_image'] ?? 'default_banner.png';
        $profileInfos['image_path'] = $profileInfos['image_path'] ?? 'default_profile.png';
        $profileInfos['description'] = $profileInfos['description'] ?? 'Cette description est vide...';
        
        
        $articles = $profile_data[1];
        $comments = $profile_data[2];
        
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
    
    private function fetchAndSortProfileData($user)
    {
        $model = new \Models\Profile();
        $profile_data = $model->getProfileInfos($user);
        
        $profileInfos = [];
        $articles = [];
        $comments = [];
        
        //separate and store the data from the articles and the data from the comments
        foreach($profile_data as $data){
            
            $profileInfos = [
                'username' => $data['username'],
                'banner_image' => $data['banner_image'],
                'image_path' => $data['image_path'],
                'email' => $data['email'],
                'creation_date' => $data['creation_date'],
                'description' => $data['description']
            ];
            
            //if an article is already present in the $articles table, do not add it in
            $push = true;
            foreach($articles as $article){
                if($data['article_id'] === $article['id']){
                    $push = false;
                }
            }
            
            if($push){
                $articles[] = [
                    'id' => $data['article_id'],
                    'username' => $data['username'],
                    'image_path' => $data['image_path'],
                    'content' => $data['article_content'],
                    'article_image' => $data['article_image'],
                    'audio_file' => $data['article_audio'],
                    'validate' => $data['article_validate'],
                    'time_stamp' => $data['article_time_stamp']
                ];
            }
            
            $comments[] = [
                'id' => $data['comment_id'],
                'article_id' => $data['comment_article_id'],
                'validate' => $data['comment_validate'],
                'content' => $data['comment_content'],
                'username' => $data['comment_author'],
                'image_path' => $data['comment_author_photo'],
                'time_stamp' => $data['comment_time_stamp']
            ];
        }
        
        //create tokens for each article and each comment
        $_SESSION['article-tokens'] = [];
        foreach($articles as $article) {
            $_SESSION['article-tokens'][] = [
                'id' => $article['id'],
                'token' => bin2hex(random_bytes(5))
            ];
        }
        $_SESSION['comment-tokens'] = [];
        foreach($comments as $comment) {
            $_SESSION['comment-tokens'][] = [
                'id' => $comment['id'],
                'token' => bin2hex(random_bytes(5))
            ];
        }
        
        $profile_data = [
            $profileInfos,
            $articles,
            $comments
        ];
        
        return $profile_data;
        
    }

}