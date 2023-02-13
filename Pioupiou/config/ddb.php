<?php

        try {
            $db = new PDO('mysql:host=db.3wa.io;dbname=thomastestaud_Pioupiou', 'thomastestaud', 'c23e2220a763a675189ecf8ba3b047fa', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ]);
            // echo "connect";
                return $db;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }