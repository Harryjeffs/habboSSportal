<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 15/03/2017
 * Time: 6:34 PM
 */
//Include the main config.php file
include 'models/config.php';

//include the user stats class so we can see the users program wide stats.
include 'models/class/user_stats.php';

    $username = $_GET['username']; //Define the $user variable to the name in the URL bar
    $title = "ERROR"; // Define the page's title.

    if(empty($username)) { // check if the username is empty, if true, return an error.
        $emptyName = true;
    } else {
        //Else we continue on with the validation
        $emptyName = false;

        if (!usernameExists($username)) { // We use a function that calls the DB to see whether this username exists or not.
            $nameExists = false;
        } else {
            $nameExists = true; // change the value to true so we don't encounter any errors.

            $userdetails = fetchUserDetails($username, NULL, NULL); // We get the users complete information.

            $title = "User Profile - " . $userdetails['user_name']; // Define the page's title.
            $user_id = $userdetails['id']; //Define the $user_id variable.

            $userPermission = fetchUserPermissions($user_id); //Get all the permission groups this user is in.
            $permissionData = fetchAllAdminPermissions(); //Get all the permissions this user can alter.

            $logs = new user_stats(); // initiate a new use of the "user_stats" class.

            $logs->calculate($username, $user_id); // Run the "calculate" function.
        }

    }
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include 'models/header.inc.php'; // Include the header.inc.php file, this imports all our meta and css files.
    ?>
    <link href="//https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800,900" rel="stylesheet">
</head>
<body>
<?php
include 'inc/top-banner.php'; // Include the top banner for the page, purely HTML.
?>

<!-- Container styling modification reserved for only threads. this is to make threads more condensed rather than open leaving lots of white spacing. -->
<style>
    .container {
        max-width: 900px
    }
