<?php
/**
 * Created by PhpStorm.
 * User: HarryJeffs
 * Date: 28/09/2017
 * Time: 8:32 PM
 */
include "../models/config.php";

$checkPage = false;

if(!isUserLoggedIn()) {
    $url = htmlentities($_POST['path'], ENT_QUOTES);
    echo json_encode(array("online"=>false, "url"=>"/index.php?redirect_path=$url"));
}else{
    echo json_encode(array("online"=>true, "url"=>""));
}
