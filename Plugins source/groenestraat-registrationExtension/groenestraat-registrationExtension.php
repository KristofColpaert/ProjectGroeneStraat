<?php
	/*
		Plugin Name: Groenestraat Registratie Uitbreiding
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin kijkt bij registratie van een gebruiker of het gekozen e-mailadres reeds bestaat.
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert, Koen Van Crombrugge en Vincent De Ridder
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	/*
		Add actions
	*/
		
	add_action('wp_ajax_nopriv_check_email', 'check_email');
	add_action('wp_ajax_check_email', 'check_email');

	/*
		Plugin methods
	*/

	function check_email()
	{
		if(isset($_POST['email']))
		{
			$email = $_POST['email'];
			$users = get_users();
			foreach($users as $user)
			{
				if($user->user_email == $email)
				{
					echo 'true';
					die();
				}
			}
			echo 'false';
			die();
		}
	}
?>