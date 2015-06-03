<?php
	/*
		Plugin Name: Groenestraat Front-end Management
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin zorgt ervoor dat gebruikers de content front-end kunnen beheren.
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

		register_activation_hook(__FILE__, 'prowp_frontendlayout_install');

	function prowp_frontendlayout_install()
	{
		makeShortcodePage('Kalender','[personal_calendar]','kalender','publish','page','closed');
		makeShortcodePage('Delete event','[delete_event]','delete event','publish','page','closed');
		makeShortcodePage('Delete zoekertje','[delete_zoekertje]','delete zoekertje','publish','page','closed');
		makeShortcodePage('Delete project','[delete_project]','delete project','publish','page','closed');
	}

	function makeShortcodePage($title,$content,$post_name,$post_status,$post_type,$ping_status)
	{
		$args = array(
			'post_title' => $title,
			'post_content' => $content,
			'post_name' => $post_name,
			'post_status' => $post_status, 
			'post_type' => $post_type,
			'ping_status' => $ping_status
		);
		wp_insert_post($args);
	}

	include_once('groenestraat_frontendManagement_new_project.php');
	include_once('groenestraat_frontendManagement_edit_project.php');
	include_once('groenestraat_frontendManagement_delete_project.php');

	include_once('groenestraat_frontendManagement_new_event.php');
	include_once('groenestraat_frontendManagement_edit_event.php');
	include_once('groenestraat_frontendManagement_delete_event.php');

	include_once('groenestraat_frontendManagement_new_zoekertje.php');
	include_once('groenestraat_frontendManagement_edit_zoekertje.php');
	include_once('groenestraat_frontendManagement_delete_zoekertje.php');
?>