<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 12/07/2017
 * Time: 12:54 PM
 */
include_once('../models/config.php');
$message_id = $_GET['m_id'];


if(empty($message_id) or !is_numeric($message_id)){ header("Location: ".FULL_PATH."/index.php");}


$lmao = $mysqli->query("");
$v1 = $lmao->fetch_array();

$items = 9;
$page = 1;


if(currentForumThreadView($message_id) == 0){
    newForumThreadView($message_id);
}

if(isset($_GET['page']) and is_numeric($_GET['page']) and $page = $_GET['page'])
    $limit = " LIMIT ".(($page-1)*$items).",$items";
else
    $limit = " LIMIT $items";

$sqlStr = "SELECT * FROM `forum_replys` WHERE `message_id` = '$message_id' and deleted = false ORDER BY `message_id` desc";
$sqlStrAux = $mysqli->query("SELECT * FROM `forum_replys` WHERE `message_id` = '$message_id' and deleted = false ORDER BY `message_id` desc");

$query = $mysqli->query($sqlStr.$limit);

$p = new pagination;
$p->items($sqlStrAux->num_rows);
$p->limit($items);
$p->urlFriendly();
$p->target("/forum/thread/$message_id/%/");//#page/1/
$p->currentPage($page);
$p->calculate();

$permQ = $mysqli->query("SELECT `permission_id` FROM `forum_sub_category` WHERE `sub_category_id` = '$sub_cat_id'");
$permR = $permQ->fetch_array();

$permissionID = $permR['permission_id'];

$sub_cat_id = $v1['sub_category_id'];

$sub_cat_infoQ = $mysqli->query("SELECT sub_category_desc FROM `forum_sub_category` WHERE sub_category_id = $sub_cat_id");
$stuff = $sub_cat_infoQ->fetch_array();

?>
<!doctype html>
<html lang="en">
<head>
    <?php include '../models/header.inc.php'?>
</head>
<body>
<?php include '../inc/top-banner.php'?>
<div class="container" style="margin-top: 100px;">
    <div class="jumbo-thread" style="background-image: url('https://www.habbo.com/habbo-imaging/avatarimage?user=GenR.&amp;action=wav&amp;direction=4&amp;head_direction=&amp;gesture=sml&amp;size=l&amp;img_format=gif')">
        <div class="jumbotron">
            <h1>A helm</h1>
            <p>Posted <span data-livestamp=""> by <a href="#" style="color: rgba(0, 0, 0, 0.5)">GenR.</a></p>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li><a href="<? echo FULL_PATH ?>/index.php">Forum Home</a></li>
                    <li class="sub_cat_name"><a href="<? echo FULL_PATH ?>/messages/view.php">Messages</a></li>
                    <li class="active currentThreadTitle">A helm</li>
                </ol>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            hi
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include '../models/footer.inc.php'?>

</body>
</html>
