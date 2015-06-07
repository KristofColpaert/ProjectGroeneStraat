<?php
	/*
		Plugin Name: Groenestraat Delete Manager
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin zorgt ervoor dat wanneer events en projecten verwijderd worden, de metadata automatisch ook gewist wordt.
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	/*
		Add actions
	*/

	add_action('before_delete_post', 'prowp_delete_post_meta');

	/*
		Methods
	*/

	function prowp_delete_post_meta($postId)
	{
		global $post_type;
		global $post;

		if($post_type == 'events')
		{
			$args = array(
				'meta_key' => '_eventCalendar',
				'meta_value' => $post->ID,
				'meta_compare' => '='
			);

			$users = get_users($args);

			foreach($users as $user)
			{
				delete_user_meta($user->ID, '_eventCalendar', $post->ID);
			}
		}

		if($post_type == 'project')
		{
			delete_post_meta($post->ID, '_subscriberId');
		}
	}
?>