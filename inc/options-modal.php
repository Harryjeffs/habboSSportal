<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 12/03/2017
 * Time: 8:03 PM
 */
include '../models/config.php';
$post_id = $_POST['id'];
    $stmt = $mysqli->query("SELECT * FROM forum_replys WHERE reply_id = $post_id");
$v2 = $stmt->fetch_array();
$thread_id = $v2['thread_id'];
if(isset($_POST['id'])){
    ?>



        <ul>
            <li><a href="#" id="<?php echo $v2['uniqueID']; ?>" class="quoteButton">
                    <div class="more-box" style="border-top-left-radius: 5px; border-top-right-radius: 5px;">
                        Quote Post
                        <p>Copy the content on this post on a new reply</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="#" class="link-post" id="<?php echo $settings['website_url']['value']."/forum/thread/".$thread_id."/#".$v2['reply_id']; ?>">
                    <div class="more-box">
                        Link this post
                        <p>Get a link that directs to this post</p>
                    </div>
                </a>
            </li>
            <?php if($loggedInUser->user_id == $v2['user_id'] or $loggedInUser->checkPermission(array(12))){?>
                <li><a href="#" id="editBtn" data-id="<?php echo $v2['reply_id']; ?>">
                        <div class="more-box">
                            Edit Content
                        </div>
                    </a>
                </li>

            <?php }?>

        <?php if($loggedInUser->user_id == $v2['user_id'] or $loggedInUser->checkPermission(array(2,11))){?>
            <?php if(isFirstPost($v2['timestamp'], $thread_id)){?>

                <li><a href="#" id="titleEditOption" data-toggle="modal" data-target="#title-edit-modal">
                        <div class="more-box">
                            Edit Title
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo FULL_PATH; ?>/inc/functions/delete_thread.php?Tid=<?php echo $thread_id;?>&uid=<? echo $v2['user_id']; ?>" onclick="confirm('Are you sure you want to do this? Deleting this post will delete the whole thread. This action cannot be undone! NOTE: All actions are logged.');">
                        <div class="more-box" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
                            Delete Thread
                        </div>
                    </a>
                </li>
                <?php
            }else{
                ?>
                <li>
                    <a href="#" class="delete-post" data-postid="<?php echo $v2['reply_id']; ?>">
                        <div class="more-box" style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
                            Delete Post
                        </div>
                    </a>
                </li>
             <?php } ?>
        <?php }?>
        </ul>
    <?
}else
    die("ERROR: Invalid parameters given.  ")
?>