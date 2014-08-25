<?php
/*
Plugin Name: wp tell a friend popup form
Description: This will create the popup form to the user to share the website link to their friend. The concept of this plug-in is to open the Tell a Friend form in the popup window by clicking the button from the page.
Author: Gopi Ramasamy
Version: 5.4
Plugin URI: http://www.gopiplus.com/work/2012/05/21/wordpress-plugin-wp-tell-a-friend-popup-form/
Author URI: http://www.gopiplus.com/work/2012/05/21/wordpress-plugin-wp-tell-a-friend-popup-form/
Donate link: http://www.gopiplus.com/work/2012/05/21/wordpress-plugin-wp-tell-a-friend-popup-form/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

function TellAFriend()
{
	$TellAFriendLink = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
	?>
	<a href='javascript:TellAFriend_OpenForm("TellAFriend_BoxContainer","TellAFriend_BoxContainerBody","TellAFriend_BoxContainerFooter");'><?php echo get_option('TellAFriend_Caption'); ?></a>
	<div style="display: none;" id="TellAFriend_BoxContainer">
	  <div id="TellAFriend_BoxContainerHeader">
		<div id="TellAFriend_BoxTitle"><?php echo get_option('TellAFriend_Title'); ?></div>
		<div id="TellAFriend_BoxClose"><a href="javascript:TellAFriend_HideForm('TellAFriend_BoxContainer','TellAFriend_BoxContainerFooter');"><?php _e('Close', 'tell-a-friend'); ?></a></div>
	  </div>
	  <div id="TellAFriend_BoxContainerBody">
		<form action="#" name="TellAFriend_Form" id="TellAFriend_Form">
		  <div id="TellAFriend_BoxAlert"> <span id="TellAFriend_alertmessage"></span> </div>
		  <div id="TellAFriend_BoxLabel"> <?php _e('Your Name', 'tell-a-friend'); ?> </div>
		  <div id="TellAFriend_BoxLabel">
			<input name="TellAFriend_name" class="TellAFriend_TextBox" type="text" id="TellAFriend_name" maxlength="120">
		  </div>
		  <div id="TellAFriend_BoxLabel"> <?php _e('Friend Email', 'tell-a-friend'); ?> </div>
		  <div id="TellAFriend_BoxLabel">
			<input name="TellAFriend_email" class="TellAFriend_TextBox" type="text" id="TellAFriend_email" maxlength="120">
		  </div>
		  <div id="TellAFriend_BoxLabel"> <?php _e('Enter Message To Friend', 'tell-a-friend'); ?> </div>
		  <div id="TellAFriend_BoxLabel">
			<textarea name="TellAFriend_message" class="TellAFriend_TextArea" rows="3" id="TellAFriend_message"></textarea>
		  </div>
		  <div id="TellAFriend_BoxLabel">
			<input type="button" name="button" class="TellAFriend_Button" value="Submit" onClick="javascript:TellAFriend_Submit(this.parentNode,'<?php echo home_url(); ?>');">
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
	
	$admin_email = get_option('admin_email');
	if($admin_email == "")
	{
		$admin_email = "admin@tellafriend.com";
	}
	$url = home_url();
	
	add_option('TellAFriend_Title', "Tell A Friend");
	add_option('TellAFriend_Fromemail', $admin_email);
	add_option('TellAFriend_On_MyEmail', "");
	add_option('TellAFriend_On_Subject', "Link recommended by ##USERNAME##");
	add_option('TellAFriend_Caption', "<img src='".get_option('siteurl')."/wp-content/plugins/wp-tell-a-friend-popup-form/tell-a-friend.jpg' />");
	add_option('TellAFriend_Adminmail_Content', "Hi Admin, Someone (##USERNAME##) used our form to send link (##LINK##) to friend ##FRIENDEMAIL## with the message \r\n\r\n ##MESSAGE## ");
	add_option('TellAFriend_Usermail_Content', "Hi, Your friend ##USERNAME## has sent you a link to web site. \r\n\r\n ##LINK## \r\n\r\n ##MESSAGE##");
	add_option('TellAFriend_homeurl', $url);
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
	echo '<p>';
	_e('Check official website for more information', 'tell-a-friend');
	?> <a target="_blank" href="http://www.gopiplus.com/work/2012/05/21/wordpress-plugin-wp-tell-a-friend-popup-form/"><?php _e('click here', 'tell-a-friend'); ?></a></p><?php
}

function TellAFriend_widget_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget( __('Tell A Friend', 'tell-a-friend'), __('Tell A Friend', 'tell-a-friend'), 'TellAFriend_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control( __('Tell A Friend', 'tell-a-friend'), array( __('Tell A Friend', 'tell-a-friend'), 'widgets'), 'TellAFriend_control');
	} 
}

function TellAFriend_deactivation() 
{
	// No deactivation
}

function TellAFriend_admin()
{
	?>
	<div class="wrap">
	<div class="form-wrap">
    <div id="icon-plugins" class="icon32 icon32-posts-post"></div>
	<h2><?php _e('Tell a friend popup form', 'tell-a-friend'); ?></h2>
	<?php
	global $wpdb, $wp_version;
	$TellAFriend_Title = get_option('TellAFriend_Title');
	$TellAFriend_Fromemail = get_option('TellAFriend_Fromemail');
	$TellAFriend_On_MyEmail = get_option('TellAFriend_On_MyEmail');
	$TellAFriend_On_Subject = get_option('TellAFriend_On_Subject');
	$TellAFriend_Caption = get_option('TellAFriend_Caption');
	$TellAFriend_Adminmail_Content = get_option('TellAFriend_Adminmail_Content');
	$TellAFriend_Usermail_Content = get_option('TellAFriend_Usermail_Content');
	$TellAFriend_homeurl = get_option('TellAFriend_homeurl');
	
	if (isset($_POST['TellAFriend_submit'])) 
	{
		$TellAFriend_Title = stripslashes($_POST['TellAFriend_Title']);
		$TellAFriend_Fromemail = stripslashes($_POST['TellAFriend_Fromemail']);
		$TellAFriend_On_MyEmail = stripslashes($_POST['TellAFriend_On_MyEmail']);
		$TellAFriend_On_Subject = stripslashes($_POST['TellAFriend_On_Subject']);
		$TellAFriend_Caption = stripslashes($_POST['TellAFriend_Caption']);
		$TellAFriend_Adminmail_Content = stripslashes($_POST['TellAFriend_Adminmail_Content']);
		$TellAFriend_Usermail_Content = stripslashes($_POST['TellAFriend_Usermail_Content']);
		$TellAFriend_homeurl = stripslashes($_POST['TellAFriend_homeurl']);
		
		update_option('TellAFriend_Title', $TellAFriend_Title );
		update_option('TellAFriend_Fromemail', $TellAFriend_Fromemail );
		update_option('TellAFriend_On_MyEmail', $TellAFriend_On_MyEmail );
		update_option('TellAFriend_On_Subject', $TellAFriend_On_Subject );
		update_option('TellAFriend_Caption', $TellAFriend_Caption );
		update_option('TellAFriend_Adminmail_Content', $TellAFriend_Adminmail_Content );
		update_option('TellAFriend_Usermail_Content', $TellAFriend_Usermail_Content );
		update_option('TellAFriend_homeurl', $TellAFriend_homeurl );
		
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'tell-a-friend'); ?></strong></p>
		</div>
		<?php
	}
	echo '<h3>'.__('Plugin setting', 'tell-a-friend').'</h3>';
	echo '<form name="form_gCF" method="post" action="">';
	
	echo '<label for="tag-title">'.__('Title', 'tell-a-friend').'</label><input  style="width: 350px;" type="text" value="';
	echo $TellAFriend_Title . '" name="TellAFriend_Title" id="TellAFriend_Title" /><p>'.__('Enter your popup box title.', 'tell-a-friend').'</p>';
	
	echo '<label for="tag-title">'.__('From email:', 'tell-a-friend').'</label><input  style="width: 350px;" type="text" value="';
	echo $TellAFriend_Fromemail . '" name="TellAFriend_Fromemail" id="TellAFriend_Fromemail" /><p>'.__('Enter From email address for your mail.', 'tell-a-friend').'</p>';
	
	echo '<label for="tag-title">'.__('Admin email:', 'tell-a-friend').'</label><input style="width: 350px;" type="text" value="';
	echo $TellAFriend_On_MyEmail . '" name="TellAFriend_On_MyEmail" maxlength="200" id="TellAFriend_On_MyEmail" /><br />';
	echo '<p>'.__('Enter admin email address to receive  Tell A Friend Mail copy.', 'tell-a-friend').'</p>';
	
	echo '<label for="tag-title">'.__('Email subject:', 'tell-a-friend').'</label><input style="width: 350px;" type="text" value="';
	echo $TellAFriend_On_Subject . '" name="TellAFriend_On_Subject" maxlength="200" id="TellAFriend_On_Subject" /><br />';
	echo '<p>'.__('Enter mail subject.', 'tell-a-friend').'</p>';
	
	echo '<label for="tag-title">'.__('Link button/text:', 'tell-a-friend').'</label><input style="width: 800px;" type="text" value="';
	echo $TellAFriend_Caption . '" name="TellAFriend_Caption" id="TellAFriend_Caption" /><br />';
	echo '<p>'.__('This box is to add the contact us Image or text, Entered value will display in the front end.', 'tell-a-friend').'</p>';
	
	?>
    <table width="640" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><label for="tag-title"><?php _e('Admin mail content:', 'tell-a-friend'); ?></label></td>
        <td><label for="tag-title"><?php _e('User mail content:', 'tell-a-friend'); ?></label></td>
      </tr>
      <tr>
        <td><textarea name="TellAFriend_Adminmail_Content" rows="6" id="j_adminmail_content" style="width: 450px;"><?php echo $TellAFriend_Adminmail_Content ?></textarea></td>
        <td><textarea name="TellAFriend_Usermail_Content" rows="6" id="j_usermail_content" style="width: 450px;"><?php echo $TellAFriend_Usermail_Content ?></textarea></td>
      </tr>
      <tr><td colspan="2"><p>Keywords: ##USERNAME## , ##LINK## , ##FRIENDEMAIL## , ##MESSAGE##</p></td></tr>
    </table>
	
	<h3><?php _e('Security Check (Spam Stopper)', 'tell-a-friend'); ?></h3>
	<label for="tag-width"><?php _e('Home URL', 'tell-a-friend'); ?></label>
	<input name="TellAFriend_homeurl" type="text" value="<?php echo $TellAFriend_homeurl; ?>"  id="TellAFriend_homeurl" size="50" maxlength="500">
	<p><?php _e	('This home URL is for security check. We can submit the form only on this website. ', 'tell-a-friend'); ?></p>
	
	<div style="height:8px;"></div>
	<input type="submit" id="TellAFriend_submit" name="TellAFriend_submit" lang="publish" class="button add-new-h2" value="<?php _e('Update Setting', 'tell-a-friend'); ?>" />
	<input name="Help" lang="publish" class="button add-new-h2" onclick="window.open('http://www.gopiplus.com/work/2012/05/21/wordpress-plugin-wp-tell-a-friend-popup-form/');" value="<?php _e('Help', 'tell-a-friend'); ?>" type="button" />
	</form>
	</div>
	<h3><?php _e('Plugin configuration option', 'tell-a-friend'); ?></h3>
	<ol>
		<li><?php _e('Drag and drop the plugin widget to your sidebar.', 'tell-a-friend'); ?></li>
		<li><?php _e('Add plugin in the posts or pages using short code.', 'tell-a-friend'); ?></li>
		<li><?php _e('Add directly in to the theme using PHP code.', 'tell-a-friend'); ?></li>
	</ol>
	<p class="description">
		<?php _e('Check official website for more information', 'tell-a-friend'); ?> 
		<a target="_blank" href="http://www.gopiplus.com/work/2012/05/21/wordpress-plugin-wp-tell-a-friend-popup-form/"><?php _e('click here', 'tell-a-friend'); ?></a>
	</p>
	</div>
	<?php
}


function TellAFriend_add_to_menu() 
{
	add_options_page(__('Tell A friend', 'tell-a-friend'), __('Tell A friend', 'tell-a-friend'), 'manage_options', __FILE__, 'TellAFriend_admin' );
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
	$siteurl = "'" . home_url() . "'";
	$close = "javascript:TellAFriend_HideForm('TellAFriend_BoxContainer','TellAFriend_BoxContainerFooter');";
	$open = 'javascript:TellAFriend_OpenForm("TellAFriend_BoxContainer","TellAFriend_BoxContainerBody","TellAFriend_BoxContainerFooter");';
	
	$html = "<a href='".$open."'>".$TellAFriend_Caption."</a>";
	$html .= '<div style="display: none;" id="TellAFriend_BoxContainer">';
	  $html .= '<div id="TellAFriend_BoxContainerHeader">';
		$html .= '<div id="TellAFriend_BoxTitle">'.$PopupContact_title.'</div>';
		$html .= '<div id="TellAFriend_BoxClose"><a href="'.$close .'">'.__('Close', 'tell-a-friend').'</a></div>';
	  $html .= '</div>';
	  $html .= '<div id="TellAFriend_BoxContainerBody">';
		$html .= '<form action="#" name="TellAFriend_Form" id="TellAFriend_Form">';
		  $html .= '<div id="TellAFriend_BoxAlert"> <span id="TellAFriend_alertmessage"></span> </div>';
		  $html .= '<div id="TellAFriend_BoxLabel_Page"> '.__('Your Name', 'tell-a-friend').' </div>';
		  $html .= '<div id="TellAFriend_BoxLabel_Page">';
			$html .= '<input name="TellAFriend_name" class="TellAFriend_TextBox" type="text" id="TellAFriend_name" maxlength="120">';
		  $html .= '</div>';
		  $html .= '<div id="TellAFriend_BoxLabel_Page"> '.__('Friend Email', 'tell-a-friend').' </div>';
		  $html .= '<div id="TellAFriend_BoxLabel_Page">';
			$html .= '<input name="TellAFriend_email" class="TellAFriend_TextBox" type="text" id="TellAFriend_email" maxlength="120">';
		  $html .= '</div>';
		  $html .= '<div id="TellAFriend_BoxLabel_Page"> '.__('Enter Message To Friend', 'tell-a-friend').' </div>';
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

function TellAFriend_textdomain() 
{
	  load_plugin_textdomain( 'tell-a-friend', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function TellAFriend_plugin_query_vars($vars) 
{
	$vars[] = 'tellafriend';
	return $vars;
}

function TellAFriend_plugin_parse_request($qstring)
{
	if (array_key_exists('tellafriend', $qstring->query_vars)) 
	{
		$page = $qstring->query_vars['tellafriend'];
		switch($page)
		{
			case 'send-mail':
				$ToEmail = isset($_POST['TellAFriend_email']) ? $_POST['TellAFriend_email'] : '';
				if($ToEmail <> "")
				{
					if (!filter_var($ToEmail, FILTER_VALIDATE_EMAIL))
					{
						echo "invalid-email";
					}
					else
					{
						$homeurl = get_option('TellAFriend_homeurl');
						if($homeurl == "")
						{
							$homeurl = home_url();
						}
						
						$samedomain = strpos($_SERVER['HTTP_REFERER'], $homeurl);
						if ( ($samedomain !== false) && ($samedomain < 5) ) 
						{
							$SenderName = isset($_POST['TellAFriend_name']) ? $_POST['TellAFriend_name'] : '';
							$ToEmail = isset($_POST['TellAFriend_email']) ? $_POST['TellAFriend_email'] : '';

							$Link = $_POST['TellAFriend_Link'];
							$ToMessage = stripslashes($_POST['TellAFriend_message']);
							$FromEmail = get_option('TellAFriend_Fromemail');
							$AdminEmail = get_option('TellAFriend_On_MyEmail');
							$Subject = stripslashes(get_option('TellAFriend_On_Subject'));
							$Adminmail_Content = stripslashes(get_option('TellAFriend_Adminmail_Content'));
							$Usermail_Content = stripslashes(get_option('TellAFriend_Usermail_Content'));
							
							$headers  = "From: \"" . $FromEmail . "\" <$FromEmail>\n";
							$headers .= "Return-Path: <" . $FromEmail . ">\n";
							$headers .= "Reply-To: \"" . $FromEmail . "\" <" . $FromEmail . ">\n";
							$headers .= "X-Mailer: PHP" . phpversion() . "\n";
							$headers .= "MIME-Version: 1.0\n";
							$headers .= "Content-Type: " . get_bloginfo('html_type') . "; charset=\"". get_bloginfo('charset') . "\"\n";
							$headers .= "Content-type: text/html\r\n";
							
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
							
							@wp_mail($ToEmail, $Subject, $Usermail_Content, $headers);
							if($AdminEmail <> "")
							{
								@wp_mail($AdminEmail, $Subject, $Adminmail_Content, $headers);
							}
							echo "mail-sent-successfully";
						}
						else
						{
							echo "there-was-problem";
						}
					}
				}
				else
				{
					echo "empty-email";
				}
				die();
				break;	
		}
	}
}
		

add_action('parse_request', 'TellAFriend_plugin_parse_request');
add_filter('query_vars', 'TellAFriend_plugin_query_vars');
add_action('plugins_loaded', 'TellAFriend_textdomain');
add_shortcode( 'tell-a-friend', 'TellAFriend_shortcode' );
add_action('wp_enqueue_scripts', 'TellAFriend_add_javascript_files');
add_action("plugins_loaded", "TellAFriend_widget_init");
register_activation_hook(__FILE__, 'TellAFriend_install');
register_deactivation_hook(__FILE__, 'TellAFriend_deactivation');
add_action('init', 'TellAFriend_widget_init');
?>