</style>
<div class="container" style="margin-top: 100px;">
    <div class="row">
        <?php

                if($emptyName){ //Check if the $emptyName variable is equal to true.

                echo"<div class=\"alert alert-danger\"><strong>No name? That's odd. Please specific a username to view. </strong></div>";

                }elseif(!$nameExists){ //Check if the $nameExists variable returns false.
                        echo"<div class=\"alert alert-danger\"><strong>This user does not exist.</strong> </div>";
                    }else{
        ?>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="avatar-holder">
                    <div class="avatar-big" style="background-image: url('http://www.habbo.com/habbo-imaging/avatarimage?user=<?php echo $username; ?>&action=wav&direction=2&head_direction=2&gesture=sml&size=l&img_format=gif')">
                    </div>
                </div>
                <div class="panel-body">
                    <div class="basic-profileInfo">
                        <div class="profile-username"><?php echo $username; ?></div>
                        <div class="profile-rank"><?php echo $userdetails['rank']; ?></div>
                        <?php
                        $old_content = $mysqli->query("SELECT user_bio from portaluser_preferences WHERE user_id = $user_id")->fetch_object()->user_bio;

                        if($user_id == $loggedInUser->user_id){
                                if($old_content == ""){
                                    $old_content = "Insert bio here";
                                }
                            ?>
                            <p class="profile-bio" id="content" contenteditable="true">
                                <?php
                                echo $old_content;
                                ?>
                            </p>
                            <?
                        }else{
                            ?>
                            <p class="profile-bio">
                                <?php
                                echo $old_content;
                                ?>
                            </p>
                            <?
                        }
                        ?>
                </div>
                    <center>
                        <?php
                        if($user_id != $loggedInUser->user_id){
                           // echo"<button class='btn btn-primary'>Follow</button>";
                        }
                        ?>
                        <a href="<? echo FULL_PATH ?>/#" class="btn btn-send btn-sm"><i class="ion-android-mail"></i> Message</a>
                    <div class="btn-group">
                    <?php if($loggedInUser->checkPermission(array(2,11))){ ?>
                            <button type="button" class="btn btn-gray dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ion-gear-a"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <!-- tbworked-->
                                <li><a href="<? echo FULL_PATH ?>/admin/user/edit.php?id=<?php echo $user_id; ?>">View user's profile</a></li>
<!--                                <li><a href="--><?// echo FULL_PATH ?><!--/#">Assign user badge</a></li>-->
                            </ul>
                        </div>
                        <?php }elseif  ($loggedInUser->user_id == $user_id){
                            ?>
                            <button type="button" class="btn btn-gray" href="<? echo FULL_PATH ?>/settings.php">
                                <i class="ion-gear-a"></i>
                            </button>
                        <?
                        } ?>
                    </center>
                    <div class="section-profile">
                        <div class="profile-sectionContainer">
                            <h3>SPECIAL UNITS</h3>
                            <?php
                            showUserPerms($permissionData, $userPermission);?>
                        </div>
                        <!-- close.profile-sectionContainer-->
                    </div>
                    <!-- close .section-profile-->
                    <div class="section-profile">
                        <div class="profile-sectionContainer">
                            <h3>BADGES</h3>
                            <div class="imageBox" style="margin-right: 3.5px;">
                                <img style="min-width: 40px;min-height: 40px;max-width: 40px;max-height: 40px;" border="0" src="http://habboo-a.akamaihd.net/c_images/album1584/NL431.gif" data-placement="top" data-toggle="tooltip" title="" data-original-title="Successfully registering on the portal">
                            </div>
                            <div class="imageBox" style="margin-right: 3.5px;">
                                <img style="min-width: 40px;min-height: 40px;max-width: 40px;max-height: 40px;" border="0" src="http://portal.habboss.com./assets/img/badges/IT747.gif" data-placement="top" data-toggle="tooltip" title="" data-original-title="For logging on during Christmas">
                            </div>
                            <div class="imageBox" style="margin-right: 3.5px;">
                                <img style="min-width: 40px;min-height: 40px;max-width: 40px;max-height: 40px;" border="0" src="http://habboo-a.akamaihd.net/c_images/album1584/HML05.gif" data-placement="top" data-toggle="tooltip" title="" data-original-title="Sip, sip.">
                            </div>
                        </div>
                       <!-- close.profile-sectionContainer-->
                    </div>
                    <!-- close .section-profile-->
                </div>
               <!-- close .panel-body-->
            </div>
            <!-- close .panel default-->
        </div>
        <!-- close .col-md-3-->
        <div class="col-md-9">
            <div class="panel panel-default">
                <h3 class="portal-statsProfiel">Portal Stats</h3>
                <ul class="nav nav-tabs" style="    list-style-type: none;
    margin: 0;
    padding-left: 19px !important;
    padding-right: 19px;
    padding: 0;
    /* background: #f9f9f9; */
    border-bottom: 1px solid #efefef !important;">
                    <li class="active"><a data-toggle="tab" href="<? echo FULL_PATH ?>/#home">All Time</a></li>
                </ul>
                <div class="panel-body" style="padding: 20px;     padding-bottom: 15px !important;">
                    <div class="row no-padding">
                        <div class="col-md-4 no-gutter">
                            <div class="stats-box">
                                <h1><? echo $logs->promotion(0);?></h1>
                                <p>Promotion Logs</p>
                            </div>
                            <!-- close .stats-box-->
                        </div>
                        <!-- close .row-md-4-->
                        <div class="col-md-4 no-gutter">
                            <div class="stats-box">
                                <h1><? echo $logs->trainer(0); ?></h1>
                                <p>Trainer Logs</p>
                            </div>
                            <!-- close .stats-box-->
                        </div>
                        <!-- close .row-md-4-->

                        <div class="col-md-4 no-gutter">
                            <div class="stats-box">
                                <h1><? echo $logs->fired(0); ?> </h1>
                                <p>Fired Logs</p>
                            </div>
                            <!-- close .stats-box-->
                        </div>
                        <!-- close .row-md-4-->
                        <div class="col-md-4 no-gutter">
                            <div class="stats-box stats-box2">
                                <h1><? echo $logs->sold_ranks(0); ?></h1>
                                <p>Sold Ranks</p>
                            </div>
                            <!-- close .stats-box-->
                        </div>
                        <!-- close .row-md-4-->
                        <div class="col-md-4 no-gutter">
                            <div class="stats-box stats-box2">
                                <h1><? echo $logs->discipline(0); ?></h1>
                                <p>Disciplinary Logs</p>
                            </div>
                            <!-- close .stats-box-->
                        </div>
                        <!-- close .row-md-4-->
                        <div class="col-md-4 no-gutter">
                            <div class="stats-box stats-box2">
                                <h1><? echo $logs->promo_received(0); ?></h1>
                                <p>Promo. Recieved</p>
                            </div>
                            <!-- close .stats-box-->
                        </div>
                        <!-- close .row-md-4-->
                    </div>
                    <!-- close .row and .no-padding-->
                </div>
                <!-- close col-md-9-->
            </div>
            <!-- close .panel-default-->
            <div class="panel panel-default">
                <h3 class="portal-statsProfiel">Forum Stats</h3>
                <div class="panel-body" style="padding: 20px;     padding-bottom: 15px !important;">
                    <div class="row no-padding" style="margin-top: -15px">
                        <div class="col-md-6 no-gutter">
                            <div class="stats-box">
                                <h1><?php echo $logs->forumTotalPosts(); ?></h1>
                                <p>Total Posts</p>
                            </div>
                            <!-- close.stats-box-->
                        </div>
                        <!-- close .col-md-6-->
                        <div class="col-md-6 no-gutter">
                            <div class="stats-box">
                                <h1><?php echo $logs->totalLikesRec(); ?></h1>
                                <p>Likes Received</p>
                            </div>
                           <!-- close.stats-box-->
                        </div>
                       <!-- close .col-md-6-->
                    </div>
                    <!-- close .row-->
                </div>
               <!-- close.panel.body-->
            </div>
            <!--close .panel-default-->
        </div>
        <!--close .col.md-9-->
    </div>
    <!--close .row-->
    <?php } include 'models/footer.inc.php'; ?>
    <style>
        #content{
            padding: 5px;
            border: 1px solid #EEE;
            background: rgba(255, 255, 0,0 );
            transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;        }

        #content:focus {
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            border-color: #00a7d0;
        }

    </style>
</body>
</html>