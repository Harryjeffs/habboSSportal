<?php
/**
 * Created by PhpStorm.
 * User: HarryJeffs
 * Date: 31/10/2016
 * Time: 9:47 AM
 */

include_once('models/config.php');
$title = "View Forum";

?>

<!doctype html>
<!--suppress HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget, HtmlUnknownTarget -->
<html>
    <head>
        <?php
            include 'models/header.inc.php';
        ?>
        <link rel="stylesheet" href="assets/css/forum.css">
    </head>

    <body class="entire_PAGE">
            <a href="javascript:" id="return-to-top"><img src="../assets/img/to-top-inc.png" alt="Return to top"></a>
            <?php include ("inc/top-banner.php");?>

            <div class="header">
                <div class="header-title container">
                    <h1 class="header-titletext">Where work happens.</h1>
                    <p class="header-titlep">Introducing the new forum, custom built and designed for the Secret Service. Partake in discussions and conversations with fellow staff.</p>
                </div>
            </div>

            <!--include the forum_view.php file here-->
            <div id="forumMainView"></div>

        <?php include 'models/footer.inc.php'; ?>
    </body>
</html>

