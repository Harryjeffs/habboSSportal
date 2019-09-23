<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 20/01/2017
 * Time: 11:24 AM
 */

include_once('../../models/config.php');


    $content = str_replace('<br>', "\n",strip_tags($_POST['threadContents'], "<br>"));

    $thread_title = strip_tags($_POST['threadTitle']);
    $sub_category_id = $_POST['subCat'];
    $key = true;


    if(strlen($content) == 0 or empty($content)){
        echo json_encode(array("error"=>true,"title"=>"It's empty?","text"=>"You need to enter something in the box!"));
    }else if (empty($thread_title)){
        echo json_encode(array("error"=>true,"title"=>"It's empty?","text"=>"Please enter a thread title"));
    }else if (strlen($thread_title) < 2){
        echo json_encode(array("error"=>true,"title"=>"That title is short","text"=>"Your post can't be this sort!"));
    }else if(lastPost() == 2){
        echo json_encode(array("error"=>true,"title"=>"Slow down!","text"=>"You may only post every 2 minutes!"));
    }else {
        while ($key) {
            $randomKey = generateRandomString();
            // Do stuff
            if (checkUniqueID($randomKey) == 0)
                $key = false;
        }
        $pinned = $_POST['pinned'];

        if (empty($pinned) or $pinned == 0) {
            $pinned = 0;
        } else {
            $pinned = 1;
        }

        $header = htmlspecialchars($thread_title);


       $thread_id = newForumThread($sub_category_id, $header, $pinned);

       $insert_id = submitFormReply($thread_id, $content, $randomKey);

        echo json_encode(array("error"=>false,"href"=>FULL_PATH."/thread/$thread_id"));

        $i = forumFindMention($content);
        $u = userMentionNotify($i, $header, $thread_id."#".$insert_id);

        $action = "$loggedInUser->username created a new thread:  $header";
        $page = "New Thread";
        $level = 1;
        userForumLogs($page, $action, $level);

    }
