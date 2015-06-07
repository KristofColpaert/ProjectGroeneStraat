<?php
	/*
		Plugin Name: Groenestraat Persoonlijke Eventen Overzicht
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont de eventen waarvan de ingelogde user lid van is. 
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add Shortcodes
	*/
		
	add_shortcode('persoonlijke_events','prowpt_persoonlijkeEventenOverzicht');

	register_activation_hook(__FILE__, 'prowp_persoonlijkeEventen_install');

	function prowp_persoonlijkeEventen_install()
	{
		//Persoonlijke overzicht van events
		makePersEventenShortcode('Persoonlijke events','[persoonlijke_events]','persoonlijke events','publish','page','closed');
	}

	function makePersEventenShortcode($title,$content,$post_name,$post_status,$post_type,$ping_status)
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




function prowpt_persoonlijkeEventenOverzicht()
{
	global $post;

	$eventen = array();
	$userId = get_current_user_id(); 

	if(is_user_logged_in())
	{
		global $wpdb;
		//eventen ophalen waar hij zelf auteur van is.
		$post_type = "events";
		$post_author = $userId;
		$results = $wpdb->get_results($wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s AND post_author = %d ", $post_type, $post_author), ARRAY_A);

			foreach($results as $result)
			{
					$eventId = $result['ID'];
					$eventen[] = get_post($eventId, OBJECT);
			}

		foreach($eventen as $event)
		{
			$eventAdminId = $event->post_author;
			$title = $event->post_title;
			$omschrijving = $event->post_content;

			$eventTime = get_post_meta($event->ID, "_eventTime")[0];
			$eventEndTime = get_post_meta($event->ID, "_eventEndTime")[0];
			$eventLocation = get_post_meta($event->ID, "_eventLocation")[0];

			if(!empty($title) && !empty($omschrijving) && !empty($eventTime) && !empty($eventEndTime) && !empty($eventLocation))
			{
				print '<h1>' . $title . '</h1>';
				print '<strong>Startdatum: </strong> ' . $eventTime . '<br />';
				print '<strong>Einddatum: </strong> ' . $eventEndTime . '<br />';;
				print '<strong>Locatie: </strong> ' . $eventLocation . '<br />';;
				print '<strong>Omschrijving: </strong><p>' . $omschrijving . '</p>';
				print '<a href="'.site_url().'/bewerk-event?event='. $event->ID .'">Bewerk event</a>';
			}
		}

	}
	else
	{
		//niet-ingelogd
		echo "U moet zich aanmelden om deze pagina te bekijken.";
	}

}
?>