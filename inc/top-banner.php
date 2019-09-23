<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 17/01/2017
 * Time: 1:53 PM
 */

?>


<nav class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="sr-only">Toggle navigation</span>
            </button>
            <a class="navbar-brand" href="#">
                <div class="brand-mark"><img class="ss-bdg" src="<?php echo FULL_PATH ?>/assets/img/forum-nav-logo.png"></div>
            </a>

        </div>

        <div class="collapse navbar-collapse" id="navigation">
            <ul class="nav navbar-nav">
                <li id="fadeshow1" style="margin-left: 10px;">
                    <a href="<?php echo FULL_PATH ?>/latest-threads/">Latest Threads</a>
                </li>
                <li id="fadeshow1">
                    <a href="<?php echo FULL_PATH ?>/latest-posts/">Today’s Posts</a>
                </li>
                <?php if($loggedInUser->checkPermission(array(2,11))){
                    ?>
                    <li class="icon icon-admin">
                        <a href="<?php echo FULL_PATH ?>/admin/index.php"><i class="ion-ios-gear"></i><span class="badge badge-alert"></span></a>
                    </li>
                    <?
                }?>
                <li class="icon icon-mail">
                    <a href="<?php echo FULL_PATH ?>/messages/view.php"><i class="ion-ios-email" style="font-size: 30px;"></i><span class="badge badge-alert">1</span></a>
                </li>
                <li class="icon icon-notifi">
                    <a data-target="#modalAlerts" data-toggle="modal" href="#"><i class="ion-ios-bell" id="notification-count"></i></a>
                </li>
                <li>
                    <a href="#"></a>
                    <div class="dropdown">
                        <a href="#"></a> <a href="#"></a> <a href="#">
                            <div class="avatar" style="margin-top: -20px; margin-left: 10px; background-image: url('http://www.habbo.com/habbo-imaging/avatarimage?user=GenR.&direction=4&head_direction=4&action=wav&gesture=sml&s');"></div></a>
                    </div><!-- /.navbar-collapse -->
                </li>
        </div>
        </div>
    </div>
</nav>
<div aria-labelledby="myModalLabel" class="modal fade" id="modalAlerts" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" class="close modal-close" data-dismiss="modal" type="button"><span aria-hidden="true"><i class="ion-android-close"></i></span></button>

                <h4 class="modal-title" id="myModalLabel">Alerts</h4><!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active" role="presentation">
                        <a aria-controls="home" data-toggle="tab" href="#unread" role="home">Unread Alerts</a>
                    </li>
                    <li role="presentation">
                        <a aria-controls="profile" data-toggle="tab" href="#read" role="tab">Read Alerts</a>
                    </li>
                </ul>
            </div>
            <div id="notification-notices">
            </div>
        </div>
    </div>
</div>
