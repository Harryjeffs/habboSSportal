<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 14/01/2017
 * Time: 3:09 PM
 */

require_once("../../../models/config.php");


if($loggedInUser->checkPermission(array(12, 1))){

    $id = intval($_POST['post_id']);

    $stmt = $mysqli->prepare("UPDATE `forum_replys` SET `deleted`= 1 WHERE reply_id = ?");

    $stmt->bind_param('i', $id);
    $stmt->execute();

    $stmt->close();

    $action = "$loggedInUser->username has deleted a forum post $id";
    $page = "Delete Post";
    $level = 3;
    userForumLogs($page, $action, $level);
}else{
    header("Location: " . FULL_PATH . "/index.php");
}