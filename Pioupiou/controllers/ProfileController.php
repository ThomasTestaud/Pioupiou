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
    
    public function verifyUpdateProfileForm()
    {
        if(array_key_exists('description', $_POST)) {
                
                $profileUser = [
                    'userId' => $_SESSION['user_data']['user_id'],
                    'description' => trim($_POST['description']),
                    'banner_image' => null,
                    'profile_image' => null
                ];
            
                if(!empty($_FILES['banner']['name']) && $_FILES['banner']['type'] === 'image/png' || $_FILES['banner']['type'] === 'image/jpg'){
                    echo 'passbanner';
                    $fileName = bin2hex(random_bytes(15)) . ".png";
                    $target_path = 'public/uploads/profile_img/' . $fileName;
                    move_uploaded_file($_FILES['banner']['tmp_name'], $target_path);
                    $profileUser['banner_image'] = $fileName;
                }
                
                if(!empty($_FILES['profile']['name']) && $_FILES['profile']['type'] === 'image/png' || $_FILES['profile']['type'] === 'image/jpg'){
                    echo 'passprofile';
                    $fileName = bin2hex(random_bytes(15)) . ".png";
                    $target_path = 'public/uploads/profile_img/' . $fileName;
                    move_uploaded_file($_FILES['profile']['tmp_name'], $target_path);
                    $profileUser['profile_image'] = $fileName;
                }
                
                return $profileUser;
        }
    }
    
    public function updateBanner()
    {
        
        if(!empty($_FILES['banner']['name']) && $_FILES['banner']['type'] === 'image/png'){
            
            $fileName = bin2hex(random_bytes(15)) . ".png";
            $target_path = 'public/uploads/profile_img/' . $fileName;
            move_uploaded_file($_FILES['banner']['tmp_name'], $target_path);
            
            $model = new \Models\Profile();
            $model->updateBanner($fileName);
        }
        header('Location: index.php?route=profile');
        exit;
    }
    
    public function updateProfilePicture()
    {
        if(!empty($_FILES['profile']['name']) && $_FILES['profile']['type'] === 'image/png' || $_FILES['profile']['type'] === 'image/jpg'){
            
            $fileName = bin2hex(random_bytes(15)) . ".png";
            $target_path = 'public/uploads/profile_img/' . $fileName;
            move_uploaded_file($_FILES['profile']['tmp_name'], $target_path);
            
            $model = new \Models\Profile();
            $model->updateProfilePicture($fileName);
        }
        header('Location: index.php?route=profile');
        exit;
    }
    
    public function updateDescription()
    {
        
        $errors = [];
        
        if(strlen($_POST['description']) > 500){
            
            $errors[] = 'Votre description ne doit pas dépasser les 500 charachtères. Vous en avez ' .  strlen($_POST['description']); 
            
        }
        
        //echo strlen($_POST['description']); echo count($errors); die
        
        if(count($errors) === 0) {
            $model = new \Models\Profile();
            $model->updateDescription($_POST['description']);
            header('Location: index.php?route=profile');
            exit;
        }else{
            $template = "edit_description.phtml";
            include_once 'views/layout.phtml';
        }
        
        
        
    }
    
    public function editBanner()
    {
        $template = "edit_banner.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function editProfilePicture()
    {
        $template = "edit_profile_photo.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function editDescription()
    {
        $template = "edit_description.phtml";
        include_once 'views/layout.phtml';
    }
    
    
}