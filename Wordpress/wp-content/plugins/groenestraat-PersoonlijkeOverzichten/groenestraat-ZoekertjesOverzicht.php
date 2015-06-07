<?php
	/*
		Plugin Name: Groenestraat Persoonlijke Zoekertjes Overzicht
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont de zoekertjes waarvan de ingelogde user lid van is. 
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add Shortcodes
	*/
		
	add_shortcode('persoonlijke_zoekertjes','prowpt_persoonlijkeZoekertjesOverzicht');

	register_activation_hook(__FILE__, 'prowp_persoonlijkeZoekertjes_install');

	function prowp_persoonlijkeZoekertjes_install()
	{
		//Persoonlijke overzicht van zoekertjes
		makePersZoekertjesShortcode('Persoonlijke zoekertjes','[persoonlijke_zoekertjes]','persoonlijke zoekertjes','publish','page','closed');
	}

	function makePersZoekertjesShortcode($title,$content,$post_name,$post_status,$post_type,$ping_status)
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

function prowpt_persoonlijkeZoekertjesOverzicht()
{
	global $post;

	$zoekertjes = array();
	$userId = get_current_user_id(); 

	if(is_user_logged_in())
	{
		global $wpdb;
		//zoekertjes ophalen waar hij zelf auteur van is.
		$post_type = "zoekertjes";
		$post_author = $userId;
		$results = $wpdb->get_results($wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s AND post_author = %d ", $post_type, $post_author), ARRAY_A);

			foreach($results as $result)
			{
					$zoekertjesId = $result['ID'];
					$zoekertjes[] = get_post($zoekertjesId, OBJECT);
			}

		foreach($zoekertjes as $zoekertje)
		{
			$zoekertjeAdminId = $zoekertje->post_author;

			$title = $zoekertje->post_title;
			$omschrijving = $zoekertje->post_content;

			$adPrice = get_post_meta($zoekertje->ID, "_adPrice")[0];
			$adLocation = get_post_meta($zoekertje->ID, "_adLocation")[0];

			if(!empty($title) && !empty($omschrijving) && !empty($adPrice) && !empty($adLocation))
			{
				print '<h1>' . $title . '</h1>';
				print '<strong>Prijs: </strong> ' . $adPrice . '<br />';
				print '<strong>Locatie: </strong> ' . $adLocation . '<br />';;
				print '<strong>Omschrijving: </strong><p>' . $omschrijving . '</p>';

				print '<a href="'.site_url().'/bewerk-zoekertje?zoekertje='. $zoekertje->ID .'">Bewerk zoekertje</a>';
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