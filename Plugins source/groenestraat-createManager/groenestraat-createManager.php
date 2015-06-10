<?php
	/*
		Plugin Name: Groenestraat Create Manager
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin zorgt ervoor dat wanneer events en projecten aangemaakt worden, de eigenaar ervan automatisch ook subscriber wordt.
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	/*
		Add actions
	*/

	add_action('publish_events', 'prowp_create_event_post_meta', 1, 2);
	add_action('publish_projecten', 'prowp_create_project_post_meta', 1, 2);

	/*
		Plugin methods
	*/

	function prowp_create_event_post_meta($postId, $post)
	{
		$current_user = wp_get_current_user();
		$meta = get_user_meta($current_user->ID, '_eventCalendar');
		if(!in_array($postId, $meta))
		{
			add_user_meta($current_user->ID, '_eventCalendar', $postId);
		}
	}

	function prowp_create_project_post_meta($postId, $post)
	{
		$current_user = wp_get_current_user();
		$meta = get_post_meta($postId, '_subscriberId');
		if(!in_array($current_user->ID, $meta))
		{
			add_post_meta($postId, '_subscriberId', $current_user->ID);
		}
	}
?>