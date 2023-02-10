<?php

session_start();

spl_autoload_register(function($class) {
    require_once lcfirst(str_replace('\\','/', $class)) .'.php';
});

$user = new Controllers\UserController();

//router
if(array_key_exists('route', $_GET)) {
    
    if($user->isConnected()){
    
        switch($_GET['route']) {
            
            case 'dashboard':
            break;
            
            case 'profile':
            break;
            
            default:
                header('Location: index.php?route=dashboard');
                exit;
            break;
            
        }
    
    } else {
        
        
        $controller = new Controllers\WelcomeController();
        $controller->displayWelcome();
    }
    
} else {
    header('Location: index.php?route=welcome&action=login');
    exit;
}