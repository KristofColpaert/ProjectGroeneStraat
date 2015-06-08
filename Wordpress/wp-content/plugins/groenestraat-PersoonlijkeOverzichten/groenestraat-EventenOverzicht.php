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
	$userId = get_current_user_id(); 

	if(is_user_logged_in())
	{
		//eventen ophalen waar hij zelf auteur van is.
		$post_author = $userId;

		global $post;
		$the_query = new WP_Query(
				array(
					'author' => $userId,
					'post_type' => 'events',
					'order' => 'ASC',
					'orderby' => 'date')
				);

		if ($the_query->have_posts()) {
					while ($the_query->have_posts() ) {
						$the_query->the_post();	

						echo '<h2>' . get_the_title() . '</h2>';

						$eventTime = get_post_meta($post->ID, "_eventTime")[0];
						$eventEndTime = get_post_meta($post->ID, "_eventEndTime")[0];
						$eventLocation = get_post_meta($post->ID, "_eventLocation")[0];

						if(!empty($eventTime) && !empty($eventEndTime) && !empty($eventLocation))
						{
							print '<strong>Startdatum: </strong> ' . $eventTime . '<br />';
							print '<strong>Einddatum: </strong> ' . $eventEndTime . '<br />';
							print '<strong>Locatie: </strong> ' . $eventLocation . '<br />';
							print '<strong>Omschrijving: </strong><p>' . get_the_excerpt()  . '</p>';
							print '<a href="'.site_url().'/bewerk-event?event='. $post->ID .'">Bewerk event</a>';
						}

					}
		}
		else
		{
			print "Er werden geen events gevonden.";
		}

	}
	else
	{
		//niet-ingelogd
		echo "U moet zich aanmelden om deze pagina te bekijken.";
	}

}
?>