<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 15/06/2017
 * Time: 9:16 PM
 */
$pinned = $mysqli->query("SELECT pinned from forum_thread WHERE thread_id = $thread_id")->fetch_object()->pinned;
$locked = $mysqli->query("SELECT locked from forum_thread WHERE thread_id = $thread_id")->fetch_object()->locked;
?>
<div class="modal fade" id="threadManage" tabindex="-1" role="dialog" aria-labelledby="threadManageLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 10px">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="threadManageLabel">Manage the thread "<?php echo $thread_topic; ?>"</h4>
            </div>
            <div class="modal-body">
                    <div class="option-box">
                        <input id="pin" value="1" name="pinned" style="    margin-right: 6px;" type="checkbox"  <? if($pinned == 1){?>checked<?}?>>
                        <label for="cbox2">Pin Thread</label>
                        <p class="option-description">Stick thread on forum list <!-- cant think of better desc. atm --></p>
                    </div>
                    <div class="option-box" style="padding-top: 8px;">
                        <input id="lock" value="1" name="locked" style="    margin-right: 6px;" type="checkbox" <? if($locked == 1){?>checked<?}?>>
                        <label for="cbox2">Lock Discussion</label>
                        <p class="option-description">Prohibit further discussion on this thread</p>
                    </div>
                    <div class="option-box" style="padding-top: 8px;">
                        <i class="ion-locked" style="color: rgba(0, 0, 0, 0.5)"></i>
                        <label for="cbox2" style="margin-left: 9px;">Relocate Thread</label>
                        <p class="option-description">Move thread to another category</p>
                        <?php
                        $moveq = $mysqli->query("SELECT * FROM `forum_category`
        INNER JOIN `forum_sub_category`
        ON forum_category.category_id = forum_sub_category.category_id
        INNER JOIN portaluser_permission_matches
        ON portaluser_permission_matches.permission_id = forum_sub_category.permission_id
        WHERE portaluser_permission_matches.user_id = '$loggedInUser->user_id'
        ORDER BY forum_category.display_order, forum_sub_category.display_order ASC");
                        echo"<select class='form-control manage-dropdown' name='moveThreadID' id='moveThread'>";
                        $current_subcategory = "";
                        while ($moveThread = $moveq->fetch_array()){

                            if ($moveThread["category_desc"] != $current_subcategory) {
                                if ($current_subcategory != "") {
                                    echo "</optgroup>";
                                }
                                echo '<optgroup label="'.$moveThread['category_desc'].'">';
                                $current_subcategory = $moveThread['category_desc'];
                            }
                            if($moveThread['sub_category_id'] == $sub_cat_id){
                                echo '<option value="&nbsp;'.$moveThread['sub_category_id'].'" disabled selected>'.$moveThread['sub_category_desc'].'</option>';

                            }else{
                                echo '<option value="&nbsp;'.$moveThread['sub_category_id'].'">'.$moveThread['sub_category_desc'].'</option>';

                            }
                        }

                        echo"</select>";

                        ?>
                    </div>
                    <div class="option-box" style="border: none; padding-top: 8px;">
                        <i class="ion-trash-b" style="color: rgba(0, 0, 0, 0.5); font-size: 17px;"></i>
                        <label for="cbox2" style="margin-left: 9px;">Delete Thread</label>
                        <p class="option-description">Permanently delete this thread</p>
                        <button class="btn btn-sm btn-danger btn-option" type="button" style="color: #fff;
    background-color: #da524e;
    border-color: #da524e;" id="delete">Permanently Delete</button
                    </div>
            </div>
        </div>
    </div>
</div>
</div>

