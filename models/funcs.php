<?php


//Functions that do not interact with DB
//------------------------------------------------------------------------------

//Retrieve a list of all / files in models/languages
function getLanguageFiles()
{
	$directory = "../models/languages/";
	$languages = glob($directory . "en.php");
	//print each file name
	return $languages;
}

//Retrieve a list of all .css files in models/site-templates
function getTemplateFiles()
{
	$directory = "models/site-templates/";
	$languages = glob($directory . "*.css");
	//print each file name
	return $languages;
}

//Retrieve a list of all / files in root files folder
function getPageFiles()
{
	$directory = "";
	$pages = glob($directory . "*.php");
	//print each file name
	foreach ($pages as $page){
		$row[$page] = $page;
	}
	return $row;
}

//Destroys a session as part of logout
function destroySession($name)
{
	global $remember_me_length,$loggedInUser,$mysqli,$db_table_prefix;

	if($loggedInUser->remember_me == 0) {
		if(isset($_SESSION[$name]))
		{
			$_SESSION[$name] = NULL;
			unset($_SESSION[$name]);
			$loggedInUser = NULL;
		}
	}
	else if($loggedInUser->remember_me == 1) {
		if(isset($_COOKIE[$name]))
		{
			$stmt = $mysqli->prepare("DELETE FROM ".$db_table_prefix."sessions WHERE `sessionID` = ? and `remember_me` = 1");
			$stmt->bind_param("s", $loggedInUser->remember_me_sessid);
			$stmt->execute();
			$stmt->close();

			setcookie($name, "", time() - parseLength($remember_me_length));

			$loggedInUser = NULL;
		}
	}
}

//Generate a unique code
function getUniqueCode($length = "")
{
	$code = md5(uniqid(rand(), true));
	if ($length != "") return substr($code, 0, $length);
	else return $code;
}

//Generate an activation key
function generateActivationToken($gen = null)
{
	do
	{
		$gen = md5(uniqid(mt_rand(), false));
	}
	while(validateActivationToken($gen));
	return $gen;
}

function generateHash($plainText, $salt = null)
{
	if ($salt === null)
	{

			$salt = password_hash($plainText, PASSWORD_BCRYPT);
	}
	else
	{
		$salt = substr($salt, 0, 32);
	}

	return $salt;
}
function generateHash2($plainText, $salt = null)
{
	if ($salt === null)
	{
		$salt = substr(md5(uniqid(rand(), true)), 0, 32);
	}
	else
	{
		$salt = substr($salt, 0, 32);
	}

	return $salt . sha1($salt . $plainText);
}
//Checks if an email is valid
function isValidEmail($email)
{
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	}
	else {
		return false;
	}
}

//Inputs language strings from selected language.
function lang($key,$markers = NULL)
{
	global $lang;
	if($markers == NULL)
	{
		$str = $lang[$key];
	}
	else
	{
		//Replace any dyamic markers
		$str = $lang[$key];
		$iteration = 1;
		foreach($markers as $marker)
		{
			$str = str_replace("%m".$iteration."%",$marker,$str);
			$iteration++;
		}
	}
	//Ensure we have something to return
	if($str == "")
	{
		return ("No language key found");
	}
	else
	{
		return $str;
	}
}

//Checks if a string is within a min and max length
function minMaxRange($min, $max, $what)
{
	if(strlen(trim($what)) < $min)
		return true;
	else if(strlen(trim($what)) > $max)
		return true;
	else
		return false;
}

//Replaces hooks with specified text
function replaceDefaultHook($str)
{
	global $default_hooks,$default_replace;
	return (str_replace($default_hooks,$default_replace,$str));
}

