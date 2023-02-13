<?php


//Choix du formulaire de la page d'accueil
if(!isset($_GET['action'])){
    $_GET['action'] = 'login';
}

$welcomeForm = $_GET['action'];
$welcomeForm = 'views/components/' . $welcomeForm . "_form.phtml";

require 'views/welcome.phtml';