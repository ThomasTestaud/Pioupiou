<?php

namespace Controllers;


class UserController {
    
    public function isConnected(): bool 
    {
        if (isset($_SESSION['connected']) && $_SESSION['connected'] === true){
            return true;
        } else {
            return false;
        }
    }

}