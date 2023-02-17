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
                
                $controller = new Controllers\DashboardController();
                $controller->displayDashboard();
                
            break;
            
            case 'profile':
                $controller = new Controllers\ProfileController();
                $controller->displayProfile();
            break;
            
            case 'disconnect':
                $controller = new Controllers\UserController();
                $controller->disconnect();
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