<?php
    include('models/config.php');

    $username = $loggedInUser->username;
    $userdetails = fetchUserDetails($username, NULL, NULL); // We get the users complete information.

    $user_id = $userdetails['id']; //Define the $user_id variable.

    $userPermission = fetchUserPermissions($user_id); //Get all the permission groups this user is in.
    $permissionData = fetchAllAdminPermissions(); //Get all the permissions this user can alter.

    $stmt = $mysqli->query("SELECT * FROM `portaluser_preferences` WHERE user_id = $loggedInUser->user_id");
    if ($stmt->num_rows == 0){
        newUserPref();
    }
        $preference = $stmt->fetch_object();
    $title = "Settings";
?>
<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800,900" rel="stylesheet">
<style>
    .container {
        max-width: 900px
    }
</style>
<style>
    .form-control {
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #2b2b2b;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 3px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0) !important;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0) !important;
        -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    }

    hr {
        margin-top: 0px !important;
    }

    label {
        display: block;
        max-width: 100%;
        margin-bottom: 7px !important;
        font-weight: 500;
        color: rgba(0, 0, 0, 0.6);
        font-size: 14px;
        letter-spacing: .3px;
        margin-top: 20px;
    }

    li {
        font-size: 16px;
        font-weight: 400;
        color: rgba(0, 0, 0, .5) !important;
    }
    .sub-txt {
        font-size: 16px;
        font-weight: 400;
        color: rgba(0, 0, 0, .5) !important;
    }
    .sub-txt p a {
        color: rgba(0, 0, 0, .5) !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.44);
    }
    .panel-body-1 {
        padding: 0px 23px !important;
    }
</style>
<?php include('models/header.inc.php')?>
<?php include('inc/top-banner.php')?>
<div class="container" style="margin-top: 100px;">
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="avatar-holder">
                    <div class="avatar-big" style="background-image: url('http://www.habbo.com/habbo-imaging/avatarimage?user=<?php echo $username; ?>&action=wav&direction=2&head_direction=2&gesture=sml&size=l&img_format=gif')"></div>
                </div>
                <div class="panel-body">
                    <div class="basic-profileInfo">
                        <div class="profile-username"><?php echo $username; ?></div>
                        <div class="profile-rank"><?php echo $userdetails['rank']; ?></div>
                        <p class="profile-bio">I'm sexy, amazing, awesome. Too many words to describe myself. </p>
                    </div>
                    <center>
                        <a href="<? echo FULL_PATH ?>/#" class="btn btn-send btn-sm"><i class="ion-android-mail"></i> Message</a> <div class="btn-group">
                            <button type="button" class="btn btn-gray dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ion-gear-a"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <!-- tbworked-->
                                <li><a href="<? echo FULL_PATH ?>/#">View user's profile</a></li>
                                <li><a href="<? echo FULL_PATH ?>/#">Assign user badge</a></li>
                            </ul>
                        </div>
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
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Preferences</h3>
                </div>
                <div class="panel-body panel-body-1">

                    <div class="settings-body">
                        <form id="user-settings" action="" novalidate autocomplete="off">
                            <div class="form-group">
                                <label for="">Email address</label>
                                <input type="email" class="form-control" id="email" value="<?php echo $loggedInUser->email; ?>" placeholder="john@example.com">
                            </div>
                            <div class="form-group">
                                <label for="">Display Name</label>
                                <p class="helper-text--settings sub-txt" style="font-size: 12.5px;">Your display name will be shown publicly on all your post. <br/>Preferably, use your first name or identifiable nickname.</p>
                                <input type="displayName" class="form-control" id="display_name" placeholder="" value="<?php echo $loggedInUser->display_name; ?>">
                            </div>
                            <div class="checkbox">
                                <?php
                                    if($preference->pref_showOnline == 1){
                                        $Onlinechecked = "checked";
                                    }else{
                                        $Onlinechecked = "";
                                    }
                                ?>
                                <label><input type="checkbox" value="1" id="show_online" name="show_online" <?php echo $Onlinechecked; ?>>Do not show me online <p class="helper-text--settings" style="margin-top: 3px; font-size: 12.5px;">You will be excluded from being displayed on the online users list.</p>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="">Gender</label>
                                <?php
                                switch ($preference->pref_Gender){
                                    case "Male":
                                        $maleChecked = "checked";$femalechecked = "";$otherChecked = "";
                                        break;
                                    case "Female":
                                        $femalechecked = "checked";$maleChecked = "";$otherChecked = "";
                                        break;
                                    Case "Other":
                                        $otherChecked = "checked";$maleChecked = "";$femalechecked = "";
                                        break;
                                    default:
                                        $maleChecked = "checked";$femalechecked = "";$otherChecked = "";
                                }
                                ?>
                                <label class="radio-inline" style="margin-top: 0px !important">
                                    <input type="radio" name="inlineRadioOptions" id="gender" value="Male" <?php echo $maleChecked; ?>> Male
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="gender" value="Female" <?php echo $femalechecked; ?>> Female
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="gender" value="Other" <?php echo $otherChecked; ?>> Other
                                </label>
                            </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Social Accounts</h3>
                </div>
                <div class="panel-body panel-body-1">
                    <div class="form-group">
                        <label for="">Skype</label>
                        <input type="email" class="form-control" id="social_skype" value="<?php echo $preference->social_skype; ?>" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="">Twitter</label>
                        <input type="email" class="form-control" id="social_twitter" value="<?php echo $preference->social_twitter; ?>" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="">Facebook</label>
                        <input type="email" class="form-control" id="social_facebook" value="<?php echo $preference->social_facebook; ?>" placeholder="">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="float: right;">Update</button>
            </form>
        </div>




    </div>
    <!-- close col-md-9-->
</div>
<?php include('models/footer.inc.php')?>