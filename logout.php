<?php
/*
UserSpice 2.5.6
by Dan Hoover at http://UserSpice.com

based on
UserCake Version: 2.0.2


UserCake created by: Adam Davis
UserCake V2.0 designed by: Jonathan Cassels

Please note that this version uses technology that some consider
to be outdated. This version is designed as a cosmetic upgrade for
users of 2.0.2 and as a path towards development of version 3.0 and beyond
*/

require_once("models/config.php");

//Log the user out
if(isUserLoggedIn())
{
	$loggedInUser->userLogOut();
}

if(!empty($websiteUrl))
{
	$add_http = "http://";

	if(strpos($websiteUrl,"https://") === false)
	{
		$add_http = "https://";
	}

	header("Location: ".$add_http.$websiteUrl);
	die();
}
else
{
 header("Location: /");
	die();
}

?>
