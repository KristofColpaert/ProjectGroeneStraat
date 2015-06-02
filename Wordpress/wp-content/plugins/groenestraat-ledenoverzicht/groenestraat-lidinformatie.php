<?php
	/*
		Plugin Name: Groenestraat MemberInformatie
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin voegt een overzicht van de leden (users) toe. 
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add actions
	*/

add_shortcode('member_informatie','prowpt_memberinformatie');

function prowpt_memberinformatie()
{
	if(isset($_GET["userid"]))
	{
			$userid = $_GET["userid"];
			$user = get_userdata($userid);
			echo '<strong>Login: </strong><label>'. $user->user_login .'</label><br />';
			echo '<strong>E-mail: </strong><label>'. $user->user_email  .'</label>';
	}
}

?>

