<?php

namespace Controllers;

class UserController {
    
    public function connect()
    {
        $errors = [];
        
        if(array_key_exists('login-id', $_POST) && array_key_exists('login-password', $_POST)) {
        
            $connectUser = [
                'login-id' => trim($_POST['login-id']),
                'login-password' => trim($_POST['login-password'])
            ];
            
        }
            
        if(empty($connectUser['login-id'])) {
            $errors[] = "Veuillez saisir un identifiant";
        }
        
        if(empty($connectUser['login-password'])) {
            $errors[] = "Veuillez saisir un mot de passe";
        }
        
        if(count($errors) == 0) {
                    
            $data = [
                'login-id' => $connectUser['login-id'],
                'login-password' => $connectUser['login-password']
            ];
                    
            $model = new \Models\Users();
            $result = $model->login($data);
            
            
            
            if(password_verify($data['login-password'], $result['password'])) {
                
                $_SESSION['user_data'] = [
                    'user_id' => $result['id'],
                    'username' => $result['username'],
                    'email' => $result['email'],
                    'creation_date' => $result['creation_date'],
                    'validate' => $result['validate']
                ];
                
                $_SESSION['connected'] = true;
                header('Location: index.php?route=dashboard');
                exit;
            }else {
                $errors[] = "Identifiant ou mot de passe incorrect";
            }
        }
        
        $template = "_login_form.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function register()
    {
        $errors = [];
        
        if(array_key_exists('register-username', $_POST) && array_key_exists('register-email', $_POST) && array_key_exists('register-password', $_POST) && array_key_exists('register-password-check', $_POST)) {
            
            $addUser = [
                'register-username' => trim($_POST['register-username']),
                'register-email' => trim(strtolower($_POST['register-email'])),
                'register-password' => trim($_POST['register-password']),
                'register-password-check' => trim($_POST['register-password-check'])
            ];
            
            if(empty($addUser['register-username'])) {
                $errors[] = "Veuillez saisir un Pseudo !";
            }
            
            if(!filter_var($addUser['register-email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Veuillez saisir un email valide";
            }
            
            if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $addUser['register-password'])) {
                $errors[] = "Le mot de passe doit être composé d'au moins 8 charactère dont au moins une majuscule, une minuscule, un nombre et un charachtère spécial (?:;./=+, etc.)";
            }
            
            if($addUser['register-password-check'] !== $addUser['register-password']) {
                $errors[] = "Les deux mots de passes doivent être identique";
            }
            
            if(count($errors) == 0) {
                    
                $data = [
                    'register-username' => $addUser['register-username'],
                    'register-email' => $addUser['register-email'],
                    'register-password' => password_hash($addUser['register-password'], PASSWORD_DEFAULT)
                ];
                    
                $model = new \Models\Users();
                $exist = $model->checkIfUserExist($data);
                
                //var_dump($exist); die;
                //var_dump($exist['username'], $exist['email'], $data['register-username'], $data['register-email']); die;
                
                if(empty($exist)) {
                    //create account
                    $id = $model->createAccount($data);
                    var_dump($id);
                    die();
                    
                    //save user's data into session
                    $datalog = [
                        'login-id' => $addUser['register-username'],
                        'login-password' => $addUser['register-password']
                    ];
                    //$result = $model->login($datalog);
                    $_SESSION['user_data'] = [
                        'user_id' => $result['id'],
                        'username' => $result['username'],
                        'email' => $result['email'],
                        'creation_date' => $result['creation_date'],
                        'validate' => $result['validate']
                    ];
                    
                    $_SESSION['connected'] = true;
                    
                    header('Location: index.php?route=dashboard');
                    exit;
                } else {
                     //$errors[] = "Erreur lors de la création du compte";
                    if(strtolower($exist['username']) === strtolower($data['register-username'])){
                        $errors[] = "Ce nom d'utilisateur est déjà pris. Veuillez en choisir un autre";
                    }
                    if(strtolower($exist['email']) === strtolower($data['register-email'])) {
                        $errors[] = "Vous avez déjà un compte à cette adresse mail";
                    }
                }
                
            }
            
        }
        
        $template = "_register_form.phtml";
        include_once 'views/layout.phtml';
    }
    
    public function isNotConnected(): void 
    {
        $model = new \Models\Users();
        $model->isNotConnected();
    }
    
    public function disconnect()
    {
        session_destroy();
        header('Location: index.php?route=welcome&action=login');
        exit;
    }
}