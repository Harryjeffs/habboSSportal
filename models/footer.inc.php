<!-- Javascript -->
<!--Jquery min file-->
<script src="<?php echo FULL_PATH; ?>/assets/js/jquery.js"></script>
<!--Bootstrap-->
<script src="<?php echo FULL_PATH; ?>/assets/js/bootstrap.min.js"></script>
<!--PLUGIN: Moment-->
<script src="<?php echo FULL_PATH; ?>/assets/js/moment.js"></script>
<!--PLUGIN: Moment timezone-->
<script src="<?php echo FULL_PATH; ?>/assets/js/moment.tz.min.js"></script>
<!--PLUGIN: Pnotify - Base notification system-->
<script src="<?php echo FULL_PATH; ?>/assets/js/pnotify.custom.min.js"></script>
<!--PLUGIN: Livestamp - Used for updating time since-->
<script src="<?php echo FULL_PATH; ?>/assets/js/livetimestamp.js"></script>
<!--PLUGIN: wysibb editor - used for the bbcode input of users-->
<script src="<?php echo FULL_PATH; ?>/assets/wysibb/jquery.wysibb.min.js"></script>
<!--Base forum js - All ajax & event listeners-->
<script src="<?php echo FULL_PATH; ?>/assets/js/forum.js?id=<? echo time(); ?>"></script>
<!--PLUGIN: Good Analytics - Tracks user movement-->
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-81714943-1', 'auto');
    ga('send', 'pageview');

    </script>
<?php $notificationsCount = $mysqli->query("SELECT * FROM `forum_notification_new` INNER JOIN forum_notifications_recipients ON forum_notification_new.notification_id = forum_notifications_recipients.notification_id WHERE forum_notifications_recipients.user_id = $loggedInUser->user_id  AND forum_notifications_recipients.message_read = 0")->num_rows;
?>
<!--VARIABLES: Base variables used for inputs  -->
<script>
        base_url = "<?php echo FULL_PATH; ?>";
        loggedInUserUsername = "<?php echo $loggedInUser->username; ?>";
        loggedInUserId = <?php echo $loggedInUser->user_id; ?>;
        current_notification_count = <?php echo $notificationsCount; ?>;
</script>
