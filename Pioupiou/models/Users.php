<?php

namespace Models;

class Articles extends Database {
    
    public function getAllArticles()
    {
        $req = "SELECT * FROM users";
        return $this->findAll($req);
    }
}