//Displays error and success messages
function resultBlock($errors,$successes){
	//Error block
	if(count($errors) > 0)
	{
		foreach($errors as $error)
		{
			echo "<script>
			$(document).ready(function(){
				new PNotify({
					title: 'Oops',
					text: '".$error."',
					type: 'error'
				});
			});
			</script>";
		}



	}
	//Success block
	if(count($successes) > 0)
	{
		foreach($successes as $success)
		{
			echo "<script>
			$(document).ready(function(){
				new PNotify({
							title: 'Nice',
							text: '".$success."',
							type: 'success'
				});
			});
			</script>";		}

	}
}

//Completely sanitizes text
function sanitize($str)
{
	return strip_tags(trim(($str)));
}

//Functions that interact mainly with .users table
//------------------------------------------------------------------------------

//Delete a defined array of users
function deleteUsers($users) {
	global $mysqli,$db_table_prefix;
	$i = 0;
	$stmt = $mysqli->prepare("UPDATE portalusers 
		SET deleted = 1 
		WHERE id = ?");

	$stmt2 = $mysqli->prepare("DELETE FROM ".$db_table_prefix."user_permission_matches 
		WHERE user_id = ?");
	foreach($users as $id){
		$stmt2->bind_param("i", $id);
		$stmt2->execute();
		$i++;
	}
    foreach($users as $id){
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }


	$stmt->close();
	$stmt2->close();
	return $i;
}

//Check if a display name exists in the DB
function displayNameExists($displayname)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT active
		FROM ".$db_table_prefix."users
		WHERE
		display_name = ?
		LIMIT 1");
	$stmt->bind_param("s", $displayname);
	$stmt->execute();
	$stmt->store_result();
	$num_returns = $stmt->num_rows;
	$stmt->close();

	if ($num_returns > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//Check if an email exists in the DB
function emailExists($email)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT active
		FROM ".$db_table_prefix."users
		WHERE
		email = ?
		LIMIT 1");
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$stmt->store_result();
	$num_returns = $stmt->num_rows;
	$stmt->close();

	if ($num_returns > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//Check if a user name and email belong to the same user
function emailUsernameLinked($email,$username)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT active
		FROM ".$db_table_prefix."users
		WHERE user_name = ?
		AND
		email = ?
		LIMIT 1
		");
	$stmt->bind_param("ss", $username, $email);
	$stmt->execute();
	$stmt->store_result();
	$num_returns = $stmt->num_rows;
	$stmt->close();

	if ($num_returns > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//Gets the userinfo from Habbo API
function habbo( $name, $hotel ) {

    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_URL, "https://www.habbo." . $hotel . "/api/public/users" );
    curl_setopt( $ch, CURLOPT_HEADER, false );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Accept-Encoding: gzip, deflate, sdch' ) );
    curl_setopt( $ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );

    $get = gzinflate( substr( curl_exec( $ch ), 10, -8 ) );
    preg_match( "/setCookie\((.*)\);/", $get, $get );
    $get = explode( ",", str_replace( array( "'", " " ), "", $get[1] ) );

    curl_setopt( $ch, CURLOPT_HTTPHEADER, array( "Cookie:" . $get[0] . "=" . $get[1] ) );
    curl_setopt( $ch, CURLOPT_URL, "http://www.habbo." . $hotel . "/api/public/users?name=" . $name );

    $id = json_decode( curl_exec( $ch ) );

    if( isset( $id ) && $id->profileVisible == 1 ) {

        curl_setopt( $ch, CURLOPT_URL, "http://www.habbo." . $hotel . "/api/public/users/" . $id->uniqueId . "/profile" );
        $info = json_decode( curl_exec( $ch ) );

    } else
        $info = false;

    curl_close( $ch );

    return $info;

}
//Retrieve information for all users
function fetchAllUsers()
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT 
		id,
		user_name,
		display_name,
		password,
		email,
		activation_token,
		last_activation_request,
		lost_password_request,
		active,
		title,
		sign_up_stamp,
		last_sign_in_stamp,
        ip,
        updatedip,
        rank,
        promo_tag,
        hex,
        deleted
		FROM ".$db_table_prefix."users 
		WHERE deleted = 0");
	$stmt->execute();
	$stmt->bind_result($id, $user, $display, $password, $email, $token, $activationRequest, $passwordRequest, $active, $title, $signUp, $signIn, $ip, $updatedip, $rank, $promo_tag, $hex, $deleted);

	while ($stmt->fetch()){
		$row[] = array('id' => $id, 'user_name' => $user, 'display_name' => $display, 'password' => $password, 'email' => $email, 'activation_token' => $token, 'last_activation_request' => $activationRequest, 'lost_password_request' => $passwordRequest, 'active' => $active, 'title' => $title, 'sign_up_stamp' => $signUp, 'last_sign_in_stamp' => $signIn, 'ip' => $ip, 'updatedip' => $updatedip, 'rank'=> $rank, 'promo_tag'=> $promo_tag, 'hex'=> $hex, 'deleted' => $deleted);
	}
	$stmt->close();
	return ($row);
}
//Retrieve information for all users
function activateAccount()
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT 
		id,
		user_name,
		display_name,
		password,
		email,
		activation_token,
		last_activation_request,
		lost_password_request,
		active,
		title,
		sign_up_stamp,
		last_sign_in_stamp,
        ip,
        rank,
        promo_tag 
		FROM ".$db_table_prefix."users WHERE `active` = 0");
	$stmt->execute();
	$stmt->bind_result($id, $user, $display, $password, $email, $token, $activationRequest, $passwordRequest, $active, $title, $signUp, $signIn, $ip, $rank, $promo_tag);

	while ($stmt->fetch()){
		$row[] = array('id' => $id, 'user_name' => $user, 'display_name' => $display, 'password' => $password, 'email' => $email, 'activation_token' => $token, 'last_activation_request' => $activationRequest, 'lost_password_request' => $passwordRequest, 'active' => $active, 'title' => $title, 'sign_up_stamp' => $signUp, 'last_sign_in_stamp' => $signIn, 'ip' => $ip, 'rank'=> $rank, 'tag'=> $promo_tag);
	}
	$stmt->close();
	return ($row);
}
function updateAdminTag($id){

	global $mysqli;

	$tag = htmlentities($_POST['tag']);

	$sql = $mysqli->prepare("UPDATE portalusers SET `promo_tag`= ? WHERE id = ? ");

	$sql->bind_param('si', $tag,$id);
	$sql->execute();

	$sql->close();

	return $sql;
}
function updateAdminRank($rank, $Rid){
	global $mysqli;

	$sql = $mysqli->prepare("UPDATE portalusers SET rank = ? WHERE id = ? ");

	$sql->bind_param('si', $rank, $Rid);
	if($sql->execute()){
		return true;
	}else{
		return false;
	}
}
//Retrieve complete user information by username, token or ID
function fetchUserDetails($username=NULL,$token=NULL, $id=NULL)
{
	if($username!=NULL) {
		$column = "user_name";
		$data = $username;
	}
	elseif($token!=NULL) {
		$column = "activation_token";
		$data = $token;
	}
	elseif($id!=NULL) {
		$column = "id";
		$data = $id;
	}
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT 
		id,
		user_name,
		display_name,
		password,
		email,
		activation_token,
		last_activation_request,
		lost_password_request,
		active,
		title,
		sign_up_stamp,
		last_sign_in_stamp,
		ip,
		updatedip,
        rank,
        promo_tag,
        hex,
        deleted
		FROM ".$db_table_prefix."users
		WHERE
		$column = ?
		LIMIT 1");
	$stmt->bind_param("s", $data);

	$stmt->execute();
	$stmt->bind_result($id, $user, $display, $password, $email, $token, $activationRequest, $passwordRequest, $active, $title, $signUp, $signIn, $ip, $updatedip, $rank, $promo_tag, $hex, $deleted);
	while ($stmt->fetch()){
		$row = array('id' => $id, 'user_name' => $user, 'display_name' => $display, 'password' => $password, 'email' => $email, 'activation_token' => $token, 'last_activation_request' => $activationRequest, 'lost_password_request' => $passwordRequest, 'active' => $active, 'title' => $title, 'sign_up_stamp' => $signUp, 'last_sign_in_stamp' => $signIn, 'ip' => $ip,'updatedip' => $ip, 'rank'=> $rank, 'promo_tag'=> $promo_tag, 'hex' => $hex, 'deleted' => $deleted);
	}
	$stmt->close();
	return ($row);
}

//Toggle if lost password request flag on or off
function flagLostPasswordRequest($username,$value)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."users
		SET lost_password_request = ?
		WHERE
		user_name = ?
		LIMIT 1
		");
	$stmt->bind_param("ss", $value, $username);
	$result = $stmt->execute();
	$stmt->close();
	return $result;
}

//Check if a user is logged in
function isUserLoggedIn()
{
	global $loggedInUser,$mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT 
		id,
		password
		FROM ".$db_table_prefix."users
		WHERE
		id = ?
		AND 
		password = ? 
		AND
		active = 1
		LIMIT 1");
	$stmt->bind_param("is", $loggedInUser->user_id, $loggedInUser->hash_pw);
	$stmt->execute();
	$stmt->store_result();
	$num_returns = $stmt->num_rows;
	$stmt->close();

	if($loggedInUser == NULL)
	{
		return false;
	}
	else
	{
		if ($num_returns > 0)
		{
			return true;
		}
		else
		{
			destroySession("portalUser");
			return false;
		}
	}
}
function updateSessionObj()
{
	global $loggedInUser,$mysqli,$db_table_prefix;

	$newObj = serialize($loggedInUser);

	$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."sessions SET sessionData = ? WHERE sessionID = ?");
	$stmt->bind_param("ss", $newObj, $loggedInUser->remember_me_sessid);
	$stmt->execute();
	$stmt->close();
}

function parseLength($len) {
	$user_units = strtolower(substr($len, -2));
	$user_time = substr($len, 0, -2);
	$units = array("mi" => 60,
		"hr" => 3600,
		"dy" => 86400,
		"wk" => 604800,
		"mo" => 2592000
	);
	if(!array_key_exists($user_units, $units))
		die("Invalid unit of time.");
	else if(!is_numeric($user_time))
		die("Invalid length of time.");
	else
		return (int)$user_time*$units[$user_units];
}



//Change a user from inactive to active
function setUserActive($id)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."users
		SET active = 1
		WHERE
		id = ?
		LIMIT 1");
	$stmt->bind_param("i", $id);
	$result = $stmt->execute();
	$stmt->close();
	return $result;
}

//Change a user's display name
function updateDisplayName($id, $display)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."users
		SET display_name = ?
		WHERE
		id = ?
		LIMIT 1");
	$stmt->bind_param("si", $display, $id);
	$result = $stmt->execute();
	$stmt->close();
	return $result;
}

//Update a user's email
function updateEmail($id, $email)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."users
		SET 
		email = ?
		WHERE
		id = ?");
	$stmt->bind_param("si", $email, $id);
	$result = $stmt->execute();
	$stmt->close();
	return $result;
}

//Input new activation token, and update the time of the most recent activation request
function updateLastActivationRequest($new_activation_token,$username,$email)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."users
		SET activation_token = ?,
		last_activation_request = ?
		WHERE email = ?
		AND
		user_name = ?");
	$stmt->bind_param("ssss", $new_activation_token, time(), $email, $username);
	$result = $stmt->execute();
	$stmt->close();
	return $result;
}
function updateUserPassword($secure_pass, $id){
	global $mysqli,$db_table_prefix;

	$stmt = $mysqli->prepare("UPDATE portalusers
	SET 
	password = ?
	WHERE 
	id = ?");

	$stmt->bind_param('si', $secure_pass, $id);

	$result = $stmt->execute();
	$stmt->close();
	return $result;
}
//Generate a random password, and new token
function updatePasswordFromToken($pass,$token)
{
	global $mysqli,$db_table_prefix;
	$new_activation_token = generateActivationToken();
	$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."users
		SET password = ?,
		activation_token = ?
		WHERE
		activation_token = ?");
	$stmt->bind_param("sss", $pass, $new_activation_token, $token);
	$result = $stmt->execute();
	$stmt->close();
	return $result;
}

//Update a user's title
function updateTitle($id, $title)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."users
		SET 
		title = ?
		WHERE
		id = ?");
	$stmt->bind_param("si", $title, $id);
	$result = $stmt->execute();
	$stmt->close();
	return $result;
}

