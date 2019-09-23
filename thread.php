<?php


include_once('models/config.php');
$thread_id = $_GET['id'];


if(empty($thread_id) or !is_numeric($thread_id)){ header("Location: ".FULL_PATH."/index.php");}

$lmao = $mysqli->query("SELECT forum_thread.sub_category_id, user_id, thread_id, locked, topic_header, pinned, timestamp FROM `forum_thread` INNER JOIN forum_sub_category ON forum_thread.sub_category_id = forum_sub_category.sub_category_id WHERE forum_thread.thread_id = $thread_id");
$v1 = $lmao->fetch_array();

$sub_cat_id = $v1['sub_category_id'];

$thread_id = $v1['thread_id'];
$thread_topic = $v1['topic_header'];
$title = $thread_topic;

//    if(currentForumThreadView($thread_id) == 0){
//        newForumThreadView($thread_id);
//    }a

$permQ = $mysqli->query("SELECT `permission_id` FROM `forum_sub_category` WHERE `sub_category_id` = '$sub_cat_id'");
$permR = $permQ->fetch_array();

$permissionID = $permR['permission_id'];

$divisionColour = array("#2f3133", "#ecce56", "#f1c674", "#8FCE6B", "#5c9be2", "#6bbece",);
$sub_cat_id = $v1['sub_category_id'];

$sub_cat_infoQ = $mysqli->query("SELECT sub_category_desc FROM `forum_sub_category` WHERE sub_category_id = $sub_cat_id");
$stuff = $sub_cat_infoQ->fetch_array();

?>

<!doctype html>
<!--suppress HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget -->
<html>
<head>
    <?php
    include 'models/header.inc.php';
    ?>
    <link rel="stylesheet" href="<? echo FULL_PATH; ?>./assets/css/jquery.mentiony.css">
    <style>
        .container {
            max-width: 900px

        }
    </style>
</head>
<body><?php include ("inc/top-banner.php");?>
<div class="container" style="margin-top: 100px;">
    <?php if (isDeleted($thread_id)) {
        echo'<img src="'.FULL_PATH.'./assets/img/error.gif" alt="oops? Frank says woops" align="middle">
                                    <div class="alert alert-warning">
                                       An error has occurred.
                                       Please return back to the <a href="'. FULL_PATH .'/index.php">Forum Home</a>.
                                     </div> ';
    }
    else if($loggedInUser->checkPermission(array($permissionID))) {
        ?>
        <div class="jumbo-thread"
             style="background-image: url('https://www.habbo.com/habbo-imaging/avatarimage?user=<?php echo getUsername($v1['user_id'], "forum_replys"); ?>&amp;action=wav&amp;direction=4&amp;head_direction=&amp;gesture=sml&amp;size=l&amp;img_format=gif')">
            <div class="jumbotron">
                <h1><?php echo $thread_topic; ?></h1>
                <p>Posted <span data-livestamp="<? echo $v1['timestamp']; ?>"></span> by <a href="#"
                                                                                            style="color: rgba(0, 0, 0, 0.5)"><?php echo getDisplayName($v1['user_id'], "forum_replys"); ?></a>
                </p>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li><a href="<? echo FULL_PATH ?>/index.php">Forum Home</a></li>
                        <li class="sub_cat_name"><a
                                href="<? echo FULL_PATH ?>/category/<?php echo $sub_cat_id; ?>"><?php echo $stuff['sub_category_desc']; ?></a>
                        </li>
                        <li class="active currentThreadTitle"><?php echo $thread_topic; ?></li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="blankspace"></div>

        <div class="loading-div"><img src="<?php echo FULL_PATH;?>./assets/img/ajax-loader.gif" style="margin-top: 20%; margin-left: 50%;"></div>
        <div id="results"></div>

        <?
        if ($v1['locked'] != 1) {
            if ($disabled->num_rows > 0 and $disable->disable_type == 2 or $disable->disable_type == 5) {
                if ($disable->disable_type == 2 or $disable->disable_type == 5) {
                    echo "<div class='alert alert-info'>
                                               <b> Your thread replying privileges have been temporarily disabled by an administrator.</b>
                                             </div>";
                }
            } else {
                echo '
                                <div class="panel panel-default panel-thread-reply">
                                      <div class="panel-body">
                                        <form id="formoid" action="" title="" method="post" novalidate>
                                            <input type="hidden" name="threadID" id="threadID" value="' . $v1['thread_id'] . '">
                                            <textarea style="min-height: 150px; display: none;" id="threadReplyContent" name="threadReplyContent" required></textarea>
                                            <button type="submit" class="btn btn-success btn-block" id="register" name="threadBtn" style="border-radius: 0px; border-bottom-left-radius: 3px; border-bottom-right-radius: 3px">Submit Reply</button>
                                        </form>
                                    </div>
                                </div>
                            ';
            }
        } else {
            echo "
                                        <div class='alert alert-warning panel-thread-reply'>
                                            This thread has been locked. Further discussion is no longer available.      
                                        </div>             
                                   ";
        }
    }else{
        echo "<div class='alert alert-warning'>
                                You do not have permission to access this thread.
                           </div>";
    }

    ?>
</div>


<!-- include all the modals here  -->

<? include 'inc/modals/user-options.php'; ?>
<? include 'inc/modals/edit-post.php'; ?>
<? include 'inc/modals/title-modal.php'; ?>
<? include 'inc/modals/admin-options.php'; ?>
<?php if($loggedInUser->checkPermission(array(2))) {?>
    <? include 'inc/modals/user-profile.php'; ?>
<?php  } ?>
<!-- End including modals -->

<?php include 'models/footer.inc.php'; ?>
<script src="<? echo FULL_PATH; ?>./assets/js/tooltipster/tooltipster.bundle.min.js"></script>
<script src="<?php echo FULL_PATH; ?>./assets/js/mentions/jquery.mentiony.min.js"></script>

<script>
    thread_title = "<?php echo $thread_topic; ?>";
    thread_id = <?php echo $thread_id; ?>;
    current_page = 1;

    $(document).ready(function() {
        var wbbOpt = {
            buttons: "bold,italic,underline,strike,|,img,imgleft,imgright,video,link,smileList,smilebox,|,bullist,numlist,|,fontcolor,|,justifyleft,justifycenter,justifyright,|,removeFormat"
        }
        $("#threadReplyContent").wysibb(wbbOpt);
        /*
         *
         * FUNCTION THAT UPDATES PAGINATION WHEN CLICKED
         *
         */
        $(document).on('mouseenter', '.user-tooltip:not(.tooltipstered)', function () {
            var user = $(this).data("user");

            $(this).tooltipster('open', {
                content: 'Loading...',
                contentAsHTML: true,
                interactive: true,
                theme: "tooltipster-borderless",
                functionBefore: function (instance, helper) {
                    var $origin = $(helper.origin);
                    if ($origin.data('loaded') !== true) {
                        $.get(base_url + '/inc/user-tooltip.php', {username: user}, function (data) {
                            instance.content(data);
                            $origin.data('loaded', true);
                        });
                    }
                }
            });
        });
        fileName = location.pathname.split("/");
            if (fileName[4]) {
                page = fileName[4];
            } else {
                page = 1;
            }
            $(".loading-div").hide();
            $("#results").load(base_url + "/inc/pages/thread-view.php", {
                id: thread_id,
                page: page
            }, function (){
                $('[data-toggle="popover"]').popover({html: true});
                $('[data-toggle="tooltip"]').tooltip();
            });
    });
</script>
</body>
</html>
