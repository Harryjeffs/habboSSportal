<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 15/06/2017
 * Time: 9:25 PM
 */
?>
<div id = "edit-modal" class="modal fade" tabindex = "-1" role = "dialog" aria - labelledby = "myModalLabel" aria - hidden = "true" style = "display: none;" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" >

            <div class="modal-header" >
                <button type = "button" class="close" data - dismiss = "modal" aria - hidden = "true" > ×</button >
                <h4 class="modal-title" >
                    <i class="ion ion-edit"></i> Edit your post
                </h4 >
            </div >

            <div class="modal-body" >
                <div id = "modal-loader" style = "display: none; text-align: center;" >
                    <!--ajax loader-->
                    <img src = "./assets/img/ajax-loader.gif" >
                </div >
                <!--mysql data will be load here-->
                <div class="edit-data"></div >
            </div >

        </div >
    </div >
</div >