//Check if a user ID exists in the DB
function userIdExists($id)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT active
		FROM ".$db_table_prefix."users
		WHERE
		id = ?
		LIMIT 1");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->store_result();
	$num_returns = $stmt->num_rows;
	$stmt->close();

	if ($num_returns > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//Checks if a username exists in the DB
function usernameExists($username)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT active
		FROM ".$db_table_prefix."users
		WHERE
		user_name = ?
		LIMIT 1");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$stmt->store_result();
	$num_returns = $stmt->num_rows;
	$stmt->close();

	if ($num_returns > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//Check if activation token exists in DB
function validateActivationToken($token,$lostpass=NULL)
{
	global $mysqli,$db_table_prefix;
	if($lostpass == NULL)
	{
		$stmt = $mysqli->prepare("SELECT active
			FROM ".$db_table_prefix."users
			WHERE active = 0
			AND
			activation_token = ?
			LIMIT 1");
	}
	else
	{
		$stmt = $mysqli->prepare("SELECT active
			FROM ".$db_table_prefix."users
			WHERE active = 1
			AND
			activation_token = ?
			AND
			lost_password_request = 1 
			LIMIT 1");
	}
	$stmt->bind_param("s", $token);
	$stmt->execute();
	$stmt->store_result();
	$num_returns = $stmt->num_rows;
	$stmt->close();

	if ($num_returns > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//Functions that interact mainly with .permissions table
//------------------------------------------------------------------------------

//Create a permission level in DB
function createPermission($permission) {
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("INSERT INTO ".$db_table_prefix."permissions (
		name
		)
		VALUES (
		?
		)");
	$stmt->bind_param("s", $permission);
	$result = $stmt->execute();
	$stmt->close();
	return $result;
}

//Delete a permission level from the DB
function deletePermission($permission) {
	global $mysqli,$db_table_prefix,$errors;
	$i = 0;
	$stmt = $mysqli->prepare("DELETE FROM ".$db_table_prefix."permissions 
		WHERE id = ?");
	$stmt2 = $mysqli->prepare("DELETE FROM ".$db_table_prefix."user_permission_matches 
		WHERE permission_id = ?");
	$stmt3 = $mysqli->prepare("DELETE FROM ".$db_table_prefix."permission_page_matches 
		WHERE permission_id = ?");
	foreach($permission as $id){
		if ($id == 1){
			$errors[] = lang("CANNOT_DELETE_NEWUSERS");
		}
		elseif ($id == 2){
			$errors[] = lang("CANNOT_DELETE_ADMIN");
		}
		else{
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt2->bind_param("i", $id);
			$stmt2->execute();
			$stmt3->bind_param("i", $id);
			$stmt3->execute();
			$i++;
		}
	}
	$stmt->close();
	$stmt2->close();
	$stmt3->close();
	return $i;
}
//Retrieve information for all permission levels
function fetchAllForumPermissions()
{
    global $mysqli,$db_table_prefix;
    $stmt = $mysqli->prepare("SELECT `id`, `name`, `perms` FROM `portalpermissions` WHERE `perms` = 3");
    $stmt->execute();
    $stmt->bind_result($id, $name, $perms);
    while ($stmt->fetch()){
        $row[] = array('id' => $id, 'name' => $name, 'perms' => $perms);
    }
    $stmt->close();
    return ($row);
}
//Retrieve information for all permission levels
function fetchAllModPermissions()
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT `id`, `name`, `perms` FROM `portalpermissions` WHERE `perms` = 1");
	$stmt->execute();
	$stmt->bind_result($id, $name, $perms);
	while ($stmt->fetch()){
		$row[] = array('id' => $id, 'name' => $name, 'perms' => $perms);
	}
	$stmt->close();
	return ($row);
}
//Retrieve information for all permission levels
function fetchAllAdminPermissions()
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT `id`, `name`, `perms` FROM `portalpermissions`");
	$stmt->execute();
	$stmt->bind_result($id, $name, $perms);
	while ($stmt->fetch()){
		$row[] = array('id' => $id, 'name' => $name, 'perms' => $perms);
	}
	$stmt->close();
	return ($row);
}
//Retrieve information about the admin contact page
function user_contact(){
	global $mysqli;

	$admin = $mysqli->query("SELECT COUNT(Status) FROM help");

	return $admin;
}
function forumThreadlikes($thread_id) {
    global $mysqli;

    $query = $mysqli->prepare("SELECT DISTINCT `user_id` FROM forum_topic_likes WHERE thread_id = ?");

    $query->bind_param('i', $thread_id);

    $query->execute();

    $query->store_result();

    $likes = $query->num_rows;

    $query->close();
    return $likes;
}

//Retrieve information for a single permission level
function fetchPermissionDetails($id)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT 
		id,
		name
		FROM ".$db_table_prefix."permissions
		WHERE
		id = ?
		LIMIT 1");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->bind_result($id, $name);
	while ($stmt->fetch()){
		$row = array('id' => $id, 'name' => $name);
	}
	$stmt->close();
	return ($row);
}

//Check if a permission level ID exists in the DB
function permissionIdExists($id)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT id
		FROM ".$db_table_prefix."permissions
		WHERE
		id = ?
		LIMIT 1");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->store_result();
	$num_returns = $stmt->num_rows;
	$stmt->close();

	if ($num_returns > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//Check if a permission level name exists in the DB
function permissionNameExists($permission)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT id
		FROM ".$db_table_prefix."permissions
		WHERE
		name = ?
		LIMIT 1");
	$stmt->bind_param("s", $permission);
	$stmt->execute();
	$stmt->store_result();
	$num_returns = $stmt->num_rows;
	$stmt->close();

	if ($num_returns > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//Change a permission level's name
function updatePermissionName($id, $name)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."permissions
		SET name = ?
		WHERE
		id = ?
		LIMIT 1");
	$stmt->bind_param("si", $name, $id);
	$result = $stmt->execute();
	$stmt->close();
	return $result;
}

//Functions that interact mainly with .user_permission_matches table
//------------------------------------------------------------------------------

//Match permission level(s) with user(s)
function addPermission($permission, $user) {
	global $mysqli,$db_table_prefix;
	$i = 0;
	$stmt = $mysqli->prepare("INSERT INTO ".$db_table_prefix."user_permission_matches (
		permission_id,
		user_id
		)
		VALUES (
		?,
		?
		)");
	if (is_array($permission)){
		foreach($permission as $id){
			$stmt->bind_param("ii", $id, $user);
			$stmt->execute();
			$i++;
		}
	}
	elseif (is_array($user)){
		foreach($user as $id){
			$stmt->bind_param("ii", $permission, $id);
			$stmt->execute();
			$i++;
		}
	}
	else {
		$stmt->bind_param("ii", $permission, $user);
		$stmt->execute();
		$i++;
	}
	$stmt->close();
	return $i;
}

//Retrieve information for all user/permission level matches
function fetchAllMatches()
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT 
		id,
		user_id,
		permission_id
		FROM ".$db_table_prefix."user_permission_matches");
	$stmt->execute();
	$stmt->bind_result($id, $user, $permission);
	while ($stmt->fetch()){
		$row[] = array('id' => $id, 'user_id' => $user, 'permission_id' => $permission);
	}
	$stmt->close();
	return ($row);
}

//Retrieve list of permission levels a user has
function fetchUserPermissions($user_id)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT
		id,
		permission_id
		FROM ".$db_table_prefix."user_permission_matches
		WHERE user_id = ?
		");
	$stmt->bind_param("i", $user_id);
	$stmt->execute();
	$stmt->bind_result($id, $permission);
	while ($stmt->fetch()){
		$row[$permission] = array('id' => $id, 'permission_id' => $permission);
	}
	$stmt->close();
	if (isset($row)){
		return ($row);
	}
}

//Retrieve list of users who have a permission level
function fetchPermissionUsers($permission_id)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT id, user_id
		FROM ".$db_table_prefix."user_permission_matches
		WHERE permission_id = ?
		");
	$stmt->bind_param("i", $permission_id);
	$stmt->execute();
	$stmt->bind_result($id, $user);
	while ($stmt->fetch()){
		$row[$user] = array('id' => $id, 'user_id' => $user);
	}
	$stmt->close();
	if (isset($row)){
		return ($row);
	}
}

//Unmatch permission level(s) from user(s)
function removePermission($permission, $user) {
	global $mysqli,$db_table_prefix;
	$i = 0;
	$stmt = $mysqli->prepare("DELETE FROM ".$db_table_prefix."user_permission_matches 
		WHERE permission_id = ?
		AND user_id =?");
	if (is_array($permission)){
		foreach($permission as $id){
			$stmt->bind_param("ii", $id, $user);
			$stmt->execute();
			$i++;
		}
	}
	elseif (is_array($user)){
		foreach($user as $id){
			$stmt->bind_param("ii", $permission, $id);
			$stmt->execute();
			$i++;
		}
	}
	else {
		$stmt->bind_param("ii", $permission, $user);
		$stmt->execute();
		$i++;
	}
	$stmt->close();
	return $i;
}

//Functions that interact mainly with .configuration table
//------------------------------------------------------------------------------

//Update configuration table
function updateConfig($id, $value)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."configuration
		SET 
		value = ?
		WHERE
		id = ?");
	foreach ($id as $cfg){
		$stmt->bind_param("si", $value[$cfg], $cfg);
		$stmt->execute();
	}
	$stmt->close();
}

//Functions that interact mainly with .pages table
//------------------------------------------------------------------------------

//Add a page to the DB
function createPages($pages) {
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("INSERT INTO ".$db_table_prefix."pages (
		page
		)
		VALUES (
		?
		)");
	foreach($pages as $page){
		$stmt->bind_param("s", $page);
		$stmt->execute();
	}
	$stmt->close();
}

//Delete a page from the DB
function deletePages($pages) {
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("DELETE FROM ".$db_table_prefix."pages 
		WHERE id = ?");
	$stmt2 = $mysqli->prepare("DELETE FROM ".$db_table_prefix."permission_page_matches 
		WHERE page_id = ?");
	foreach($pages as $id){
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt2->bind_param("i", $id);
		$stmt2->execute();
	}
	$stmt->close();
	$stmt2->close();
}

//Fetch information on all pages
function fetchAllPages()
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT 
		id,
		page,
		private
		FROM ".$db_table_prefix."pages");
	$stmt->execute();
	$stmt->bind_result($id, $page, $private);
	while ($stmt->fetch()){
		$row[$page] = array('id' => $id, 'page' => $page, 'private' => $private);
	}
	$stmt->close();
	if (isset($row)){
		return ($row);
	}
}

//Fetch information for a specific page
function fetchPageDetails($id)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT 
		id,
		page,
		private
		FROM ".$db_table_prefix."pages
		WHERE
		id = ?
		LIMIT 1");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->bind_result($id, $page, $private);
	while ($stmt->fetch()){
		$row = array('id' => $id, 'page' => $page, 'private' => $private);
	}
	$stmt->close();
	return ($row);
}

//Check if a page ID exists
function pageIdExists($id)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT private
		FROM ".$db_table_prefix."pages
		WHERE
		id = ?
		LIMIT 1");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->store_result();
	$num_returns = $stmt->num_rows;
	$stmt->close();

	if ($num_returns > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//Toggle private/public setting of a page
function updatePrivate($id, $private)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."pages
		SET 
		private = ?
		WHERE
		id = ?");
	$stmt->bind_param("ii", $private, $id);
	$result = $stmt->execute();
	$stmt->close();
	return $result;
}

//Functions that interact mainly with .permission_page_matches table
//------------------------------------------------------------------------------

//Match permission level(s) with page(s)
function addPage($page, $permission) {
	global $mysqli,$db_table_prefix;
	$i = 0;
	$stmt = $mysqli->prepare("INSERT INTO ".$db_table_prefix."permission_page_matches (
		permission_id,
		page_id
		)
		VALUES (
		?,
		?
		)");
	if (is_array($permission)){
		foreach($permission as $id){
			$stmt->bind_param("ii", $id, $page);
			$stmt->execute();
			$i++;
		}
	}
	elseif (is_array($page)){
		foreach($page as $id){
			$stmt->bind_param("ii", $permission, $id);
			$stmt->execute();
			$i++;
		}
	}
	else {
		$stmt->bind_param("ii", $permission, $page);
		$stmt->execute();
		$i++;
	}
	$stmt->close();
	return $i;
}

//Retrieve list of permission levels that can access a page
function fetchPagePermissions($page_id)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT
		id,
		permission_id
		FROM ".$db_table_prefix."permission_page_matches
		WHERE page_id = ?
		");
	$stmt->bind_param("i", $page_id);
	$stmt->execute();
	$stmt->bind_result($id, $permission);
	while ($stmt->fetch()){
		$row[$permission] = array('id' => $id, 'permission_id' => $permission);
	}
	$stmt->close();
	if (isset($row)){
		return ($row);
	}
}

//Retrieve list of pages that a permission level can access
function fetchPermissionPages($permission_id)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT
		id,
		page_id
		FROM ".$db_table_prefix."permission_page_matches
		WHERE permission_id = ?
		");
	$stmt->bind_param("i", $permission_id);
	$stmt->execute();
	$stmt->bind_result($id, $page);
	while ($stmt->fetch()){
		$row[$page] = array('id' => $id, 'permission_id' => $page);
	}
	$stmt->close();
	if (isset($row)){
		return ($row);
	}
}

//Unmatched permission and page
function removePage($page, $permission) {
	global $mysqli,$db_table_prefix;
	$i = 0;
	$stmt = $mysqli->prepare("DELETE FROM ".$db_table_prefix."permission_page_matches 
		WHERE page_id = ?
		AND permission_id =?");
	if (is_array($page)){
		foreach($page as $id){
			$stmt->bind_param("ii", $id, $permission);
			$stmt->execute();
			$i++;
		}
	}
	elseif (is_array($permission)){
		foreach($permission as $id){
			$stmt->bind_param("ii", $page, $id);
			$stmt->execute();
			$i++;
		}
	}
	else {
		$stmt->bind_param("ii", $permission, $user);
		$stmt->execute();
		$i++;
	}
	$stmt->close();
	return $i;
}
//Change a user's Username
function updateUserName($username, $uId)
{
	global $mysqli,$db_table_prefix;

	$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."users
		SET user_name = ?
		WHERE
		id = ?
		LIMIT 1");
	$stmt->bind_param("si", $username, $uId);

			$stmt->execute();

	return $stmt;
}
//Check if a user has access to a page
function securePage($uri){

	//Separate document name from uri
	$tokens = explode('/', $uri);
	$page = $tokens[sizeof($tokens)-1];
	global $mysqli,$db_table_prefix,$loggedInUser;
	//retrieve page details
	$stmt = $mysqli->prepare("SELECT 
		id,
		page,
		private
		FROM ".$db_table_prefix."pages
		WHERE
		page = ?
		LIMIT 1");
	$stmt->bind_param("s", $page);
	$stmt->execute();
	$stmt->bind_result($id, $page, $private);
	while ($stmt->fetch()){
		$pageDetails = array('id' => $id, 'page' => $page, 'private' => $private);
	}
	$stmt->close();
	//If page does not exist in DB, allow access
	if (empty($pageDetails)){
		return true;
	}
	//If page is public, allow access
	elseif ($pageDetails['private'] == 0) {
		return true;
	}
	//If user is not logged in, deny access
	elseif(!isUserLoggedIn())
	{
		header("Location: /index.php");
		return false;
	}
	else {
		//Retrieve list of permission levels with access to page
		$stmt = $mysqli->prepare("SELECT
			permission_id
			FROM ".$db_table_prefix."permission_page_matches
			WHERE page_id = ?
			");
		$stmt->bind_param("i", $pageDetails['id']);
		$stmt->execute();
		$stmt->bind_result($permission);
		while ($stmt->fetch()){
			$pagePermissions[] = $permission;
		}
		$stmt->close();
		//Check if user's permission levels allow access to page
		if ($loggedInUser->checkPermission($pagePermissions)){
			return true;
		}
		else {
			header("Location: /index/");
			return false;
		}
	}
}




function countPages($query){
	global $mysqli;

	$per_page = 25;

	$result = $mysqli->query($query);
	$num_rows = $result->num_rows;
	$pages = $num_rows / $per_page;

	return $pages;
}


function updateRank(){

	global $mysqli, $loggedInUser;

	$rank = $_POST['country'];
	$id = $loggedInUser->user_id;

	$sql = $mysqli->prepare("UPDATE portalusers SET rank = ? WHERE id = ? ");

	$sql->bind_param('si', $rank, $id);
	$sql->execute();

	$sql->close();

	return $sql;
}


function getUsername($user_id, $table) {
	global $mysqli;

	return $result =  $mysqli->query("SELECT user_name FROM portalusers INNER JOIN $table ON portalusers.ID = $user_id LIMIT 1")->fetch_object()->user_name;
}
function getDisplayName($user_id, $table) {
	global $mysqli;

	return $result =  $mysqli->query("SELECT display_name FROM portalusers INNER JOIN $table ON portalusers.ID = $user_id LIMIT 1")->fetch_object()->display_name;
}

function isFirstPost($timestamp, $thread_id){
    global $mysqli;

    $stmt = $mysqli->query("SELECT timestamp from forum_thread where timestamp = $timestamp and thread_id = $thread_id");

    if($stmt->num_rows > 0){
        return true;
    }else{
        return false;
    }
}

function lastSignUp($time){
	$time = time() - $time;

	return $time;
}



// Function to get the client IP address
function ip() {
	$ip = '';
	if (isset($_SERVER['HTTP_CLIENT_IP']))
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_X_FORWARDED']))
		$ip = $_SERVER['HTTP_X_FORWARDED'];
	else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
		$ip = $_SERVER['HTTP_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_FORWARDED']))
		$ip = $_SERVER['HTTP_FORWARDED'];
	else if(isset($_SERVER['REMOTE_ADDR']))
		$ip = $_SERVER['REMOTE_ADDR'];
	else
		$ip = 'UNKNOWN';
	return $ip;
}

function deleteUser($id){
	global $mysqli;
	$i = 0;
	$stmt = $mysqli->prepare("DELETE FROM portalusers 
		WHERE id = ?");

	$stmt->bind_param('i', $id);
	$stmt->execute();

	$stmt->close();
	return $i;
}
function undelete($id){
	global $mysqli;

	$stmt = $mysqli->prepare("UPDATE portalusers
 								SET `deleted` = 0
 								WHERE `id` = ?");

	$stmt->bind_param('i', $id);
	$stmt->execute();

	$stmt->close();
}

//Retrieve information for all users
function fetchAllDeletedUsers()
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT 
		id,
		user_name,
		display_name,
		password,
		email,
		activation_token,
		last_activation_request,
		lost_password_request,
		active,
		title,
		sign_up_stamp,
		last_sign_in_stamp,
        ip,
        updatedip,
        rank,
        promo_tag,
        hex,
        deleted
		FROM ".$db_table_prefix."users 
		WHERE deleted = 1");
	$stmt->execute();
	$stmt->bind_result($id, $user, $display, $password, $email, $token, $activationRequest, $passwordRequest, $active, $title, $signUp, $signIn, $ip, $updatedip, $rank, $promo_tag, $hex, $deleted);

	while ($stmt->fetch()){
		$row[] = array('id' => $id, 'user_name' => $user, 'display_name' => $display, 'password' => $password, 'email' => $email, 'activation_token' => $token, 'last_activation_request' => $activationRequest, 'lost_password_request' => $passwordRequest, 'active' => $active, 'title' => $title, 'sign_up_stamp' => $signUp, 'last_sign_in_stamp' => $signIn, 'ip' => $ip, 'updatedip' => $updatedip, 'rank'=> $rank, 'promo_tag'=> $promo_tag, 'hex'=> $hex, 'deleted' => $deleted);
	}
	$stmt->close();
	return ($row);
}



function newForumThread($sub_category_id, $header, $pinned){
	global $mysqli, $loggedInUser;

	$time = time();

	$stmt = $mysqli->prepare("
	INSERT INTO `forum_thread`
	(`user_id`, 
	 `sub_category_id`,
	  `topic_header`, 
	  `timestamp`,
	     `pinned`)
	VALUES (?,?,?,?,?)
	");

	$stmt->bind_param('iisii',$loggedInUser->user_id, $sub_category_id,$header, $time, $pinned);
	$stmt->execute();

    $thread_id = $stmt->insert_id;

	$stmt->close();

    return $thread_id;
}
function submitFormReply($thread_id,$content, $randomKey){
	global $mysqli, $loggedInUser;

    $time = time();

	$stmt = $mysqli->prepare("
	INSERT INTO `forum_replys`(
	 `thread_id`,
	  `reply_content`,
	   `user_id`,
	    `timestamp`,
	     `uniqueID`)
	 VALUES (
	 ?,
	 ?,
	 ?,
	 ?,
	 ?)");

	$stmt->bind_param('isiis', $thread_id, $content, $loggedInUser->user_id, $time, $randomKey );
	$stmt->execute();

    $stmt->store_result();

    $insert_id = $stmt->insert_id;

	$stmt->close();

    return $insert_id;
}
function sendAlert($message, $user, $time){
	global $mysqli;
	$insert_message = $mysqli->prepare("INSERT INTO messages (message, sender, timestamp) VALUES (?, ?, ?)");
	$insert_message->bind_param('sii', $message, $user, $time);

	$insert_message->execute();

	$message_id = $insert_message->insert_id;

	$insert_query = $mysqli->prepare("INSERT INTO messages_users (`message_id`, `receiver_id`, `message_read`) 
                                                SELECT ?, ID, false FROM portalusers ");

	$insert_query->bind_param('i', $message_id);

	$insert_query->execute();

	$insert_query->close();
	$insert_message->close();
}
function updateUserLastActivity(){
	global $mysqli, $loggedInUser;

	$time = time();
	$stmt = $mysqli->prepare("UPDATE portalsessions 
	SET
	`lastActive` = ?
	WHERE
	`username` = ?
	");

	$stmt->bind_param('is', $time, $loggedInUser->display_name);
	$stmt->execute();
}
function forumProgressBar($username, $count){

	switch (true) {
		case $count <= 24:
			$message = ''.$username.' is a noob';
			$percentage = 2;
			$class = 'danger';
			break;

		case $count <= 149:
			$message = ''.$username.' is still being friend zoned';
			$percentage = 10;
			$class = 'danger';
			break;

		case $count <= 249:
			$message = ''.$username.' is know to 1% of Tuvalu\'s population';
			$percentage = 15;
			$class = 'danger';

			break;


		case $count <= 349:
			$message = 'I think I know '.$username.'';
			$percentage = 35;
			$class = 'warning';

			break;
		case $count <= 449:
			$message = ''.$username.' now knows what a party is';
			$percentage = 45;
			$class = 'danger';

			break;
		case $count <= 499:
			$message = ''.$username.' is halfway there';
			$percentage = 50;
			$class = 'danger';

			break;
		case $count <= 699:
			$message = ''.$username.' loves the people here';
			$percentage = 70;
			$class = 'warning';

			break;
		case $count <= 999:
			$message = ''.$username.' is getting pretty popular';
			$percentage = 80;
			$class = 'warning';

			break;
		case $count <= 1999:
			$message = ''.$username.' has their eyes fixed on the forum 24/7';
			$percentage = 90;
			$class = 'success';

			break;
		case $count <= 2999:
			$message = ''.$username.' is addicted to the forum';
			$percentage = 95;
			$class = 'success';

			break;
		case $count > 3000:
			$message = ''.$username.' is in the top 1%';
			$percentage = 100;
			$class = 'success';

			break;
		default:
			$message = ''.$username.' is a noob';
			$percentage = 5;
			$class = 'danger';
			break;
	}
	$bar = ' <div class="progress" style="height: 10px; margin-top: 5px">
                <a href="#" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="'.$message.'">
                  <div class="progress">
                    <div class="progress-bar progress-bar-'.$class.'" role="progressbar" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percentage.'%;">
                    </div>
                  </div>
                </a>
              </div>';


	return $bar;
}
function userPermissions($id){
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT `id`, `user_id`, `permission_id`  FROM `portaluser_permission_matches` WHERE `user_id` = ?");
    $stmt->bind_param('i', $id);

    $stmt->bind_result($id, $user_id, $permission_id);
    while ($stmt->fetch()){
        $row = array('id' => $id, 'user_id' => $user_id, 'permission_id' => $permission_id);
    }
    $stmt->close();
    return ($row);
}

function truncateString($string) {
    if (strlen($string) > 17) {
        $string = substr($string, 0, 17) . "...";
    }
    return $string;
}
function calculatePage($post_num){

    $function = floor($post_num / 9) + 1;
    if($function == 1){
        return "";
    }else{
        return $function;
    }


}
function checkforumThreadView($id){
    global $mysqli, $loggedInUser;

	$stmt = $mysqli->prepare("SELECT * FROM `forum_views` WHERE `thread_id` = ? and `username` = ?");

	$stmt->bind_param('is', $id, $loggedInUser->username);
	$stmt->store_result();

    $stmt->execute();

	$counted_rows = $stmt->num_rows;
	$stmt->close();

	return $counted_rows;
}
function generateRandomString($length = 15) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function checkUniqueID($unique_id){
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT * FROM `forum_replys` WHERE `uniqueID` = ?");

    $stmt->bind_param('s', $unique_id);
    $stmt->store_result();

    $counted_rows = $stmt->num_rows;
    $stmt->close();

    return $counted_rows;
}
function forumThreadView($id){
	global $mysqli, $ip, $loggedInUser;

	$stmt = $mysqli->prepare("
	INSERT INTO forum_views (`thread_id`, `username`, `ip`) VALUES (?,?,?)
	");

	$stmt->bind_param('isi', $id, $loggedInUser->username, $ip);
	$stmt->execute();

	$stmt->close();
}
function likes($post_id) {

    global $mysqli;
    $query = $mysqli->prepare("SELECT * FROM forum_likes WHERE item_id = ? ");

    $query->bind_param('i', $post_id);
    $query->execute();

    $query->store_result();
    $num_returns = $query->num_rows;

    $query->close();

    return $num_returns;
}
function forumUserLikes($user_id){
    global $mysqli;
    $query = $mysqli->prepare("SELECT * FROM forum_likes INNER JOIN forum_thread_likes WHERE forum_likes.user_id and forum_thread_likes.user_id = ? ");

    $query->bind_param('i', $user_id);
    $query->execute();

    $query->store_result();
    $num_returns = $query->num_rows;

    $query->close();

    return $num_returns;
}

function forumPollCreate($thread_id){
    global $mysqli, $loggedInUser;

    $poll_title = htmlentities($_POST['title_title'], ENT_QUOTES);
    $poll_desc = htmlentities($_POST['title_desc'], ENT_QUOTES);
    $option_1 = htmlentities($_POST['option_1'], ENT_QUOTES);
    $option_2 = htmlentities($_POST['option_2'], ENT_QUOTES);
    $option_3 = htmlentities($_POST['option_3'], ENT_QUOTES);
    $option_4 = htmlentities($_POST['option_4'], ENT_QUOTES);
    $option_5 = htmlentities($_POST['option_5'], ENT_QUOTES);

    $stmt = $mysqli->prepare("INSERT INTO forumpoll 
                                (`thread_id`,
                                `poll_title`,
                                `poll_desc`,
                                `option_1`, 
                                `option_2`,
                                `option_3`,
                                `option_4`,
                                `option_5`,
                                `user_id`,
                                `locked`)
                               VALUES (?,?,?,?,?,?,?,?,?,?)");

    $stmt->bind_param('isssssi',$thread_id ,$poll_title, $poll_desc, $option_1, $option_2, $option_3, $option_4, $option_5, $loggedInUser->user_id);
    $stmt->execute();

    $stmt->close();
}
function forumPollVote($thread_id){
    global $mysqli, $loggedInUser;

    $option_choice = $_POST['option_choice'];
    $stmt = $mysqli->prepare("INSERT INTO forumpollanswers VALUES (?,?,?)");


    $stmt->bind_param('isi', $thread_id,$option_choice, $loggedInUser->user_id);
    $stmt->execute();

    $stmt->close();
}
function alertSingleUser($msg){
    global $mysqli, $loggedInUser;

    $sender = 1;
    $stmt = $mysqli->prepare("INSERT INTO `messages`(`message`, `sender`, `timestamp`) VALUES (?,?,?)");
    $stmt->bind_param('i',$msg, $sender, time());
}

function threadViews($id){
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT * FROM `forum_views` WHERE thread_id = ?");
    $stmt->bind_param('i', $id);

    $stmt->execute();

    $stmt->store_result();
    $threadViewCount = $stmt->num_rows;

    $stmt->close();

    return $threadViewCount;
}
function threadReplies($id){
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT * FROM `forum_replys` WHERE thread_id = ?");
    $stmt->bind_param('i', $id);

    $stmt->execute();

    $stmt->store_result();
    $threadReplyCount = $stmt->num_rows;

    $stmt->close();

    return $threadReplyCount;
}
function currentForumThreadView($id){

    global $mysqli, $loggedInUser;

    $stmt = $mysqli->prepare("SELECT * FROM `forum_views` WHERE thread_id = ? and username = ?");
    $stmt->bind_param('is', $id, $loggedInUser->username);

    $stmt->execute();

    $stmt->store_result();
    $currentForumThreadView = $stmt->num_rows;

    $stmt->close();

    return $currentForumThreadView;
}
function forumlikes($post_id){

    global $mysqli, $loggedInUser;

    $stmt = $mysqli->prepare("SELECT * FROM `forum_likes` WHERE `user_id` = ? and `post_id` = ?");
    $stmt->bind_param('ii',$loggedInUser->user_id, $post_id);

    $stmt->execute();

    $stmt->store_result();
    $currentForumThreadView = $stmt->num_rows;

    $stmt->close();

    return $currentForumThreadView;
}
function newForumThreadView($id){
    global $mysqli, $loggedInUser, $ip;

    $stmt = $mysqli->prepare("INSERT INTO `forum_views`(`thread_id`, `username`, `ip`) VALUES (?,?,?)");
    $stmt->bind_param('iss', $id, $loggedInUser->username, $ip);

    $stmt->execute();

    $stmt->close();
}
function forumRecentActivity($recent){

    if(!empty($recent['reply_content'])){
        $preview = 'RE: '.strlen($recent['topic_header']) > 50 ? substr($recent['topic_header'],0,50)."..." : $recent['topic_header'].'';
        return $preview;
    }else{
        $preview = ''.strlen($recent['topic_header']) > 50 ? substr($recent['topic_header'],0,50)."..." : $recent['topic_header'].'';
        return $preview;
    }
}

function addUserBadge($badge_id){
    global $mysqli, $loggedInUser;

    $stmt = $mysqli->prepare("INSERT INTO `portaluser_badges_usermatches`(`badge_id`, `user_id`) VALUES (?,?)");

    $stmt->bind_param('ii', $badge_id, $loggedInUser);
    $stmt->execute();

    $stmt->close();
}
function totalUserPosts($user_id){
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT user_id FROM forum_replys WHERE user_id = ?");

    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    $stmt->store_result();

    $lastPost = $stmt->num_rows;

    $stmt->close();

    return $lastPost;
}
function addBadgeName($badge_desc, $badge_url){
    global $mysqli;

    $stmt = $mysqli->prepare("INSERT INTO `portaluser_badges`(`badge_desc`, `badge_url`) VALUES (?,?)");

    $stmt->bind_param('ii', $badge_desc, $badge_url);
    $stmt->execute();

    $stmt->close();
}
function totalUserLikes($user_id){
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT reciever_user_id FROM forum_likes WHERE reciever_user_id = ?");

    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    $stmt->store_result();

    $lastPost = $stmt->num_rows;

    $stmt->close();

    return $lastPost;
}
function fetchCategoryListing($sub_cat_id){
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT 
       `thread_id`,
        `user_id`,
      `sub_category_id`,
      `topic_header`, 
      `timestamp`, 
      `locked`,
      `locked_by`,
      `pinned`
      FROM  `forum_thread` 
      WHERE sub_category_id = ?
      ORDER BY `pinned` DESC, `thread_id` DESC");

    /* Bind parameters.  */
    $stmt->bind_param('i',$sub_cat_id);

    /* Execute statement */
    $stmt->execute();

    $stmt->bind_result($thread_id, $user_id, $sub_category_id, $topic_header, $timestamp, $locked, $locked_by, $pinned);
    while ($stmt->fetch()){
        $row[] = array('thread_id' => $thread_id,'user_id' => $user_id, 'sub_category_id' => $sub_category_id,'topic_header' => $topic_header, 'timestamp' => $timestamp,'locked' => $locked,'locked_by' => $locked_by,'pinned' => $pinned);
    }

    $stmt->close();
    return ($row);
}
function christmasBadge(){
    global $mysqli, $loggedInUser;

    $badge_id = 2;
    $stmt = $mysqli->prepare("INSERT INTO `portaluser_badges_usermatches`(`badge_id`, `user_id`) VALUES (?,?)");

    $stmt->bind_param('ii', $badge_id, $loggedInUser->user_id);
    $stmt->execute();

    $stmt->close();
}
function lastPost(){
    global $mysqli, $loggedInUser;

    $stmt = $mysqli->prepare("SELECT `timestamp` from `forum_replys` WHERE user_id = ? and timestamp >= UNIX_TIMESTAMP(date_sub(now(), interval 2 minute))");

    $stmt->bind_param('i', $loggedInUser->user_id);
    $stmt->execute();

    $stmt->store_result();

    $num_rows = $stmt->num_rows;

    $stmt->close();

    if($num_rows == 0){
        return false;
    }else{
        return true;
    }}
function EditInsert($old_text, $new_text, $post_id, $thread_id){
    global $mysqli, $loggedInUser, $time;

    $stmt = $mysqli->prepare("INSERT INTO
 `forum_posts_edits`( `user_id`, `old_text`, `new_text`, `timestamp`, `post_id`, `thread_id`)
  VALUES (?,?,?,?,?,?)");

    $stmt->bind_param('issiii', $loggedInUser->user_id, $old_text, $new_text, $time, $post_id, $thread_id);
    $stmt->execute();

    $stmt->close();
}
function editPost($postContent, $post_id){
    global $mysqli;

    $stmt = $mysqli->prepare("UPDATE forum_replys SET `reply_content` = ? WHERE reply_id = ?");

    $stmt->bind_param('si', $postContent, $post_id);

    $stmt->execute();
    $stmt->close();
}
function santaBadge(){
    global $mysqli, $loggedInUser;

        $badge_id = 2;
    $stmt = $mysqli->prepare("SELECT `badge_id` from `portaluser_badges_usermatches` WHERE user_id = ? and badge_id = ?");

    $stmt->bind_param('ii', $loggedInUser->user_id, $badge_id);
    $stmt->execute();

    $stmt->store_result();

    $lastPost = $stmt->num_rows;

    $stmt->close();

    return $lastPost;
}
function newForumNotification($message){
    global $mysqli, $time;


    $query = $mysqli->prepare("INSERT INTO `forum_notifications`( `message`, `timestamp`) VALUES (?,?)");

    $query->bind_param('si', $message, $time);
    $query->execute();

    $query->store_result();

    $reciever_id = $query->insert_id;

    $query->close();
    return $reciever_id;
}
function newForumNotification2($type, $thread, $href){
    global $mysqli, $time, $loggedInUser;

    $username = $loggedInUser->username;

    $query = $mysqli->prepare("INSERT INTO `forum_notification_new`(`username`, `type`, `thread`, `href`, `time`) VALUES (?,?,?,?,?)");

    $query->bind_param('sissi', $username, $type, $thread, $href, $time);
    $query->execute();

    $query->store_result();

    $reciever_id = $query->insert_id;

    $query->close();
    return $reciever_id;
}
function forumNotificationReciever($notification_id, $user_id){
    global $mysqli;
    $stmt2 = $mysqli->prepare("INSERT INTO `forum_notifications_recipients`(`notification_id`, `user_id`) VALUES (?, ?)");

    $stmt2->bind_param('ii',$notification_id,  $user_id);
    $stmt2->execute();

     $stmt2->close();
}
function notificationBuilder($username, $type, $thread, $href){
    switch ($type){
        case 1:
            $type = "NEW_POST";
        break;
        case 2:
            $type = "LIKE_POST";
			break;
		case 3:
			$type = "MENTIONED_USER";
    }
    $href = "<a href='".FULL_PATH."/thread/$href'>$thread</a>";

    $notification = array(
        "NEW_POST"=>"%m1%  responded to your thread, %m2%",
        "LIKE_POST" => "%m1%  liked your response on, %m2%",
		"MENTIONED_USER"=>"%m1% mentioned you in a comment on, %m2%"
    );
    $alterations = array("<a href='".FULL_PATH."/user/$username'>$username</a>", $href);

    $str = $notification[$type];
    $count = 1;

    foreach ($alterations as $alteration){
        $str = str_replace("%m".$count."%", $alteration, $str);
        $count++;
    }
    return $str;
}
function get_all_string_between($string)
{
    $start = "[quote]";
    $end = "[/quote]";

    $result = array();
    $string = " ".$string;
    $offset = 0;
    while(true)
    {
        $ini = strpos($string,$start,$offset);
        if ($ini == 0)
            break;
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        $result[] = substr($string,$ini,$len);
        $offset = $ini+$len;
    }
    return $result;
}

function isDeleted($thread_id){
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT deleted from forum_thread WHERE thread_id = ? and deleted = 1");

    $stmt->bind_param('i', $thread_id);

    $stmt->execute();
    $stmt->store_result();

    $isDeleted = $stmt->num_rows;

    $stmt->close();

    if($isDeleted == 1){
        return true;
    }else{
        return false;
    }
}
function lockForumThread($thread_id){
    global $mysqli;

    $stmt = $mysqli->prepare("update forum_thread SET locked = 1 WHERE thread_id = ?");
        $stmt->bind_param('i', $thread_id);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}
function unlockForumThread($thread_id){
    global $mysqli;

    $stmt = $mysqli->prepare("update forum_thread SET locked = 0 WHERE thread_id = ?");
    $stmt->bind_param('i', $thread_id);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}
function PinForumThread($thread_id){
    global $mysqli;

    $stmt = $mysqli->prepare("update forum_thread SET pinned = 1 WHERE thread_id = ?");
        $stmt->bind_param('i', $thread_id);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}
function unpinForumThread($thread_id){
    global $mysqli;

    $stmt = $mysqli->prepare("update forum_thread SET pinned = 0 WHERE thread_id = ?");
    $stmt->bind_param('i', $thread_id);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}
function mostRecentThread($user_id){
    global $mysqli;

    $stmt = $mysqli->query("
SELECT forum_replys.timestamp, topic_header, forum_replys.user_id, forum_replys.thread_id, reply_id
FROM forum_replys
INNER JOIN forum_thread
ON forum_replys.thread_id = forum_thread.thread_id
AND forum_replys.deleted = false and forum_replys.user_id = $user_id
ORDER BY forum_replys.timestamp DESC
LIMIT 6");

    $fetched = $stmt->fetch_object();

    return "Most Recent thread: <a href='/forum/thread/$fetched->thread_id'>$fetched->topic_header</a>";
}

function forumQuotes($quoted1)
{
    global $mysqli;

    $strQ = "";


        echo "<blockquote>";
        if (count($quoted1) === 1) {
            $uniqueID = implode($quoted1);

            $stmt = $mysqli->query("SELECT * FROM forum_replys WHERE uniqueID = '$uniqueID'");
            $quoteResults = $stmt->fetch_array();


            $post_content =
            $quoteResults['reply_content']."[br]" .
            "[em]" . getUsername($quoteResults['user_id'], "forum_replys") . "[/em]"
            . "[/blockquote]";
        } else {
            foreach ($quoted1 as $message) {

                $stmt = $mysqli->query("SELECT * FROM forum_replys WHERE uniqueID = '$message'");
                $quoteResults = $stmt->fetch_array(MYSQLI_ASSOC);

                echo "<blockquote>";
                echo "<p>".$quoteResults['reply_content']."</p>";
                echo "<em>" . getUsername($quoteResults['user_id'], "forum_replys") . "</em>";

                $strQ = $strQ . "</blockquote>";
            }
            echo $strQ;
            $post_content = "";
        }
    return $post_content;

}
function swearFilterCheck($string){
    include("languages/swear-word-list.php");

    foreach ($swear_word_list as $sw) {
        //if (strstr($string, $url)) { // mine version
        if (strpos($string, $sw) !== FALSE) { // Yoshi version
            return true;
        }
    }
    return false;
}

function filterwords($text){
    include("languages/swear-word-list.php");

    $filterCount = sizeof($swear_word_list);
    for($i=0; $i<$filterCount; $i++){
        $text = preg_replace('/\b'.$swear_word_list[$i].'\b',str_repeat('**',strlen('$0')),strtolower($text));
    }
    return $text;
}
function insertForumSwear($post_id){
    global $mysqli, $loggedInUser;

    $stmt = $mysqli->prepare("INSERT INTO `forum_swear_word_occurrence`(`user_id`, `post_id`) VALUES (?,?)");

    $stmt->bind_param('ii',$loggedInUser->user_id, $post_id);
    $stmt->execute();

    $stmt->close();
}
function totalThreads(){
    global $mysqli, $loggedInUser;

    $stmt = $mysqli->prepare("SELECT thread_id from `forum_thread`");

    $stmt->execute();

    $stmt->store_result();

    $lastPost = $stmt->num_rows;

    $stmt->close();

    return $lastPost;
}
function totalPosts(){
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT thread_id from `forum_replys`");

    $stmt->execute();

    $stmt->store_result();

    $lastPost = $stmt->num_rows;

    $stmt->close();

    return $lastPost;
}
function totaledits(){
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT edit_id FROM `forum_posts_edits`");

    $stmt->execute();

    $stmt->store_result();

    $lastPost = $stmt->num_rows;

    $stmt->close();

    return $lastPost;
}
function totalOnlineUsers(){
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT DISTINCT (`username`) from `portalsessions`");

    $stmt->execute();

    $stmt->store_result();

    $lastPost = $stmt->num_rows;

    $stmt->close();

    return $lastPost;
}
function totalInfractionsuser($user_id){
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT * FROM forum_offences WHERE forum_offences.user_id = ? AND deleted = false");

    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    $stmt->store_result();

    $lastPost = $stmt->num_rows;

    $stmt2 = $mysqli->prepare("SELECT * FROM `forum_swear_word_occurrence` WHERE user_id = ? AND deleted = false");

        $stmt2->bind_param('i', $user_id);
        $stmt2->execute();

        $stmt2->store_result();

        $swear = $stmt2->num_rows;

    $stmt->close();
    $stmt2->close();

    return $lastPost + $swear;
}
function userForumLogs($page, $action, $level){
    global $mysqli, $loggedInUser, $ip, $time;


    $stmt = $mysqli->prepare("INSERT INTO `forum_user_logs`(`user_id`, `page`, `action`, `ip`, `timestamp`, `level`) VALUES (?,?,?,?,?,?)");

    $stmt->bind_param('issiii', $loggedInUser->user_id, $page, $action, $ip, $time, $level);
    $stmt->execute();

    $stmt->close();
}

function modForumLogs($page, $action){
    global $mysqli, $loggedInUser, $ip, $time;

    $level = 5;

    $stmt = $mysqli->prepare("INSERT INTO `forum_user_logs`(`user_id`, `page`, `action`, `ip`, `timestamp`, `level`) VALUES (?,?,?,?,?,?)");

    $stmt->bind_param('issiii', $loggedInUser->user_id, $page, $action, $ip, $time, $level);
    $stmt->execute();

    $stmt->close();
}
function updatecategoryid($new_cat_id, $thread_id){

    global $mysqli;

    $stmt = $mysqli->prepare("UPDATE forum_thread SET sub_category_id = ? WHERE thread_id = ?");

    $stmt->bind_param("ii", $new_cat_id, $thread_id);
    if($stmt->execute()){
        $stmt->close();
        return true;
    }else{
        $stmt->close();
        return false;
    }
}
function forumLikesHtml($post_id){
    global $mysqli;

    $stmt = $mysqli->query("SELECT * FROM forum_likes WHERE post_id = $post_id");
    $num_rows = $stmt->num_rows;

    $stmt1 = $mysqli->query("SELECT * FROM forum_likes WHERE post_id = $post_id");

    $names = array();

    while($un = $stmt1->fetch_array()){
        $names[] .= getDisplayName($un['user_id'],"forum_likes");
    }
    $grammar = "likes";
    $liked_num = $num_rows;
    switch ($num_rows){
        case 0:
            $liked_alge = "No ones has liked this";
        break;
        case 1:
            $grammar = "like";
            $liked_alge = implode(", ", $names)." liked this";
            break;
        case 2:
            $liked_alge = $names[0] ." and ". $names[1] . "liked this";
            break;
        case 3:
            $liked_alge = $names[0] .", ". $names[1] . " and ". $names[2] ." liked this";
            break;
        case $num_rows > 3:
            if (($liked_num - 3) == 1){
                $like_grammar = "other has";
            }else {
                $like_grammar = "others have";
            }
            $liked_alge = $names[0] .", ". $names[1] . ", ". $names[2] ." and ".($liked_num - 3) ." ". $like_grammar ." liked this";
            break;
        default:
            break;
    }
    $html = '<a data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$liked_alge.'" class="wholiked">'.$num_rows.' '.$grammar.'</a>';

    return "with ". $html;
}
function forumMention($content){
	global $loggedInUser;

	$names = array();

	$explode = explode($content);
	foreach ($explode as $exp){
		if(strpos($exp, 0, 1) == "@"){
			$names[] = substr($exp, 1);
		}
	}
	return $names;
}
function forumUserAlge($user_id){
    global $mysqli;

    $stmt1 = $mysqli->query("SELECT `permission_id` FROM `portaluser_permission_matches` WHERE `permission_id` = 2 and `user_id` = $user_id");
    if($stmt1->num_rows > 0){

            if($user_id == 1) {
                return  "<span class=\"label label-group label-dev\">DEV</span><span class=\"label label-group label-admin\">Admin</span>";
            }

        return "<span class=\"label label-group label-admin\">Admin</span>
                             <span class=\"label label-group label-foundation\">Founder</span>";
    }

    $stmt = $mysqli->query("SELECT max(`permission_id`) AS perm_id FROM `portaluser_permission_matches` WHERE  `user_id` = $user_id ORDER BY perm_id DESC");

        while($row = $stmt->fetch_array()) {
            switch ($row['perm_id']) {
                case 25:
                    return "<span class=\"label label-group label-sc\">4ic+</span>";
                    break;
                case 24 :
                    return "<span class=\"label label-group label-MoD\">MOD</span>";
                    break;
                case 9 :
                    return "<span class=\"label label-group label-leadership\">OOA+</span>";
                    break;
                case 5 :
                    return "<span class=\"label label-group label-HR\">HR+</span>";
                    break;
                case 4:
                    return "<span class=\"label label-group label-securityTrainer\">Trainer +</span>";

            }
        }
}
function showUserPerms($permissionData, $userPermission){

    foreach ($permissionData as $v1) {
        if (isset($userPermission[$v1['id']])) {
            $name = $v1['name'];

            if (strpos($v1['name'], 'EU: ') !== false) {
                switch ($v1['name']) {
                    case "EU: Conflict Resolution Unit":
                        $name = "CRU";
                        break;
                    case "EU: Standard Rank Council":
                        $name = "SRC";
                        break;
                    case "EU: Event Planners":
                        $name = "EP";
                        break;
                    case "EU: Advertising Team":
                        $name = "ADV";
                        break;
                    case "EU: Social Media Team":
                        $name = "SMT";
                        break;
                    case "EU: Development Team":
                        $name = "DEV";
                        break;
                    case "EU: INV":
                        $name = "INV";
                        break;

                }
            }
                if (strpos($v1['name'], 'AD: ') !== false) {
                    switch ($v1['name']){
                        case "AD: External Affairs":
                            $name = "AD";
                            break;
                        case "AD: ISA":
                            $name = "ISA";
                            break;
                        case "AD: OSA":
                            $name = "OSA";
                            break;
                    }
                }
                echo "<span class=\"label label-profileEUS\" style=' margin-top: 3px !important;'>".$name."</span>\n";
            }
    }
}
function deleteForumThread($thread_id){
    global $mysqli;

    $deleted = true;

    $stmt = $mysqli->prepare("UPDATE `forum_replys` SET deleted = ? WHERE thread_id = ?");

    $stmt->bind_param('ii', $deleted, $thread_id);

    $stmt->execute();

    $stmt->close();

    $stmt2 = $mysqli->prepare("UPDATE `forum_thread` SET deleted = ? WHERE thread_id = ?");

    $stmt2->bind_param('ii', $deleted, $thread_id);

    $stmt2->execute();

    $stmt2->close();
}
function newUserPref(){
    global $mysqli, $loggedInUser;

    $stmt = $mysqli->prepare("INSERT INTO `portaluser_preferences`(`user_id`) VALUES (?)");
    $stmt->bind_param('i', $loggedInUser->user_id);

    $stmt->execute();
}
function updateUserPref($column, $value, $valueType){
    global $mysqli, $loggedInUser;

    $stmt = $mysqli->prepare("UPDATE `portaluser_preferences` SET $column = ? WHERE user_id = ?");
    $stmt->bind_param($valueType.'i', $value, $loggedInUser->user_id);

    if($stmt->execute()){
        $stmt->close();
        return true;
    }else{
        $stmt->close();
        return false;
    }
}

function forumFindMention($content){
	global $loggedInUser;

	$names = array();

	$explode = explode(" ", $content);
	foreach ($explode as $exp){
		if(substr($exp, 0, 1) == "@"){
			$k = substr($exp, 1);
			if ($loggedInUser->username != $k){
				$names[] = $k;
			}
		}
	}
	return $names;
}
function userMentionNotify($names, $thread, $href){
	global $mysqli;
	$a = ($names);
	$names = array_unique($a);

	$notification_id = newForumNotification2(3,$thread, $href);

	foreach($names as $name){
		$stmt = $mysqli->query("SELECT `id`, `user_name` from `portalusers` WHERE `user_name` = '$name'")->fetch_object();
		if($stmt){
			forumNotificationReciever($notification_id, $stmt->id);
		}
	}
}






















