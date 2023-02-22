<?php

namespace Models;

require('config/config.php');

class Database {
    
    protected $bdd;
    
    public function __construct()
    {
        try {
            $this->bdd = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS, [
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                ]);
        }catch(\PDOException $e) {
            //redirique erreur 404
            echo 'erreur 404';
            die();
        }
    }
    
    protected function findAll($req, $params = [])
    {
        $query = $this->bdd->prepare($req);
        $query->execute($params);
        return $query->fetchAll();
    }
    
    protected function findOne($req, $params = [])
    {
        $query = $this->bdd->prepare($req);
        $query->execute($params);
        return $query->fetch();
    }
    
    protected function createNew($req, $params =[]){
        $query = $this->bdd->prepare($req);
        $query->execute($params);
        return $this->bdd->lastInsertId();
    }
    
    protected function update($req, $params =[]){
        $query = $this->bdd->prepare($req);
        $query->execute($params);
    }
}