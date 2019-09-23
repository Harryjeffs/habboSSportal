<?php
/*
UserCake Version: 3.0.0
*/

/*
%m1% - Dymamic markers which are replaced at run time by the relevant index.
*/

$lang = array();

//forum error messages
$lang = array_merge($lang, array(
    "FORUM_POST_SUCCESS" => "You have successfully created your thread",
    "FORUM_SLOW_DOWN" => "You need to wait 2 minutes in between each post",
    "FORUM_EDIT_SUCCESS" => "Your post has been successfully edited.",
    "FORUM_NEW_THREAD" => "You have successfully created a new thread!",
    "FORUM_NEW_EMPTY" => "Your thread cannot be empty. Please enter something into the text box.",
    "FILE_SUCCESS" => "The photo was successfully uploaded.",
    "FILE_ERROR1" => "Sorry, there was an error uploading the photo.",
	"THREAD_DELETION" => "You have successfully deleted that thread.",
));

//file uploads
$lang = array_merge($lang, array(
	"FILE_TYPE" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed.",
	"FILE_TOO_LARGE" => "Sorry, your file is too large.",
	"FILE_EXIST" => "Sorry, this photo/file already exists.",
	"FILE_ERROR" => "Sorry, your file was not uploaded",
	"FILE_SUCCESS" => "The photo was successfully uploaded.",
	"FILE_ERROR1" => "Sorry, there was an error uploading the photo.",

));
//Account
$lang = array_merge($lang,array(
	"ACCOUNT_SPECIFY_USERNAME" 		=> "Please enter your username",
	"ACCOUNT_SPECIFY_PASSWORD" 		=> "Please enter your password",
	"ACCOUNT_SPECIFY_EMAIL"			=> "Please enter your email address",
	"ACCOUNT_INVALID_EMAIL"			=> "Invalid email address",
	"ACCOUNT_USER_OR_EMAIL_INVALID"		=> "Username or email address is invalid",
	"ACCOUNT_USER_OR_PASS_INVALID"		=> "Username or password is invalid",
	"ACCOUNT_USER_OR_PASS_INVALID2"		=> "Username or password is invalid(top_nav)",
	"ACCOUNT_ALREADY_ACTIVE"		=> "Your account is already activated",
	"ACCOUNT_INACTIVE"			=> "Your account is in-active. Contact an admin to have your account activated.",
	"ACCOUNT_USER_CHAR_LIMIT"		=> "Your username must be between %m1% and %m2% characters in length",
	"ACCOUNT_DISPLAY_CHAR_LIMIT"		=> "Your display name must be between %m1% and %m2% characters in length",
	"ACCOUNT_PASS_CHAR_LIMIT"		=> "Your password must be between %m1% and %m2% characters in length",
	"ACCOUNT_TITLE_CHAR_LIMIT"		=> "Titles must be between %m1% and %m2% characters in length",
	"ACCOUNT_PASS_MISMATCH"			=> "Your password and confirmation password must match",
	"ACCOUNT_DISPLAY_INVALID_CHARACTERS"	=> "Display name can only include alpha-numeric characters",
	"ACCOUNT_USERNAME_IN_USE"		=> "Username %m1% is already in use",
	"ACCOUNT_DISPLAYNAME_IN_USE"		=> "Display name %m1% is already in use",
	"ACCOUNT_USERNAME_UPDATED" => "Account username successfully updated.",
	"ACCOUNT_EMAIL_IN_USE"			=> "Email %m1% is already in use",
	"ACCOUNT_LINK_ALREADY_SENT"		=> "An activation email has already been sent to this email address in the last %m1% hour(s)",
	"ACCOUNT_USERNAME_H_INVALID"		=> "Our checks show that no Habbo account exists with this username. Please make sure you're using a valid username.",
	"ACCOUNT_NEW_ACTIVATION_SENT"		=> "We have emailed you a new activation link, please check your email",
	"ACCOUNT_SPECIFY_NEW_PASSWORD"		=> "Please enter your new password",
	"ACCOUNT_SPECIFY_CONFIRM_PASSWORD"	=> "Please confirm your new password",
	"ACCOUNT_NEW_PASSWORD_LENGTH"		=> "New password must be between %m1% and %m2% characters in length",
	"ACCOUNT_PASSWORD_INVALID"		=> "Current password doesn&#39;t match the one we have on record",
	"ACCOUNT_DETAILS_UPDATED"		=> "Account details updated",
	"ACCOUNT_ACTIVATION_MESSAGE"		=> "Please wait for an admin to accept your account. You can visit portal.habboss.com/admin_contact/ to request an activation.",
		"ACCOUNT_REGISTRATION_COMPLETE_TYPE2"	=> "You have successfully registered. Please wait for a portal moderator to accept your account. You will be notified by email when this has been done.",
	"ACCOUNT_PASSWORD_NOTHING_TO_UPDATE"	=> "You cannot update with the same password",
	"ACCOUNT_PASSWORD_UPDATED"		=> "Account password updated",
	"j"		=> "Account username updated",
	"ACCOUNT_EMAIL_UPDATED"			=> "Account email updated",
	"ACCOUNT_TOKEN_NOT_FOUND"		=> "Token does not exist / Account is already activated",
	"ACCOUNT_USER_INVALID_CHARACTERS"	=> "Username can only include alpha-numeric characters",
	"ACCOUNT_DELETIONS_SUCCESSFUL"		=> "You have successfully deleted %m1% users",
	"ACCOUNT_MANUALLY_ACTIVATED"		=> "%m1%&#39;s account has been manually activated",
	"ACCOUNT_DISPLAYNAME_UPDATED"		=> "Display name changed to %m1%",
	"ACCOUNT_TITLE_UPDATED"			=> "%m1%&#39;s title changed to %m2%",
	"ACCOUNT_PERMISSION_ADDED"		=> "Added access to %m1% permission levels",
	"ACCOUNT_PERMISSION_REMOVED"		=> "Removed access from %m1% permission levels",
	"ACCOUNT_INVALID_USERNAME"		=> "Invalid username",
	"DELETE_SUCCESS" 				=> "You have successfully deleted the selected log",
	"DELETE_ERROR" 					=> "ERROR: Couldn&#39;t delete the selected logs. Check that the selected log exists ",
	"SEC_RISK" => "ERROR: You have tried to cause harm to the portal. You will be banned.",
	"EMPTY_MSG" => "You must fill in the message before submitting a notification.",
	"SUCCESS_SENT" => "You have successfully sent a message to all the users.",
	"ALERT_READ" => "Thanks for reading the message.",
	"TAG_UPDATE" => "Your tag has been successfully updated.",
	"RANK_UPDATE" => "Your rank has been successfully updated.",
	"READ_SUCCESS" => "Marked as read. ",
	"TAG_TOO_SHORT" => "Your tag is too long. Your tag must be between 1-3 characters.",
	"NOT_SHORT" => "Why, just why? It&#39;s too short! The message must be between 50 and 1000 characters. If you can&#39;t extend it, contact an admin.",
	"NO_RANK" => "The inputted rank doesn&#39;t exist.",
	"ADMIN_RANK_UPDATE" => "You have successfully updated %m1%&#39;s rank to %m2%",
	"ADMIN_NAME_UPDATE" => "You have successfully updated %m1%&#39;s name",
	"ADMIN_TAG_UPDATE" => "You have successfully updated %m1%&#39;s&#39;s tag",
	"ACCOUNT_DELETED" => "Your account has been deleted.",
	"REJECTION_SUCCESSFUL" => "You have successfully rejected that members request.",
	"ACCOUNT_TRAINER_ACTIVATED" => "You have successfully accepted their trainer account.",
	"ACCOUNT_HR_ACTIVATED" => "You have successfully accepted their high rank account."


));

