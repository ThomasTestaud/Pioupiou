<?php

namespace Controllers;

class SearchController {
    
    public function displaySearch()
    {
        $content = file_get_contents("php://input");
        $data = json_decode($content, true);
        
        $search = $data['textToFind'];
        
        $model = new \Models\Search();
        $results = $model->searchUsers($search);
        
        include_once 'views/_search_result.phtml';
    }

}