<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 29/01/2017
 * Time: 2:45 PM
 */
include_once('models/config.php');
$items = 13;
$page = 1;
$title = " Today's Posts";


if(isset($_GET['page']) and is_numeric($_GET['page']) and $page = $_GET['page'])
    $limit = " LIMIT ".(($page-1)*$items).",$items";
else
    $limit = " LIMIT $items";

$sqlStr = "SELECT * FROM `forum_replys` WHERE timestamp >= UNIX_TIMESTAMP(CURDATE()) and deleted = false ORDER BY `thread_id` desc";
$sqlStrAux = $mysqli->query("SELECT * FROM `forum_replys` WHERE timestamp >= UNIX_TIMESTAMP(CURDATE()) and deleted = false ORDER BY `thread_id` desc");

$query = $mysqli->query($sqlStr.$limit);

$p = new pagination;
$p->items($sqlStrAux->num_rows);
$p->limit($items);
$p->urlFriendly();
$p->changeClass();
$p->target("/forum/latest-posts/%/");//#page/1/
$p->currentPage($page);
$p->calculate();

?>

<!doctype html>
<!--suppress HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget -->
<?php include 'models/header.inc.php'; ?>
<?php include ("inc/top-banner.php");?>

<link rel="stylesheet" href="./assets/css/forum.css?v=183">
<div class="container" style="margin-top: 100px;">
    <?php


    ?>
    <div class="jumbotron">
        <h1>Today's Posts</h1>
        <p>View the Today's posts from today.</p>
    </div>
    <div class="row" style="margin-top: 20px">
        <div class="col-md-10">
            <ol class="breadcrumb">
                <li><a href="<? echo FULL_PATH?>/index.php">Forum Home</a></li>
                <li class="active">Today's Posts</li>
            </ol>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="#" class="toggle-threads active">
                <i class="ion-ios-paper"></i> <?php echo $sqlStrAux->num_rows; ?> Discussions</a>
        </div>
        <div class="panel-body">
            <ul class="threads-listing">
                <?php
                if($sqlStrAux->num_rows == 0){
                    echo' 
                      <ul class="threads-listing">
                        <p style="text-align: center; margin-top: 15px; margin-bottom: 15px; color: rgba(0, 0, 0, 0.4)">Nothing has been posted today</p>
                      </ul>';
                }else {
                    while ($cat_fetched = $query->fetch_array()) {
                        $reply = $mysqli->query("SELECT topic_header FROM `forum_thread` WHERE thread_id = " . $cat_fetched['thread_id'] . "");
                        $topic_header = $reply->fetch_array();
                        echo '<li class="threads-item">
                    <div class="row">
                        <div class="col-xs-10 threadInfo">
                            <div class="threadAvatar pull-left">
                                <div class="userAvatar-md" style="background-image: url(\'http://www.habbo.com/habbo-imaging/avatarimage?user=' .getUsername($cat_fetched['user_id'],"promotion") . '&gesture=sml\');"></div>
                            </div>
                            <h4 class="titleThread">
                            <a href="'.FULL_PATH.'/thread/' . $cat_fetched['thread_id'] . '/" class="linkPrimary">' . $topic_header['topic_header'] . '</a></h4>
                            <p class="metaThread">Posted <span data-livestamp="' . $cat_fetched['timestamp'] . '"></span> by <a href="'.FULL_PATH.'/user/' . getUsername($cat_fetched['user_id'],"promotion") . '" class="linkSecondary">' . getUsername($cat_fetched['user_id'],"promotion") . '</i></a></p>
                        </div>
                        <div class="col-xs-2">
                            <div class="pull-right replyCount">
                                <i class="ion-ios-chatboxes-outline replyCountIcon"></i> <span class="replyCountInt">' . $reply->num_rows . '</span>
                            </div>
                        </div>
                    </div>
                </li>';
                    }
                }?>

            </ul>
        </div>
    </div>
</div>
    <center>
        <?php

        $p->show();
        ?>
        <br/>
    </center>


<?php include("models/footer.inc.php"); ?>
</body>
</html>

