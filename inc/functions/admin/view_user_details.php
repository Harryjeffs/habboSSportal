<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 14/01/2017
 * Time: 3:10 PM
 */

require_once("../../../models/config.php");
//Prevent the user visiting the logged in page if he is not logged in
if(!isUserLoggedIn()) { header("Location: /index.php"); die(); }

 if (isset($_POST['id'])) {

     $user_id = intval($_POST['id']);

     $stmt = $mysqli->query("SELECT * FROM portalusers WHERE id = $user_id");


     $row = $stmt->fetch_object();


     ?>

     <div class="table-responsive">

         <table class="table table-striped table-bordered">
             <tr>
                 <th>Username</th>
                 <td><?php echo $row->user_name; ?></td>
             </tr>
             <tr>
                 <th>User's total posts</th>
                 <td><?php echo totalUserPosts($row->id); ?></td>
             </tr>
             <tr>
                 <th>Most Recent Post</th>
                 <td><p>
                        <?php echo mostRecentThread($row->id); ?>
                     </p>
                 </td>
             </tr>
             <tr>
                 <th>Office</th>
                 <td><?php echo 1; ?></td>
             </tr>
         </table>

     </div>
     <div class="modal-footer">

         <div class="dropdown">
             <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown">Suspend User
                 <span class="caret"></span></button>
             <ul class="dropdown-menu">
                 <li><a href="#">HTML</a></li>
                 <li><a href="#">CSS</a></li>
                 <li><a href="#">JavaScript</a></li>
             </ul>
         </div>
     </div>

     <?php
 }
