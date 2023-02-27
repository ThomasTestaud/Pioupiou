<?php

namespace Models;

class Utilities {
    
    public function calculateDate($sqlDate)
    {
        date_default_timezone_set('Europe/Paris');
        $current_date = date('Y-m-d H:i:s');
        // $current_date = '2023-02-27 09:28:25';
        //$sqlDate = $sqlDate;
        
        $datetime1 = new \DateTime($current_date);
        $datetime2 = new \DateTime($sqlDate);
        
        $interval = $datetime1->diff($datetime2);
        
        $result = 'Il y a ';
        
        //handmade switch case...
        $continue = true;
        
        if ($interval->y === 1 && $continue) {
            $result .= $interval->y . ' an, ';
            $continue = false;
        }
        
        if ($interval->y > 1 && $continue) {
            $result .= $interval->y . ' ans';
            $continue = false;
        }

        if ($interval->m > 1 && $continue) {
            $result .= $interval->m . ' mois';
            $continue = false;
        }
        
        if ($interval->d === 1 && $continue) {
            $result .= $interval->d . ' jour';
            $continue = false;
        }
        
        if ($interval->d > 1 && $continue) {
            $result .= $interval->d . ' jours';
            $continue = false;
        }
        
        
        if ($interval->h === 1 && $continue) {
            $result .= $interval->h . ' heure, ';
            $continue = false;
        }
        
        if ($interval->h > 1 && $continue) {
            $result .= $interval->h . ' heures, ';
            $continue = false;
        }
        
        if ($interval->i > 1 && $continue) {
            $result .= $interval->i . ' minutes';
            $continue = false;
        }
        
        if ($continue) {
            $result = "À l'instant";
            $continue = false;
        }
        
        $result = rtrim($result, ', ');
        return $result;
    }
 
}