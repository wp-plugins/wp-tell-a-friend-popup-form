<?php
session_start();
$TellAFriend_abspath = dirname(__FILE__);
$TellAFriend_abspath_1 = str_replace('wp-content/plugins/wp-tell-a-friend-popup-form', '', $TellAFriend_abspath);
$TellAFriend_abspath_1 = str_replace('wp-content\plugins\wp-tell-a-friend-popup-form', '', $TellAFriend_abspath_1);
require_once($TellAFriend_abspath_1 .'wp-config.php');

$Adminmail_Content = "";
$Usermail_Content = "";

$SenderName = $_POST['TellAFriend_name'];
$ToEmail = $_POST['TellAFriend_email'];
$Link = $_POST['TellAFriend_Link'];
$ToMessage = $_POST['TellAFriend_message'];
$FromEmail = get_option('TellAFriend_Fromemail');
$AdminEmail = get_option('TellAFriend_On_MyEmail');
$Subject = get_option('TellAFriend_On_Subject');
$Adminmail_Content = get_option('TellAFriend_Adminmail_Content');
$Usermail_Content = get_option('TellAFriend_Usermail_Content');

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers .= "From: \"$FromEmail\" <$FromEmail>\n";

$Adminmail_Content = str_replace("&", "and", $Adminmail_Content);
$Adminmail_Content = str_replace("##USERNAME##" , $SenderName , $Adminmail_Content);
$Adminmail_Content = str_replace("##FRIENDEMAIL##" , $ToEmail , $Adminmail_Content);
$Adminmail_Content = str_replace("##MESSAGE##" , $ToMessage , $Adminmail_Content);
$Adminmail_Content = str_replace("##LINK##" , $Link , $Adminmail_Content);
$Adminmail_Content = str_replace("\r\n", "<br />", $Adminmail_Content);
$Adminmail_Content = str_replace("\n", "<br />", $Adminmail_Content);
$Adminmail_Content = nl2br($Adminmail_Content);

$Usermail_Content = str_replace("&", "and", $Usermail_Content);
$Usermail_Content = str_replace("##USERNAME##" , $SenderName , $Usermail_Content);
$Usermail_Content = str_replace("##FRIENDEMAIL##" , $ToEmail , $Usermail_Content);
$Usermail_Content = str_replace("##MESSAGE##" , $ToMessage , $Usermail_Content);
$Usermail_Content = str_replace("##LINK##" , $Link , $Usermail_Content);
$Usermail_Content = str_replace("\r\n", "<br />", $Usermail_Content);
$Usermail_Content = str_replace("\n", "<br />", $Usermail_Content);
$Usermail_Content = nl2br($Usermail_Content);

$Subject = str_replace("##USERNAME##" , $SenderName , $Subject);
$Subject = str_replace("##FRIENDEMAIL##" , $ToEmail , $Subject);
$Subject = str_replace("##LINK##" , $Link , $Subject);

if($ToEmail <> "")
{
	@wp_mail($ToEmail, $Subject, $Usermail_Content, $headers);
	if($AdminEmail <> "")
	{
		@wp_mail($AdminEmail, $Subject, $Adminmail_Content, $headers);
	}
	echo "Message sent successfully.";
}
else
{
	echo "There was a problem with the request.";
}
?>