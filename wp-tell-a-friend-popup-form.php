<?php

/*
Plugin Name: wp tell a friend popup form
Description: This will create the popup form to the user to share the website link to their friend. The concept of this plug-in is to open the Tell a Friend form in the popup window by clicking the button from the page.
Author: Gopi.R
Version: 4.1
Plugin URI: http://www.gopiplus.com/work/2012/05/21/wordpress-plugin-wp-tell-a-friend-popup-form/
Author URI: http://www.gopiplus.com/work/2012/05/21/wordpress-plugin-wp-tell-a-friend-popup-form/
Donate link: http://www.gopiplus.com/work/2012/05/21/wordpress-plugin-wp-tell-a-friend-popup-form/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function TellAFriend()
{
	$TellAFriendLink = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
	?>
	<a href='javascript:TellAFriend_OpenForm("TellAFriend_BoxContainer","TellAFriend_BoxContainerBody","TellAFriend_BoxContainerFooter");'><?php echo get_option('TellAFriend_Caption'); ?></a>
	<div style="display: none;" id="TellAFriend_BoxContainer">
	  <div id="TellAFriend_BoxContainerHeader">
		<div id="TellAFriend_BoxTitle"><?php echo get_option('TellAFriend_Title'); ?></div>
		<div id="TellAFriend_BoxClose"><a href="javascript:TellAFriend_HideForm('TellAFriend_BoxContainer','TellAFriend_BoxContainerFooter');">Close</a></div>
	  </div>
	  <div id="TellAFriend_BoxContainerBody">
		<form action="#" name="TellAFriend_Form" id="TellAFriend_Form">
		  <div id="TellAFriend_BoxAlert"> <span id="TellAFriend_alertmessage"></span> </div>
		  <div id="TellAFriend_BoxLabel"> Your Name </div>
		  <div id="TellAFriend_BoxLabel">
			<input name="TellAFriend_name" class="TellAFriend_TextBox" type="text" id="TellAFriend_name" maxlength="120">
		  </div>
		  <div id="TellAFriend_BoxLabel"> Friend Email </div>
		  <div id="TellAFriend_BoxLabel">
			<input name="TellAFriend_email" class="TellAFriend_TextBox" type="text" id="TellAFriend_email" maxlength="120">
		  </div>
		  <div id="TellAFriend_BoxLabel"> Enter Message To Friend </div>
		  <div id="TellAFriend_BoxLabel">
			<textarea name="TellAFriend_message" class="TellAFriend_TextArea" rows="3" id="TellAFriend_message"></textarea>
		  </div>
		  <div id="TellAFriend_BoxLabel">
			<input type="button" name="button" class="TellAFriend_Button" value="Submit" onClick="javascript:TellAFriend_Submit(this.parentNode,'<?php echo get_option('siteurl'); ?>/wp-content/plugins/wp-tell-a-friend-popup-form/');">
		  </div>
		  <input type="hidden" name="TellAFriend_Link" id="TellAFriend_Link" value="<?php echo $TellAFriendLink; ?>"  />
		</form>
	  </div>
	</div>
	<div style="display: none;" id="TellAFriend_BoxContainerFooter"></div>
	<?php
}

function TellAFriend_install() 
{
	global $wpdb, $wp_version;
	add_option('TellAFriend_Title', "Tell A Friend");
	add_option('TellAFriend_Fromemail', "noreply@yoursitename.com");
	add_option('TellAFriend_On_MyEmail', "");
	add_option('TellAFriend_On_Subject', "Link recommended by ##USERNAME##");
	add_option('TellAFriend_Caption', "<img src='".get_option('siteurl')."/wp-content/plugins/wp-tell-a-friend-popup-form/tell-a-friend.jpg' />");
	add_option('TellAFriend_Adminmail_Content', "Hi Admin, Someone (##USERNAME##) used our form to send link (##LINK##) to friend ##FRIENDEMAIL## with the message <br /> ##MESSAGE## ");
	add_option('TellAFriend_Usermail_Content', "Hi, Your friend ##USERNAME## has sent you a link to web site. <br /><br /> ##LINK## <br /><br /> ##MESSAGE##");

}

function TellAFriend_widget($args) 
{
	extract($args);
	echo $before_widget;
	TellAFriend();
	echo $after_widget;
}
	
function TellAFriend_control() 
{
	echo 'To change the setting goto Popup contact form link on Setting menu.';
	echo '<br><a href="options-general.php?page=wp-tell-a-friend-popup-form/wp-tell-a-friend-popup-form.php">';
	echo 'click here</a></p>';
}

function TellAFriend_widget_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('Tell A Friend', 'Tell A Friend', 'TellAFriend_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('Tell A Friend', array('Tell A Friend', 'widgets'), 'TellAFriend_control');
	} 
}

function TellAFriend_deactivation() 
{
	// No deactivation
}

function TellAFriend_admin()
{
	echo '<div class="wrap">';
	echo '<h2>Popup contact form</h2>';
	global $wpdb, $wp_version;
	$TellAFriend_Title = get_option('TellAFriend_Title');
	$TellAFriend_Fromemail = get_option('TellAFriend_Fromemail');
	$TellAFriend_On_MyEmail = get_option('TellAFriend_On_MyEmail');
	$TellAFriend_On_Subject = get_option('TellAFriend_On_Subject');
	$TellAFriend_Caption = get_option('TellAFriend_Caption');
	$TellAFriend_Adminmail_Content = get_option('TellAFriend_Adminmail_Content');
	$TellAFriend_Usermail_Content = get_option('TellAFriend_Usermail_Content');
	
	if (@$_POST['TellAFriend_submit']) 
	{
		$TellAFriend_Title = stripslashes($_POST['TellAFriend_Title']);
		$TellAFriend_Fromemail = stripslashes($_POST['TellAFriend_Fromemail']);
		$TellAFriend_On_MyEmail = stripslashes($_POST['TellAFriend_On_MyEmail']);
		$TellAFriend_On_Subject = stripslashes($_POST['TellAFriend_On_Subject']);
		$TellAFriend_Caption = stripslashes($_POST['TellAFriend_Caption']);
		$TellAFriend_Adminmail_Content = stripslashes($_POST['TellAFriend_Adminmail_Content']);
		$TellAFriend_Usermail_Content = stripslashes($_POST['TellAFriend_Usermail_Content']);
		
		update_option('TellAFriend_Title', $TellAFriend_Title );
		update_option('TellAFriend_Fromemail', $TellAFriend_Fromemail );
		update_option('TellAFriend_On_MyEmail', $TellAFriend_On_MyEmail );
		update_option('TellAFriend_On_Subject', $TellAFriend_On_Subject );
		update_option('TellAFriend_Caption', $TellAFriend_Caption );
		update_option('TellAFriend_Adminmail_Content', $TellAFriend_Adminmail_Content );
		update_option('TellAFriend_Usermail_Content', $TellAFriend_Usermail_Content );
	}
	
	echo '<form name="form_gCF" method="post" action="">';
	
	echo '<p>Title:<br><input  style="width: 350px;" type="text" value="';
	echo $TellAFriend_Title . '" name="TellAFriend_Title" id="TellAFriend_Title" /></p>';
	
	echo '<p>From Email:<br><input  style="width: 350px;" type="text" value="';
	echo $TellAFriend_Fromemail . '" name="TellAFriend_Fromemail" id="TellAFriend_Fromemail" /></p>';
	
	echo '<p>Admin Email:<br><input style="width: 350px;" type="text" value="';
	echo $TellAFriend_On_MyEmail . '" name="TellAFriend_On_MyEmail" maxlength="200" id="TellAFriend_On_MyEmail" /><br />';
	echo '<span style="font-size:0.8em;">Enter your email address to receive  Tell A Friend email copy</span></p>';
	
	echo '<p>Email Subject:<br><input style="width: 350px;" type="text" value="';
	echo $TellAFriend_On_Subject . '" name="TellAFriend_On_Subject" maxlength="200" id="TellAFriend_On_Subject" /><br />';
	echo '<span style="font-size:0.8em;">Enter mail subject</span></p>';
	
	echo '<p>Link Button/Text:<br><input style="width: 700px;" type="text" value="';
	echo $TellAFriend_Caption . '" name="TellAFriend_Caption" id="TellAFriend_Caption" /><br />';
	echo '<span style="font-size:0.8em;">This box is to add the contact us Image or text, Entered value will display in the front end.</span></p>';
	
	?>
    <table width="640" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Admin mail content:</td>
        <td>User mail content:</td>
      </tr>
      <tr>
        <td><textarea name="TellAFriend_Adminmail_Content" rows="10" id="j_adminmail_content" style="width: 450px;"><?php echo $TellAFriend_Adminmail_Content ?></textarea></td>
        <td><textarea name="TellAFriend_Usermail_Content" rows="10" id="j_usermail_content" style="width: 450px;"><?php echo $TellAFriend_Usermail_Content ?></textarea></td>
      </tr>
      <tr><td colspan="2">Keywords: ##USERNAME## , ##LINK## , ##FRIENDEMAIL## , ##MESSAGE##</td></tr>
    </table>
    <?php
	
	echo '<br /><input type="submit" id="TellAFriend_submit" name="TellAFriend_submit" lang="publish" class="button-primary" value="Update Setting" value="1" />';
	
	$help = "'http://www.gopiplus.com/work/2012/05/21/wordpress-plugin-wp-tell-a-friend-popup-form/'";
	echo '&nbsp;&nbsp;&nbsp;<input name="Help" lang="publish" class="button-primary" onclick="window.open('.$help.');" value="Help" type="button" />';
	
	echo '</form>';
	
	echo '<br /><strong>Plugin configuration</strong><br />';
	echo '<ol>';
	echo '<li>Drag and drop the widget</li>';
	echo '<li>Paste the php code to your desired template location</li>';
	echo '<li>Short code for pages and posts</li>';
	echo '</ol>';
	echo 'Note: Check official website for more info <a href="http://www.gopiplus.com/work/2012/05/21/wordpress-plugin-wp-tell-a-friend-popup-form/" target="_blank">click here</a>';

	echo '</div>';
}


function TellAFriend_add_to_menu() 
{
	add_options_page('Tell a friend', 'Tell a friend', 'manage_options', __FILE__, 'TellAFriend_admin' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'TellAFriend_add_to_menu');
}

function TellAFriend_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_style( 'tell-a-friend', get_option('siteurl').'/wp-content/plugins/wp-tell-a-friend-popup-form/tell-a-friend.css');
		wp_enqueue_script( 'tell-a-friend-form', get_option('siteurl').'/wp-content/plugins/wp-tell-a-friend-popup-form/tell-a-friend-form.js');
		wp_enqueue_script( 'tell-a-friend-popup', get_option('siteurl').'/wp-content/plugins/wp-tell-a-friend-popup-form/tell-a-friend-popup.js');
	}
}   

//[tell-a-friend id="1" title="Tell a friend"]
function TellAFriend_shortcode( $atts ) 
{
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	
	$id = $atts['id'];
	$title = $atts['title'];
	$TellAFriendLink = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
	
	$TellAFriend_Caption = get_option('TellAFriend_Caption');
	$PopupContact_title = $title;
	$siteurl = "'".get_option('siteurl') . "/wp-content/plugins/wp-tell-a-friend-popup-form/'";
	$close = "javascript:TellAFriend_HideForm('TellAFriend_BoxContainer','TellAFriend_BoxContainerFooter');";
	$open = 'javascript:TellAFriend_OpenForm("TellAFriend_BoxContainer","TellAFriend_BoxContainerBody","TellAFriend_BoxContainerFooter");';
	
	$html = "<a href='".$open."'>".$TellAFriend_Caption."</a>";
	$html .= '<div style="display: none;" id="TellAFriend_BoxContainer">';
	  $html .= '<div id="TellAFriend_BoxContainerHeader">';
		$html .= '<div id="TellAFriend_BoxTitle">'.$PopupContact_title.'</div>';
		$html .= '<div id="TellAFriend_BoxClose"><a href="'.$close .'">Close</a></div>';
	  $html .= '</div>';
	  $html .= '<div id="TellAFriend_BoxContainerBody">';
		$html .= '<form action="#" name="TellAFriend_Form" id="TellAFriend_Form">';
		  $html .= '<div id="TellAFriend_BoxAlert"> <span id="TellAFriend_alertmessage"></span> </div>';
		  $html .= '<div id="TellAFriend_BoxLabel_Page"> Your Name </div>';
		  $html .= '<div id="TellAFriend_BoxLabel_Page">';
			$html .= '<input name="TellAFriend_name" class="TellAFriend_TextBox" type="text" id="TellAFriend_name" maxlength="120">';
		  $html .= '</div>';
		  $html .= '<div id="TellAFriend_BoxLabel_Page"> Friend Email </div>';
		  $html .= '<div id="TellAFriend_BoxLabel_Page">';
			$html .= '<input name="TellAFriend_email" class="TellAFriend_TextBox" type="text" id="TellAFriend_email" maxlength="120">';
		  $html .= '</div>';
		  $html .= '<div id="TellAFriend_BoxLabel_Page"> Enter Message To Friend </div>';
		  $html .= '<div id="TellAFriend_BoxLabel_Page">';
			$html .= '<textarea name="TellAFriend_message" class="TellAFriend_TextArea" rows="3" id="TellAFriend_message"></textarea>';
		  $html .= '</div>';
		  $html .= '<div id="TellAFriend_BoxLabel_Page">';
			$html .= '<input type="button" name="button" class="TellAFriend_Button" value="Submit" onClick="javascript:TellAFriend_Submit(this.parentNode,'.$siteurl.');">';
		  $html .= '</div>';
		  $html .= '<input type="hidden" name="TellAFriend_Link" id="TellAFriend_Link" value="'.$TellAFriendLink.'"  />';
		$html .= '</form>';
	  $html .= '</div>';
	$html .= '</div>';
	$html .= '<div style="display: none;" id="TellAFriend_BoxContainerFooter"></div>';
	return $html;
}

add_shortcode( 'tell-a-friend', 'TellAFriend_shortcode' );
add_action('wp_enqueue_scripts', 'TellAFriend_add_javascript_files');
add_action("plugins_loaded", "TellAFriend_widget_init");
register_activation_hook(__FILE__, 'TellAFriend_install');
register_deactivation_hook(__FILE__, 'TellAFriend_deactivation');
add_action('init', 'TellAFriend_widget_init');
?>
