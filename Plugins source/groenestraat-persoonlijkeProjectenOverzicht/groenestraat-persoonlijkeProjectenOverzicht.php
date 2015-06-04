<?php
	/*
		Plugin Name: Groenestraat Persoonlijke Projecten Overzicht
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont de projecten waarvan de ingelogde user lid van is. 
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add Shortcodes
	*/
		
	add_shortcode('persoonlijke_projecten','prowpt_persoonlijkeProjectenOverzicht');

	register_activation_hook(__FILE__, 'prowp_persoonlijkeProjecten_install');

	function prowp_persoonlijkeProjecten_install()
	{
		//Persoonlijke overzicht van projecten
		makeShortcode('Persoonlijke projecten','[persoonlijke_projecten]','persoonlijke projecten','publish','page','closed');
	}

	function makeShortcode($title,$content,$post_name,$post_status,$post_type,$ping_status)
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




function prowpt_persoonlijkeProjectenOverzicht()
{
	global $post;

	$userId = get_current_user_id();
	$projecten = array();

	if($userId > 0)
	{
		//ingelogd 
		//--> subscriber_id --> degene die zich hebben ingeschreven op een project.

		//https://tommcfarlin.com/get-post-id-by-meta-value/
		global $wpdb;
		$results = $wpdb->get_results( "select post_id, meta_value from $wpdb->postmeta where meta_key = '_subscriberId'", ARRAY_A );

		foreach($results as $result)
		{
			//voor iedere waarde met subscriberId zullen we het project gaan ophalen.
			$projectId = $result['post_id'];
			$projecten[] = get_post($projectId, OBJECT);
		}

		foreach($projecten as $project)
		{
			$title = $project->post_title;
			print '<h1>' . $title . '</h1>';
		}

	}
	else
	{
		//niet-ingelogd
		echo "U moet zich aanmelden om deze pagina te bekijken.";
	}

}
?>