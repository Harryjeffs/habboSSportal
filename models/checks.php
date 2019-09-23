<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 19/11/2016
 * Time: 9:14 AM
 */

include("config.php");

    if(!isUserLoggedIn()) { header("Location: /index.php?redirect_path=".$_SERVER["REQUEST_URI"]); die(); }

    updateUserLastActivity();

    $userRank = fetchUserDetails(NULL, NULL, $loggedInUser->user_id);

    $loggedInUser = new loggedInUser();
    $loggedInUser->email = $userRank["email"];
    $loggedInUser->title = $userRank["title"];
    $loggedInUser->username = $userRank["user_name"];
    $loggedInUser->rank = $userRank["rank"];
    $loggedInUser->display_name = $userRank["display_name"];

    if($userRank['deleted'] == 1){
        $loggedInUser->userLogOut();
        header('Location: /');
    }
    if($maintenance > 1) {
        if (!$loggedInUser->checkPermission(array(2))) {
            header("Location: /maintenance.html");
        }
    }

    $check_banned_user = $mysqli->query("SELECT  `user_id`, `reason`, `autherising_user_id`, `timestamp_given`, `timestamp_end` FROM `forum_offences` WHERE `user_id` = $loggedInUser->user_id");
    if($check_banned_user->num_rows > 0){
        if($_SERVER['PHP_SELF'] != "/forum/banned.php"){
            header("Location: ".FULL_PATH."/banned.php");
            die();
        }
    }
//    $disabled = $mysqli->query("SELECT * FROM `forum_disable_user` WHERE `user_id` = $loggedInUser->user_id");
//    if($disabled->num_rows == 1){
//        $mysqli->query("DELETE FROM `forum_disable_user` WHERE `exp_timestamp` < NOW()");
//    }else{
//        $disable = $disabled->fetch_object();
//    }

