<?php

namespace Controllers;

class WelcomeController {
    
    public function displayLogin(): void
    {
        $form = "_login_form.phtml";
        $template = "welcome.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function displayRegister(): void
    {
        $form = "_register_form.phtml";
        $template = "welcome.phtml";
        include_once 'views/layout.phtml';
    }

}