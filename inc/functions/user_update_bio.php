<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 08/08/2017
 * Time: 9:42 PM
 */
include '../../models/config.php';
if(isset($_POST['content'])){
    $content = strip_tags(htmlspecialchars(trim($_POST['content'])));
    $old_content = $mysqli->query("SELECT user_bio from portaluser_preferences WHERE user_id = $loggedInUser->user_id")->fetch_object()->user_bio;

    if($content == $old_content){
        echo json_encode(array("error"=>true, "title"=>"Not updated", "text"=>"It seems your bio hasn't changed! ", "type"=>"error"));
    }elseif (strlen($content) < 7){
        echo json_encode(array("error"=>true, "title"=>"Creative Spark, missing!", "text"=>"Your bio needs to be longer then 7 characters!", "type"=>"error"));
    } elseif(strlen($content) > 70){
        echo json_encode(array("error"=>true, "title"=>"Do you need help?", "text"=>"Your bio may only be 70 characters long.", "type"=>"error"));
    }else{
        $stmt = $mysqli->prepare("UPDATE `portaluser_preferences` SET user_bio = ? WHERE user_id = ?");

        $stmt->bind_param('si', $content, $loggedInUser->user_id);
        if($stmt->execute()){
            echo json_encode(array("error"=>false, "title"=>"Success?", "text"=>"Your bio has been successfully updated.", "type"=>"success"));
        }else{
            echo json_encode(array("error"=>true, "title"=>"Fatal Error", "text"=>"SQL ERROR", "type"=>"error"));
        }
    }
}else{
    die("Error");
}