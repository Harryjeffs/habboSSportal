<?php
/**
 * Created by PhpStorm.
 * User: HarryJeffs
 * Date: 8/11/2016
 * Time: 9:03 PM
 */
include "../models/config.php";
?>
<?php include '../models/header.inc.php'?>
<?php include '../inc/top-banner.php'?>

<div class="container" style="margin-top: 100px;">
    <div class="alert alert-info">
        The messages feature is currently being worked on. Please check back soon as updates will be posted regularly.
    </div>
    <div class="jumbotron" style="display: none;">
        <h1>Conversations</h1>
        <p>Privately have discussions amongst other members</p>
    </div>
    <div class="row" style="margin-top: 20px; display: none;">
        <div class="col-md-10">
            <ol class="breadcrumb">
                <li><a href="/forum">Forum Home</a></li>
                <li class="active">Conversations</li>
            </ol>
        </div>
<!--        <div class="col-md-2"><a href="/forum/new/1" class="btn btn-block btn-success" style="min-height: 37px; padding-top: 8px;">Compose</a></div>-->
    </div>
    <div class="panel panel-default" style="display: none;">
        <div class="panel-heading">
            <a href="#" class="toggle-threads active">
                <i class="ion-android-mail"></i> 1 Conversations</a>
        </div>
        <div class="panel-body">
            <ul class="threads-listing">
                <li class="threads-item">
                    <div class="row">
                        <div class="col-xs-10 threadInfo">
                            <div class="threadAvatar pull-left">
                                <div class="userAvatar-md" style="background-image: url('http://www.habbo.com/habbo-imaging/avatarimage?user=admin&amp;gesture=sml');"></div>
                            </div>
                            <h4 class="titleThread"><a href="messages.php" class="linkPrimary">I thank for you your help!</a></h4>
                            <p class="metaThread">
                                <a href="/user/stats/admin" class="linkSecondary">GenR,</a>
                                <a href="/user/stats/admin" class="linkSecondary">stupefystar,</a>
                                <a href="/user/stats/admin" class="linkSecondary">richmond233</a>
                            </p>
                        </div>
                        <div class="col-xs-2">
                            <div class="pull-right replyCount">
                                <i class="ion-android-share replyCountIcon"></i> <span class="replyCountInt">1</span>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php include '../models/footer.inc.php'?>
