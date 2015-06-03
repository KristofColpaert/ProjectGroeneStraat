<?php
	/*
		Plugin Name: Groenestraat Projectlid
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin zorgt ervoor dat ingelogde gebruikers lid kunnen worden van een project.
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	/*
		Add actions
	*/
		
	add_action('wp_ajax_nopriv_link_project_user', 'link_project_user');
	add_action('wp_ajax_link_project_user', 'link_project_user');

	add_action('wp_ajax_nopriv_check_project_user', 'check_project_user');
	add_action('wp_ajax_check_project_user', 'check_project_user');

	/*
		Plugin methods
	*/

	function link_project_user()
	{
		if(isset($_POST['user_id']) && isset($_POST['project_id']) && isset($_POST['todo']))
		{
			$todo = $_POST['todo'];
			$project_id = $_POST['project_id'];
			$project = get_post($project_id, OBJECT);
			$user_id = $_POST['user_id'];
			$user = get_user_by('id', $user_id);
			if($user != null && $project != null)
			{
				if($todo == 'subscribe')
				{
					add_post_meta($project->ID, '_subscriberId', $user->ID);
					echo 'yes';
					die();
				}
				else
				{
					delete_post_meta($project->ID, '_subscriberId', $user->ID);
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

	function check_project_user()
	{
		if(isset($_POST['user_id']) && isset($_POST['project_id']))
		{
			$project_id = $_POST['project_id'];
			$project = get_post($project_id, OBJECT);
			$user_id = $_POST['user_id'];
			$user = get_user_by('id', $user_id);
			if($user != null && $project != null)
			{
				$meta = get_post_meta($project->ID, '_subscriberId');
				foreach($meta as $value)
				{
					if($value == $user->ID)
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