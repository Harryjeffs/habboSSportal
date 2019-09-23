<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 15/06/2017
 * Time: 10:03 PM
 */
include '../../models/config.php';

    if (isset($_POST['threadTitle'])){

        $thread_header = $_POST['threadTitle'];
        $thread_id = intval($_POST['threadId']);
            $time = time();
        $stmt1 = $mysqli->prepare("INSERT INTO `forum_changes`(`thread_id`, `titled_change`, `timestamp`) VALUES (?,true,?)");
        $stmt1->bind_param('ii', $thread_id, $time);
        $stmt1->execute();

        $stmt1->close();


        $stmt = $mysqli->prepare("UPDATE `forum_thread` SET `topic_header` = ? WHERE `thread_id` = ?");

        $stmt->bind_param('si', $thread_header, $thread_id);
        if($stmt->execute()){
            echo json_encode(array("threadTitle"=>$thread_header, "title"=>"success", "text"=>"You have successfully updated the threads topic. "));
        }else{
            http_response_code(505);
        }
    }else{
        die("Invalid parameters. ");
    }