<?php

    // Decode the json objects, and store the in data.txt     
    if($_POST['fields']) {
        $fields = json_decode(stripslashes($_POST['fields']), true);
        $sort = json_decode(stripslashes($_POST['sort']), true);
    
        file_put_contents("data.txt",serialize(array('fields'=>$fields, 'sort'=>$sort)));
    
        print_r($fields);
        print_r($sort);
    }