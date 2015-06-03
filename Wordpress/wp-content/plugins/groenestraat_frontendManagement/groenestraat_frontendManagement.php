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
		//kalender aanmaken
		makeShortcodePage('Kalender','[personal_calendar]','kalender','publish','page','closed');

		//delete action
		makeShortcodePage('Verwijder event','[delete_event]','verwijder event','publish','page','closed');
		makeShortcodePage('Verwijder zoekertje','[delete_zoekertje]','verwijder zoekertje','publish','page','closed');
		makeShortcodePage('Verwijder project','[delete_project]','verwijder project','publish','page','closed');
		
		//new action
		makeShortcodePage('Nieuw event','[new_event]','nieuw event','publish','page','closed');
		makeShortcodePage('Nieuw zoekertje','[new_zoekertje]','nieuw zoekertje','publish','page','closed');
		makeShortcodePage('Nieuw project','[new_project]','nieuw project','publish','page','closed');
	
		//update action
		makeShortcodePage('Bewerk event','[edit_event]','bewerk event','publish','page','closed');
		makeShortcodePage('Bewerk zoekertje','[edit_zoekertje]','bewerk zoekertje','publish','page','closed');
		makeShortcodePage('Bewerk project','[edit_project]','bewerk project','publish','page','closed');

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