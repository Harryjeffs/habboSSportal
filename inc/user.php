<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 08/06/2017
 * Time: 8:50 PM
 */

include("../models/config.php");
    $json = array();
$userData = fetchAllUsers();

    foreach($userData as $item){
        $json[] = array("id"=>$item['id'], "name"=>$item['user_name']);
    }

        echo json_encode($json);
