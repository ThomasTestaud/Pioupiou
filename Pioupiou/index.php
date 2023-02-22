<?php

session_start();

spl_autoload_register(function($class) {
    require_once lcfirst(str_replace('\\','/', $class)) .'.php';
});



//router
if(array_key_exists('route', $_GET)) {
    
    switch($_GET['route']) {
            
            case 'dashboard':
                
                if(isset($_GET['action'])){
                    switch($_GET['action']) {
                        case 'post':
                            $controller = new Controllers\ArticleController();
                            $controller->writeNewArticle();
                        break;
                        
                        case 'delete-article':
                            $controller = new Controllers\ArticleController();
                            $controller->deleteArticle();
                        break;
                        
                        case 'comment':
                            $controller = new Controllers\CommentController();
                            $controller->writeNewComment();
                        break;
                        
                        case 'delete-comment':
                            $controller = new Controllers\CommentController();
                            $controller->deleteComment();
                        break;
                        
                        default:
                            header('Location: index.php?route=dashboard');
                            exit;
                        break;
                    }
                    
                }
                
                $controller = new Controllers\ArticleController();
                $articles_data = $controller->getAllArticles();
                
                $controller = new Controllers\DashboardController();
                $controller->displayDashboard($articles_data);
                
            break;
            
            case 'profile':
                
                if(isset($_GET['user'])){
                    $controller = new Controllers\ProfileController();
                    $controller->displayProfile();
                }else{
                    header('Location: index.php?route=profile&user='. $_SESSION['user_data']['username']);
                    exit;
                }
            break;
            
            
            case 'update-description':
                $controller = new Controllers\ProfileController();
                $controller->updateDescription();
            break;
            
            case 'update-banner':
                $controller = new Controllers\ProfileController();
                $controller->updateBanner();
            break;
            
            case 'update-profile-photo':
                $controller = new Controllers\ProfileController();
                $controller->updateProfilePicture();
            break;
            
            case 'edit-description':
                $controller = new Controllers\ProfileController();
                $controller->editDescription();
            break;
            
            case 'edit-banner':
                $controller = new Controllers\ProfileController();
                $controller->editBanner();
            break;
            
            case 'edit-profile-photo':
                $controller = new Controllers\ProfileController();
                $controller->editProfilePicture();
            break;
            
            case 'article':
                $controller = new Controllers\ArticleController();
                $controller->displayOneArticle();
            break;
            
            case 'disconnect':
                $controller = new Controllers\UserController();
                $controller->disconnect();
            break;
            
            case '404':
                require 'views/erreur404.phtml';
            break;
            
            case 'welcome':
                $user = new Controllers\UserController();
                $user->isNotConnected();
                
                switch($_GET['action']) {
                
                    case 'login':
                        $controller = new Controllers\WelcomeController();
                        $controller->displayLogin();
                    break;
                    
                    case 'login-submit':
                        $user = new Controllers\UserController();
                        $user->connect();
                    break;
                    
                    case 'register':
                        $controller = new Controllers\WelcomeController();
                        $controller->displayRegister();
                    break;
                    
                    case 'register-submit':
                        $user = new Controllers\UserController();
                        $user->register();
                    break;
                    
                    default:
                        header('Location: index.php?route=welcome&action=login');
                        exit;
                    break;
                }
            break;
            
            default:
                header('Location: index.php?route=dashboard');
                exit;
            break;
            
        }
        
} else {
    header('Location: index.php?route=welcome&action=login');
    exit;
}


// session_destroy();