//Configuration
$lang = array_merge($lang,array(
	"CONFIG_NAME_CHAR_LIMIT"		=> "Site name must be between %m1% and %m2% characters in length",
	"CONFIG_URL_CHAR_LIMIT"			=> "Site name must be between %m1% and %m2% characters in length",
	"CONFIG_EMAIL_CHAR_LIMIT"		=> "Site name must be between %m1% and %m2% characters in length",
	"CONFIG_ACTIVATION_TRUE_FALSE"		=> "Email activation must be either `true` or `false`",
	"CONFIG_ACTIVATION_RESEND_RANGE"	=> "Activation Threshold must be between %m1% and %m2% hours",
	"CONFIG_LANGUAGE_CHAR_LIMIT"		=> "Language path must be between %m1% and %m2% characters in length",
	"CONFIG_LANGUAGE_INVALID"		=> "There is no file for the language key `%m1%`",
	"CONFIG_TEMPLATE_CHAR_LIMIT"		=> "Template path must be between %m1% and %m2% characters in length",
	"CONFIG_TEMPLATE_INVALID"		=> "There is no file for the template key `%m1%`",
	"CONFIG_EMAIL_INVALID"			=> "The email you have entered is not valid",
	"CONFIG_INVALID_URL_END"		=> "Please include the ending / in your site&#39;s URL",
	"CONFIG_UPDATE_SUCCESSFUL"		=> "Your site&#39;s configuration has been updated. You may need to load a new page for all the settings to take effect",
));

