<?php

namespace Controllers;

class CommentController {
    
    public function writeNewComment(): void
    {
        
        $model = new \Models\Users();
        $model->isConnected();
        
        $errors = [];
        
        if(array_key_exists('comment', $_POST)) {
        
            $newComment = [
                'user' => trim($_SESSION['user_data']['user_id']),
                'article' => trim($_GET['id']),
                'content' => trim($_POST['comment'])
            ];
        }
        
            
        if(empty($newComment['content'])) {
            $errors[] = "Veuillez saisir un texte";
        }
        
        if(empty($newComment['article'])) {
            $errors[] = "L'article que vous tentez de commenter n'a pas été trouvé...";
        }
        
        if(count($errors) == 0) {
            
                    
            $model = new \Models\Comments();
            $model->writeComment($newComment);
            
        }
        
        header('Location: index.php?route=dashboard');
        exit;
        
    }

}