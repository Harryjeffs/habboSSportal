<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 01/01/2017
 * Time: 7:51 PM
 */

    include("../models/config.php");
    $notifications = $mysqli->query("
SELECT * FROM `forum_notification_new` INNER JOIN forum_notifications_recipients ON forum_notification_new.notification_id = forum_notifications_recipients.notification_id WHERE forum_notifications_recipients.user_id = $loggedInUser->user_id  AND forum_notifications_recipients.message_read = 0");
    $notificationsRead = $mysqli->query("SELECT * FROM `forum_notification_new` INNER JOIN forum_notifications_recipients ON forum_notification_new.notification_id = forum_notifications_recipients.notification_id WHERE forum_notifications_recipients.user_id = $loggedInUser->user_id  AND forum_notifications_recipients.message_read = 1");
    $notificationCount = $notifications->num_rows;
//    $mysqli->query("DELETE FROM forum_notifications WHERE timestamp < UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL 30 DAY)))");
//    $mysqli->query("DELETE FROM forum_notifications_recipients WHERE notification_id NOT IN (SELECT notification_id FROM forum_notifications)");
?>
<span class="badge badge-alert" style="font-style: normal !important"><?php echo $notificationCount; ?></span>


<div class="modal-body">
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active fade in" id="unread" role="tabpanel">
            <div id="inner-right">
                <ul class="alerts">
                    <?php
                    if ($notifications->num_rows > 0) {
                        while ($msg = $notifications->fetch_array(MYSQLI_ASSOC)) {
                            ?>
                            <li data-notification-id="<?php echo $msg['notification_id']; ?>" class="notification-list">
                                <div class="alert-box">
                                    <div class="alert-box--Container">
                                        <?php echo notificationBuilder($msg['username'], $msg['type'], $msg['thread'], $msg['href']) ?>
                                    </div>
                                </div>
                            </li>
                            <?php
                        }
                    }else{
                        ?>
                    <li>
                        <div class="alert-box">
                            <div class="alert-box--Container">
                                No unread notifications.
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-read" type="button" id="buttonNotification"
                <?php
                    if ($notifications->num_rows == 0)echo"disabled='disabled'";
                ?>
                >Mark all alerts as read</button>
            </div>
        </div>
        <div class="tab-pane fade" id="read" role="tabpanel">
            <div id="inner-right">
                <div class="alert alert-danger">Note, all read notifications are automatically deleted after 30 days.</div>
                <ul class="alerts">
                    <?php
                    if ($notificationsRead->num_rows > 0) {

                        while ($msg = $notificationsRead->fetch_array(MYSQLI_ASSOC)) {

                            ?>
                            <li>
                                <div class="alert-box">
                                    <div class="alert-box--Container">
                                        <?php echo notificationBuilder($msg['username'], $msg['type'], $msg['thread'], $msg['href']) ?>
                                    </div>
                                </div>
                            </li>
                            <?php
                        }
                    }else{
                    ?>
                    <li>
                        <div class="alert-box">
                            <div class="alert-box--Container">
                                No read notifications.
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                </ul>
                <!--  /ul.alerts-->
            </div>
            <!-- /.inner-right-->
        </div>
        <!--  /.tab-pane-->
    </div>
    <!--  /.tab-content-->
</div>
<!--/.modal-body-->
