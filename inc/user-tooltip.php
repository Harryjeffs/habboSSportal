<?php
include '../models/config.php';
/**
 * Created by PhpStorm.
 * User: HarryJeffs
 * Date: 23/09/2017
 * Time: 2:50 PM
 */
$tool_username = $_GET['username'];
?>

<style>
    .user-tooltip-content{
        display: inline-block;
    }
    .comment-avatar {
        height: 55px;
        margin-bottom: 3px;
        width: 57px;
        border-radius: 5%;
        background-repeat: no-repeat;
        background-position: 35% 23%;
        margin-top: 5px;
        background-color: #f1f1f1;
    }

</style>
<?php
if(!empty($tool_username)){
    $tool_content = fetchUserDetails($tool_username, NULL, NULL);
    if(!$tool_content){
        echo "User details could not be fetched.";
    }else {
        echo'
            <div class="row user-tooltip-content">
                <div class="col-sm-4">
                    <div class="comment-avatar" style="background-image: url(\'http://www.habbo.com/habbo-imaging/avatarimage?user='.$tool_username.'&amp;action=std&amp;direction=2&amp;head_direction=2&amp;gesture=sml&amp;size=m&amp;img_format=gif\')"></div>
                </div>
                <div class="col-sm-7">
                    <b>'.$tool_username.'</b>
                    <p>
                        <small>'.$tool_content['rank'].'</small>
                        <br>
                        <a href="">Visit Profile</a>
                    </p>
                </div>
            </div>';
    }
}else{
    echo"Content loading error";
}
?>

