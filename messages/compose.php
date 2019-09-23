<?php
/**
 * Created by PhpStorm.
 * User: HarryJeffs
 * Date: 31/10/2016
 * Time: 9:47 AM
 */

include_once('models/config.php');

$title = "New Thread";

$sub_category_id = $mysqli->real_escape_string($_GET['id']);

$permQ = $mysqli->query("SELECT `permission_id` FROM `forum_sub_category` WHERE `sub_category_id` = '$sub_category_id'");
$permR = $permQ->fetch_array();

$permissionID = $permR['permission_id'];

$sub_cat_infoQ = $mysqli->query("SELECT sub_category_desc, sub_category_long_desc FROM `forum_sub_category` WHERE sub_category_id = $sub_category_id");
$stuff = $sub_cat_infoQ->fetch_array();
?>
<!doctype html>
<!--suppress HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget -->
<html>
<head>
    <?php include'models/header.inc.php'; ?>
</head>
<body>
<?php include('inc/top-banner.php');?>


<div class="container" style="margin-top: 100px;">
    <?php
    if($sub_cat_infoQ->num_rows == 0){
        echo "<div class='alert alert-warning'>
                                It seems this category does not exist. Please return to the <a href='/forum/'>forum home</a>.
                           </div>";
    }else if($loggedInUser->checkPermission(array($permissionID))){
    ?>
    <div class="jumbotron" >
        <h1>Create Thread</h1>
        <p>Publish a new discussion in <?php echo $stuff['sub_category_desc']; ?></p>
    </div>
    <ol class="breadcrumb">
        <li>
            <a href="/forum">Forum Home</a>
        </li>
        <li>
            <a href="/forum/category/<?php echo $sub_category_id; ?>"><?php echo $stuff['sub_category_desc']; ?></a>
        </li>
        <li class="active">Create Thread</li>
    </ol>
    <form id="formid" action="" title="" method="post" novalidate>                                  <div class="panel panel-default">
            <div class="panel-heading" style="padding: 0px; padding-left: 5px; padding-right: 5px; background: #fafafa;">
                <div class="row">
                    <div class="col-xs-8">
                        <input aria-describedby="basic-addon3" name="threadTitle" class="form-control removeShadowInput" id="threadTitle" placeholder="Title of thread" style="border: none; background: #fafafa; margin-top: 4px; font-size: 16px;" type="text">
                    </div>
                    <div class="col-xs-4">
                        <label class="checkbox"><input style="height: 20px; width: 20px" type="checkbox"> <span class="control-indicator"></span> <span class="controller-text">Pin Thread</span></label>
                    </div>
                </div>
            </div>
            <div class="panel-body" style="padding: 0 !important;">
                <input type="hidden" name="threadID" id="subCat" value="<?php echo $sub_category_id ?>">
                <textarea id="threadContents" name="threadContents" rows="6"></textarea>
                <button type="submit" class="btn btn-success btn-block" name="threadBtn" style="border-radius: 0px; border-bottom-left-radius: 3px; border-bottom-right-radius: 3px">Publish</button>

            </div>
        </div>
    </form>
    <div class="alert alert-info">
        All threads and posts which do not comply with our forum rules, Code of Conduct, or our terms and conditions will be acted upon by our forum moderation team. If moderation action is required, your account will receive a warning.
    </div>
</div>

<?php
}else{
    echo "<div class='alert alert-warning'>
                                You do not have permission to post in this category. Please return to the <a href='/forum/'>forum home</a>.
                           </div>";
}
?>
</div>
<!-- ./main-contento -->
</div>
<!-- ./row -->
</div>
<!-- ./col-md-12 -->
</div>
<!-- ./main--content -->
</div>


<?php include'models/footer.inc.php'; ?>
<script>
    $(function() {
        var wbbOpt = {
            buttons: "bold,italic,underline,strike,|,img,imgleft,imgright,video,link,smileList,smilebox,|,bullist,numlist,|,fontcolor,|,justifyleft,justifycenter,justifyright,|,removeFormat"
        }
        $("#threadContents").wysibb(wbbOpt);
    })
</script>
</body>
</html>
