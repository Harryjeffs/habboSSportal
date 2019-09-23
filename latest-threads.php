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

$title = "Latest Threads";

if(isset($_GET['page']) and is_numeric($_GET['page']) and $page = $_GET['page'])
    $limit = " LIMIT ".(($page-1)*$items).",$items";
else
    $limit = " LIMIT $items";

$sqlStr = "SELECT * FROM `forum_thread` WHERE timestamp >= UNIX_TIMESTAMP(CURDATE()) and deleted = false ORDER BY `thread_id` desc";
$sqlStrAux = $mysqli->query("SELECT * FROM `forum_thread` WHERE timestamp >= UNIX_TIMESTAMP(CURDATE()) and deleted = false ORDER BY `thread_id` desc");

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
<html>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="A unique portal made for the Habbo Secret Service to create and View logs made at the Agency on a day to day basis. ">
<meta name="author" content="Harry Jeffs Habbo SS">
<meta name="keywords" content="Habbo SS Portal Logging Log In Greetings, Dashboard">
<link rel="icon" href="/favicon.ico">

<title>Habbo SS Forum - <?php echo $title; ?></title>

<!--  Bootstrap CSS -->
<link href="./assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">

<!-- Icon CSS -->
<link rel="stylesheet" href="./assets/css/ion-icons.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" type="text/css" href="./assets/css/pnotify.custom.min.css">

<!-- Random CSS -->
<link rel="stylesheet" href="/forum/wysibb/theme/default/wbbtheme.css" />
<link href='//fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="./assets/css/forum.css?v=67">
<body>
<?php include ("inc/top-banner.php");?>
<link rel="stylesheet" href="./assets/css/forum.css?v=183">
<div class="container" style="margin-top: 100px;">

    <div class="jumbotron">
        <h1>Latest Thread's</h1>
        <p>Today's latest thread activity</p>
    </div>
    <div class="row" style="margin-top: 20px">
        <div class="col-md-10">
            <ol class="breadcrumb">
                <li><a href="/forum">Forum Home</a></li>
                <li class="active">Latest Threads</li>
            </ol>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="#" class="toggle-threads active"><i
                    class="ion-ios-paper"></i> <?php echo $sqlStrAux->num_rows; ?> Discussions</a>
        </div>
        <div class="panel-body">
            <ul class="threads-listing">
                <?php
                if($sqlStrAux->num_rows == 0){
                    echo'      <ul class="threads-listing">
        <p style="text-align: center; margin-top: 15px; margin-bottom: 15px; color: rgba(0, 0, 0, 0.4)">No threads have been posted today! Make sure you check back soon.</p></ul>';}
                ?>
                <?php while ($cat_fetched = $query->fetch_array()) {
                    $reply = $mysqli->query("SELECT reply_id FROM `forum_replys` WHERE thread_id = " . $cat_fetched['thread_id'] . "");
                    echo '<li class="threads-item">
                    <div class="row">
                        <div class="col-xs-10 threadInfo">
                            <div class="threadAvatar pull-left">
                                <div class="userAvatar-md" style="background-image: url(\'http://www.habbo.com/habbo-imaging/avatarimage?user=' . getForumUsername($cat_fetched['user_id']) . '&gesture=sml\');"></div>
                            </div>
                            <h4 class="titleThread">';
                    if ($cat_fetched['pinned'] == 1)
                        echo '<i class="ion-pin threadPinned" data-toggle="tooltip" data-trigger="hover" title="Pinned Thread"></i>';
                    if ($cat_fetched['locked'] == 1)
                        echo '<i class="ion-ios-locked threadPinned" data-toggle="tooltip" data-trigger="hover" title="Locked Thread"></i>';

                    echo '<a href="/forum/thread/'.$cat_fetched['thread_id'].'/" class="linkPrimary">' . $cat_fetched['topic_header'] . '</a></h4>
                            <p class="metaThread">Posted <span data-livestamp="' . $cat_fetched['timestamp'] . '"></span> by <a href="/user/stats/'.getForumUsername($cat_fetched['user_id']).'" class="linkSecondary">' . getForumUsername($cat_fetched['user_id']) . '</i></a></p>
                        </div>
                        <div class="col-xs-2">
                            <div class="pull-right replyCount">
                                <i class="ion-ios-chatboxes-outline replyCountIcon"></i> <span class="replyCountInt">' . $reply->num_rows . '</span>
                            </div>
                        </div>
                    </div>
                </li>';
                } ?>

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
<script src="/forum/js/forum.js"></script>
</body>
</html>
