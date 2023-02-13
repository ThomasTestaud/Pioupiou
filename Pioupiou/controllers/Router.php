<?php

session_start();

require 'models/User.php';
require 'controllers/UserController.php';


if(isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
        $controller = $_GET['page']??'Dashboard';
}else{
    $controller = 'Welcome';
}

$controller = 'controllers/'.$controller.'.php';