<?php
include("db-settings.php"); //Require DB connection

date_default_timezone_set('America/New_York');

//Retrieve settings
$stmt = $mysqli->prepare("SELECT id, name, value
	FROM ".$db_table_prefix."configuration");
$stmt->execute();
$stmt->bind_result($id, $name, $value);

while ($stmt->fetch()){
    $settings[$name] = array('id' => $id, 'name' => $name, 'value' => $value);
}

$stmt->close();

if (!defined('FULL_PATH')) define('FULL_PATH', '/forum');

//Set Settings
$emailActivation = $settings['activation']['value'];
$mail_templates_dir = "models/mail-templates/";
$websiteName = $settings['website_name']['value'];
$websiteUrl = $settings['website_url']['value'];
$emailAddress = $settings['email']['value'];
$resend_activation_threshold = $settings['resend_activation_threshold']['value'];
$emailDate = date('dmy');
$language = $settings['language']['value'];
$template = $settings['template']['value'];
$maintenance = $settings['maintenance']['value'];
$error_handling = $settings['error_handling']['value'];

//Remember me - amount of time to remain logged in.
$remember_me_length = $settings['remember_me_length']['value'];

//if($error_handling == 1){
//
//	ini_set('display_errors', 1);
//	ini_set('display_startup_errors', 1);
//	error_reporting(E_ALL);
//
//}
$master_account = -1;

$default_hooks = array("#WEBSITENAME#","#WEBSITEURL#","#DATE#");
$default_replace = array($websiteName,$websiteUrl,$emailDate);


//Pages to require
require_once("languages/en.php");
require_once("class/class.mail.php");
require_once("class/class.bbcode.php");
require_once("class/class.forummentions.php");
require_once("class/class.paginaton.php");
require_once("class/class.user.php");
require_once("funcs.php");
include_once("checks.php");

$ip = ip();
$time = time();

GLOBAL $time;
GLOBAL $ip;

if(!isset($_SESSION))
{
    session_start();
}

//Global User Object Var
//loggedInUser can be used globally if constructed
if(isset($_SESSION["portalUser"]) && is_object($_SESSION["portalUser"]))
{
    $loggedInUser = $_SESSION["portalUser"];
}
else if(isset($_COOKIE["portalUser"]))
{
    $stmt = $mysqli->prepare("SELECT sessionData FROM ".$db_table_prefix."sessions WHERE sessionID = ?");
    $stmt->bind_param("s", $_COOKIE['portalUser']);
    $stmt->execute();
    $stmt->bind_result($sessionData);

    while ($stmt->fetch())
    {
        $row = array('sessionData' => $sessionData);
    }

    if(empty($row['sessionData']))
    {
        $loggedInUser = NULL;
        setcookie("portalUser", "", -parseLength($remember_me_length));
    }
    else
    {
        $loggedInUser = unserialize($row['sessionData']);
    }

    $stmt->close();
}
else
{
    $time = time();
    $remember_length = parseLength($remember_me_length);
    $stmt = $mysqli->prepare("DELETE FROM ".$db_table_prefix."sessions WHERE ? >= (`sessionStart` + ?)");
    $stmt->bind_param("ii", $time, $remember_length);
    $stmt->execute();
    $stmt->close();

    $loggedInUser = NULL;
}
