<?php
	/*
		Plugin Name: Groenestraat Ledenoverzicht
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin voegt een overzicht van een gekozen member toe. 
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add actions
	*/

add_shortcode('leden_overzicht','prowpt_ledenoverzicht');
$users = array();	

function prowpt_ledenoverzicht()
{
	$args = array(
		'orderby'      => 'display_name',
		'order'        => 'ASC'
	 ); 

	$users = get_users($args);
	$letters = array();
	foreach ($users as $user) 
	{
		//als user gelijk is als de ingelogde user;
		if($user->ID == get_current_user_id())
		{
			continue;
		}

		$eersteLetter = str_split($user->display_name, 1)[0];

		if(!in_array($eersteLetter, $letters))
		{
			$letters[] = $eersteLetter;
			echo '<h1>' . $eersteLetter . '</h1>';
		}

		echo '<a href="/member-informatie?userid=' . $user->ID . '">' . esc_html( $user->display_name ) . '</a><br />';
	}
}


?>