//Forgot Password
$lang = array_merge($lang,array(
	"FORGOTPASS_INVALID_TOKEN"		=> "Your activation token is not valid",
	"FORGOTPASS_NEW_PASS_EMAIL"		=> "We have emailed you a new password",
	"FORGOTPASS_REQUEST_CANNED"		=> "Lost password request cancelled",
	"FORGOTPASS_REQUEST_EXISTS"		=> "There is already a outstanding lost password request on this account",
	"FORGOTPASS_REQUEST_SUCCESS"		=> "We have emailed you instructions on how to regain access to your account",
	"FORGOT_SENT_SUCCESS" => "You have successfully reset the users password via their chosen email."
));

//Mail
$lang = array_merge($lang,array(
	"MAIL_ERROR"				=> "Fatal error attempting mail, contact your server administrator",
	"MAIL_TEMPLATE_BUILD_ERROR"		=> "Error building email template",
	"MAIL_TEMPLATE_DIRECTORY_ERROR"		=> "Unable to open mail-templates directory. Perhaps try setting the mail directory to %m1%",
	"MAIL_TEMPLATE_FILE_EMPTY"		=> "Template file is empty... nothing to send",
));

//Miscellaneous
$lang = array_merge($lang,array(
	"NOTHING" => "You need to enter something! ",
	"CAPTCHA_FAIL"				=> "Failed security question",
	"CONFIRM"				=> "Confirm",
	"DENY"					=> "Deny",
	"SUCCESS"				=> "Success",
	"ERROR"					=> "Error",
	"NOTHING_TO_UPDATE"			=> "Nothing to update",
	"SQL_ERROR"				=> "Fatal SQL error",
	"FEATURE_DISABLED"			=> "This feature is currently disabled",
	"PAGE_PRIVATE_TOGGLED"			=> "This page is now %m1%",
	"PAGE_ACCESS_REMOVED"			=> "Page access removed for %m1% permission level(s)",
	"PAGE_ACCESS_ADDED"			=> "Page access added for %m1% permission level(s)",
	"LOG_SUCCESSFUL" 			=> "Your log has been successfully submitted. ",
	"LOG_DENIED" 						=> "Your log couldn&#39;t be processed due to an error.",
	"EDIT_SUCCESSFUL" => "Log successfully updated."
));

//Permissions
$lang = array_merge($lang,array(
	"PERMISSION_CHAR_LIMIT"			=> "Permission names must be between %m1% and %m2% characters in length",
	"PERMISSION_NAME_IN_USE"		=> "Permission name %m1% is already in use",
	"PERMISSION_DELETIONS_SUCCESSFUL"	=> "Successfully deleted %m1% permission level(s)",
	"PERMISSION_CREATION_SUCCESSFUL"	=> "Successfully created the permission level `%m1%`",
	"PERMISSION_NAME_UPDATE"		=> "Permission level name changed to `%m1%`",
	"PERMISSION_REMOVE_PAGES"		=> "Successfully removed access to %m1% page(s)",
	"PERMISSION_ADD_PAGES"			=> "Successfully added access to %m1% page(s)",
	"PERMISSION_REMOVE_USERS"		=> "Successfully removed %m1% user(s)",
	"PERMISSION_ADD_USERS"			=> "Successfully added %m1% user(s)",
	"CANNOT_DELETE_NEWUSERS"		=> "You cannot delete the default &#39;new user&#39; group",
	"CANNOT_DELETE_ADMIN"			=> "You cannot delete the default &#39;admin&#39; group",
));

//User Prefrences forum
$lang = array_merge($lang, array(
	"USER_ONLINE_SUCCESS"=>"Your online status been successfully updated",
	"USER_GENDER_SUCCESS"=>"Your gender been successfully updated to %m1%",
	"USER_TWITTER_SUCCESS"=>"Your twitter been successfully updated",
	"USER_FACEBOOK_SUCCESS"=>"Your facebook been successfully updated",
	"USER_SKYPE_SUCCESS"=>"Your skype been successfully updated",
));
?>
