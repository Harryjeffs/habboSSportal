<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 19/03/2017
 * Time: 3:26 PM
 */

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
//Prevent the user visiting the logged in page if he/she is already logged in
if(isUserLoggedIn()) { header("Location: /dashboard/"); die(); }

$title = "Log In";

//Forms posted
if(!empty($_POST))
{
    $errors = array();
    $username = sanitize(trim($_POST["username"]));
    $password = trim($_POST["password"]);
    $remember_choice = $_POST['remember_me'];

    //Perform some validation
    //Feel free to edit / change as required
    if($username == "")
    {
        $errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
    }
    if($password == "")
    {
        $errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
    }
    if(count($errors) == 0)
    {
        //A security note here, never tell the user which credential was incorrect
        if(!usernameExists($username))
        {
            $errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID2");

            $action = "Failed login attempt for user $username.";
            userLoginLogs($action, $title, $username);

        }
        else
        {
            $userdetails = fetchUserDetails($username);
            //See if the user's account is activated
            if($userdetails["active"] == 0)
            {
                $action = "Failed login attempt for user $username because their account is inactive.";

                userLoginLogs($action, $title, $username);

                $errors[] = lang("ACCOUNT_INACTIVE");

            }else if($userdetails['deleted'] == 1){
                $errors[] = lang("ACCOUNT_DELETED");
            }
            else
            {


                if (password_verify($password, $userdetails['password'])) {

                    //update a users IP
                    updateIp($username);



                    //Construct a new logged in user object
                    //Transfer some db data to the session object
                    $loggedInUser = new loggedInUser();
                    $loggedInUser->email = $userdetails["email"];
                    $loggedInUser->user_id = $userdetails["id"];
                    $loggedInUser->hash_pw = $userdetails["password"];
                    $loggedInUser->remember_me = $remember_choice;
                    $loggedInUser->remember_me_sessid = generateHash(uniqid(rand(), true));
                    $loggedInUser->title = $userdetails["title"];
                    $loggedInUser->username = $userdetails["user_name"];
                    $loggedInUser->rank = $userdetails["rank"];
                    $loggedInUser->display_name = $userdetails["display_name"];
                    $loggedInUser->tag = $userdetails["promo_tag"];
                    $loggedInUser->hex = $userdetails["hex"];

                    $action = "Successfully Logged In with the ip: ".ip()."";

                    userLogs($action, $title);



                    //Update last sign in
                    $loggedInUser->updateLastSignIn();
                    if($loggedInUser->remember_me == 0)
                    {
                        $time = time();

                        $serialized_loggedInUser = serialize($loggedInUser);
                        $remember_me_sessid = $loggedInUser->remember_me_sessid;

                        $_SESSION["portalUser"] = $loggedInUser;

                        $stmt = $mysqli->prepare("INSERT INTO `portalsessions`(`sessionStart`, `sessionData`, `username`, `lastActive`, `sessionID`, `remember_me`) VALUES(?, ?, ?, ?, ?, 2)");
                        $stmt->bind_param("issis", $time, $serialized_loggedInUser, $userdetails['user_name'], $time, $remember_me_sessid);
                        $stmt->execute();
                        $stmt->close();
                    }
                    else if($loggedInUser->remember_me == 1)
                    {
                        updateSessionObj();
                        $time = time();

                        $serialized_loggedInUser = serialize($loggedInUser);
                        $remember_me_sessid = $loggedInUser->remember_me_sessid;

                        $stmt = $mysqli->prepare("INSERT INTO `portalsessions`(`sessionStart`, `sessionData`, `username`, `lastActive`, `sessionID`, `remember_me`) VALUES(?, ?, ?, ?, ?, 1)");
                        $stmt->bind_param("issis", $time, $serialized_loggedInUser, $userdetails['user_name'], $time, $remember_me_sessid);
                        $stmt->execute();
                        $stmt->close();

                        setcookie("portalUser", $loggedInUser->remember_me_sessid, time()+parseLength($remember_me_length));

                    }

                    //Redirect to user account page
                    header("Location: /index/");
                    die();
                } else {
                    //Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
                    $errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");

                    $action = "Failed login attempt for user $username.";
                    userLoginLogs($action, $title, $username);
                }
            }
        }
    }
}
?>
<html lang="en">
<?php include('models/header.inc.php');?>
<link rel="stylesheet" href="./assets/css/login.css">
<link rel="stylesheet" href="./assets/css/index.css">

<center>
    <!-- center is a terrible practice, but i'm just too tired to do it another way -->
    <div class="login-panel" style="padding: 10px; border-radius: 5px 5px 0px 0px; max-width:500px; margin-top: 20px;">
        <div class="section-center ">
            <h2><img src="assets/img/mainimg.png" style="
    -webkit-filter: grayscale(50%);
    filter: grayscale(50%);
    transition: 0.5s;"><br>Greetings,</h2>
            <center><p style="font-size: 15px">Please login or create an account to proceed.<br><span style="  font-size: 13px;
  color: #adadad !important; margin-top: 15px;"> By signing in or requesting an account,<br> you accept our <a href="#" target='_blank'>Terms of Service</a> and <a href="#" target='_blank'>Privacy Policy</a></span>.</p></center>
            <br>
            <form method="post" action="index.php">

                <input name="username" type="text" placeholder="Username"><Br>
                <input name="password" type="password" placeholder="Password">
                <div>
                    <button type="submit" class="btn" style="width: 350px;">Enter Dashboard</button><br>
                    <div class="checkbox pull-left" style="padding-left: 80px;">
                        <label><input type="checkbox" name="remember_me" value="1">Remember me?</label>
                    </div>
                </div>
            </form>
            <?php
            echo resultBlock($errors,$successes);
            ?>
            <footer class="section-footer">
                <p class="copyright-info">© 2017 HabboSS Forum <span style="padding-left: 8px; padding-right: 8px;">•</span> <a href="#" class="copyright-info--about">About & Developers</a></p>
            </footer>
        </div>
    </div>
    <a href="register/"><div class="register-panel">Don't have an account? Sign Up </div></a>

    <?php include('models/footer.inc.php'); ?>
