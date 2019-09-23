<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 15/06/2017
 * Time: 9:36 PM
 */

    $titleDisabled = $mysqli->query("SELECT `titled_change` FROM `forum_changes` WHERE `thread_id` = $thread_id")->num_rows;
?>
<div id = "title-edit-modal" class="modal fade" tabindex="-1" role="dialog" aria - labelledby = "myModalLabel" aria - hidden = "true" style = "display: none;" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content" >
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" >
                    <i class="ion ion-edit"></i> Edit thread title
                </h4>
            </div>

            <div class="modal-body" >
                <?php
                    if ($titleDisabled == 0){
                        ?>
                        <div class="form-group">
                            <label for="threadTitle">Thread Title: </label>
                            <input type="text" id="threadTitle" class="form-control" value="<?php echo $thread_topic; ?>">
                        </div>

                        <div class="warning" style="color: red; font-weight: bold;">
                            Please be aware that you can only edit the thread title once.
                        </div>
                        <div class="modal-footer">
                            <button class="submit btn btn-primary" type="button" id="editTitleBtn">
                                Edit
                            </button>
                        </div>
                        <?
                    }else{
                        echo'
                        <div class="form-group">
                            <label for="threadTitle">Thread Title: </label>
                            <input type="text" class="form-control" value="'. $thread_topic .'" disabled="disabled">
                        </div>
                        <div class="warning" style="color: red; font-weight: bold; padding: 10px; text-align: center;">
                            You can no longer edit the title for this thread.
                         </div>
                        ';
                    }
                ?>
            </div >
        </div >
    </div >
</div >
