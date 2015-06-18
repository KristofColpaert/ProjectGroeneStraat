<?php
	/*
		Plugin Name: Groenestraat Eventlid
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin zorgt ervoor dat ingelogde gebruikers een event kunnen toevoegen aan hun persoonlijke kalender.
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert, Koen Van Crombrugge en Vincent De Ridder
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	/*
		Add actions
	*/
		
	add_action('wp_ajax_nopriv_link_event_user', 'link_event_user');
	add_action('wp_ajax_link_event_user', 'link_event_user');

	add_action('wp_ajax_nopriv_check_event_user', 'check_event_user');
	add_action('wp_ajax_check_event_user', 'check_event_user');

	/*
		Plugin methods
	*/

	function link_event_user()
	{
		if(isset($_POST['user_id']) && isset($_POST['event_id']) && isset($_POST['todo']))
		{
			$todo = $_POST['todo'];
			$event_id = $_POST['event_id'];
			$event = get_post($event_id, OBJECT);
			$user_id = $_POST['user_id'];
			$user = get_user_by('id', $user_id);
			if($user != null && $event != null)
			{
				if($todo == 'subscribe')
				{
					add_user_meta($user->ID, '_eventCalendar', $event->ID);
					echo 'yes';
					die();
				}
				else
				{
					delete_user_meta($user->ID, '_eventCalendar', $event->ID);
					echo 'yes';
					die();
				}
			}

			else
			{
				echo 'no';
				die();
			}
		}
	}

	function check_event_user()
	{
		if(isset($_POST['user_id']) && isset($_POST['event_id']))
		{
			$event_id = $_POST['event_id'];
			$event = get_post($event_id, OBJECT);
			$user_id = $_POST['user_id'];
			$user = get_user_by('id', $user_id);
			if($user != null && $event != null)
			{
				$meta = get_user_meta($user->ID, '_eventCalendar');
				foreach($meta as $value)
				{
					if($value == $event->ID)
					{
						echo 'true';
						die();
					}
				}
			}
		}
		echo 'false';
		die();
	}
?>