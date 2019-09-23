<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 11/12/2016
 * Time: 10:15 PM
 */

include("../../models/config.php");

if(isset($_POST['threadID'])) {

    $thread_id = $_POST['threadID'];

    $content = rawurldecode($_POST['replyContent']);
    $content = str_replace('<br>', "\n", $content);
    $content = htmlspecialchars($content, ENT_HTML5, 'UTF-8');

    $stmt = $mysqli->query("SELECT  user_id, thread_id, topic_header FROM `forum_thread` INNER JOIN forum_sub_category ON forum_thread.sub_category_id = forum_sub_category.sub_category_id
 WHERE forum_thread.thread_id = $thread_id");
    $new_post = $stmt->fetch_array();

    $old_text = $mysqli->query("SELECT `reply_content`  FROM `forum_replys` WHERE `user_id` = $loggedInUser->user_id ORDER by timestamp DESC LIMIT 1")->fetch_object()->reply_content;
    similar_text($content, $old_text, $percentage);
    switch (true) {
        case strlen($content) == 0 || $content == "":
            echo json_encode(array("error" => true, "title" => "Empty?", "text" => "Please enter a response."));
            break;
        case strlen($content) <= 6 :
            echo json_encode(array("error" => true, "title" => "Oops", "text" => "Your response must be more then 6 characters in length."));
            break;
        case lastPost() :
            echo json_encode(array("error" => true, "title" => "Slow down", "text" => "You may only post once every two minutes!"));
            break;
        case round($percentage) > 90:
            echo json_encode(array("error" => true, "title" => "Change it up!", "text" => "Your last post is too similar to this post. "));
            break;
        default:

            $key = true;

            while ($key) {

                $randomKey = generateRandomString();
                // Do stuff
                if (checkUniqueID($randomKey) == 0)
                    $key = false;

            }
            $action = "$loggedInUser->username replied to a thread, " . $new_post['topic_header'] . "";
            $page = "Reply Post";
            $level = 1;
            userForumLogs($page, $action, $level);

            $insert_id = submitFormReply($thread_id, $content, $randomKey);
            // String to extract string from.
            $exploded = get_all_string_between($content);


            $page_num = $mysqli->query("SELECT reply_id FROM forum_replys WHERE thread_id = $thread_id and deleted = false ORDER BY reply_id ASC");

            if ($exploded > 0) {
                foreach ($exploded as $concured) {
                    $stmt1 = $mysqli->query("SELECT `user_id`, `uniqueID` FROM forum_replys WHERE uniqueID = '$concured'");
                    $fetched = $stmt1->fetch_array();
                    if ($loggedInUser->user_id != $new_post['user_id']) {
                        $notification_id = newForumNotification2(1, $new_post['topic_header'], $thread_id . "/" . calculatePage($page_num->num_rows) . "#" . $insert_id);
                        forumNotificationReciever($notification_id, $fetched['user_id']);
                    }
                }
            }
            $i = forumFindMention($content);
            $u = userMentionNotify($i, $new_post['topic_header'], $thread_id . "/" . calculatePage($page_num->num_rows) . "#" . $insert_id);
            
            if (swearFilterCheck($content)) {
                insertForumSwear($insert_id);
            }
            include '../pages/drawups/new_post.php';
            
            $posted = new new_post();
            $posted->parse($content, $insert_id);

            if ($loggedInUser->user_id != $new_post['user_id']) {
                $notification_id = newForumNotification2(1, $new_post['topic_header'], $thread_id . "#" . $insert_id);
                forumNotificationReciever($notification_id, $new_post['user_id']);
            }

            $page_num = calculatePage($page_num->num_rows);

            echo json_encode(array(
                "error" => false,
                "title" => "Success",
                "text" => "You have successfully replied to this post!",
                "href" => FULL_PATH . "/thread/$thread_id/$page_num/#$insert_id",
                "page" => $page_num,
                "newPostHtml" => $posted->value($thread_id)
            ));
            break;
    }
}else{
    die("Error");
}