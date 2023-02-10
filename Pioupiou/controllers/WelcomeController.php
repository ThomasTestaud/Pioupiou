<?php

namespace Controllers;

class WelcomeController {
    
    public function displayWelcome() {
        
        if ($_GET['route'] === 'welcome' && $_GET['action'] === 'login' || $_GET['route'] === 'welcome' && $_GET['action'] === 'register'){
            $form = "_".$_GET['action']."_form.phtml";
            $template = "welcome.phtml";
            include_once 'views/layout.phtml';
        } else { 
            header('Location: index.php?route=welcome&action=login');
            exit;
        }
    }

}