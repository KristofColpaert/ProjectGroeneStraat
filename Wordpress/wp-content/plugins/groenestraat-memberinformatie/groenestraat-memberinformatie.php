<?php
	/*
		Plugin Name: Groenestraat MemberInformatie
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont het profiel van een user. 
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
			$usermeta = get_user_meta($userid);

			echo '<strong>Gebruikersnaam: </strong><label>'. $user->user_login .'</label><br />';
			echo '<strong>E-mail: </strong><label>'. $user->user_email  .'</label><br />';
			if($usermeta['rpr_straat'][0] != "" || $usermeta['rpr_postcode'][0] != "" || $usermeta['rpr_gemeente'][0] != "")
			{
				echo '<strong>Adres: </strong><label>'. $usermeta['rpr_straat'][0] . ', ' . $usermeta['rpr_postcode'][0] . ' ' . $usermeta['rpr_gemeente'][0] . '</label><br />';
			}
			if($usermeta['rpr_telefoon'][0] != "")
			{
				echo '<strong>Telefoon: </strong><label>'. $usermeta['rpr_telefoon'][0]  .'</label><br />';
			}
			echo get_avatar($userid); 
	}
}

?>

