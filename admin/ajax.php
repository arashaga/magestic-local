<?php

include_once '../config.php';
include_once '../utility.php';

$action = $_POST['action'];

if(isset($action)){
    
    if(isset($action['email-checkbox']))
        swithEmailOnOff();
    
    if(isset($action['allusers']))
        echo json_encode('allusers');
    
    if(isset($action['list']))
        echo json_encode('list');
}else{
    echo json_encode('no data!');
}

//?>