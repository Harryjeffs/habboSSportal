<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 06/12/2016
 * Time: 11:41 AM
 */
include_once('../../models/config.php');

if(isset($_POST)) {



    $reciever_id = $_POST['reciever_user_id'];
    $user_id = $loggedInUser->user_id;
    $post_id = intval($_POST['post_id']);



// check if this user ip has liked this item or not
    $query = $mysqli->prepare("SELECT * FROM `forum_likes` WHERE user_id = ? AND post_id = ? LIMIT 1");

    $query->bind_param('ii', $user_id, $post_id);
    $query->execute();

    $query->store_result();

    $check = $query->num_rows;

    $query->close();

    if($reciever_id == $loggedInUser->user_id){
        $check = 1;
        echo json_encode(array("error"=>true, "title"=>"Like hunting!!", "text"=>"As nice as it would be, you cannot like your own post :("));
    } elseif ($post_id == 0){
        echo json_encode(array("error"=>true, "title"=>"error", "text"=>"An error has occurred. Please try reloading the page. "));
        $check = 1;
    } if ($check == 0) {
// if not liked before insert the liked item ID and the user IP to database
            $add = $mysqli->prepare("INSERT INTO forum_likes (`user_id`, `post_id`, `reciever_user_id`) VALUES (?,?,?)");

            $add->bind_param('iii', $loggedInUser->user_id, $post_id, $reciever_id);


        $stmt4 = $mysqli->query("SELECT * FROM forum_replys WHERE reply_id = $post_id");
        $fetched = $stmt4->fetch_array();

        $thread_id = $fetched["thread_id"];

        $topic_header = $mysqli->query("SELECT topic_header FROM forum_thread WHERE thread_id = $thread_id")->fetch_object()->topic_header;


                $notification_id = newForumNotification2(2,$topic_header, $fetched['thread_id']."#".$fetched['reply_id']);
                forumNotificationReciever($notification_id, $fetched['user_id']);

        $action = "$loggedInUser->username liked someones's post'.";
        $page = "Liked Post";
        $level = 1;
        userForumLogs($page, $action, $level);

        if ($add->execute()) {
// after adding the like (vote) to database, consume the number of item's likes
            $stmt = $mysqli->prepare("SELECT * FROM `forum_likes` WHERE post_id = ?");
            $stmt->bind_param('i', $post_id);
            $stmt->execute();

            $stmt->store_result();

            $numberOfLikes = $stmt->num_rows;
            $stmt->close();

            $newLikes = forumLikesHtml($post_id);
            echo json_encode(array("error" => false, "title" => "Success", "text" => "You have successfully liked this thread.", "HTML" => $newLikes));

// return new number of item's likes instead of the current likes number.
        }else{

        }
    }
} else {
// if POST not isset return 0 value
    echo 0;
}
