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
		//zoekertjes ophalen die ingelogde gebruiker zelf heeft aangemaakt.
		$post_author = $userId;

		global $post;
		$the_query = new WP_Query(
				array(
					'author' => $userId,
					'post_type' => 'zoekertjes',
					'order' => 'ASC',
					'orderby' => 'date')
				);

		if ($the_query->have_posts()) {
				while ($the_query->have_posts() ) {
						$the_query->the_post();	

						echo '<h2>' . get_the_title() . '</h2>';

						$adPrice = get_post_meta($post->ID, "_adPrice")[0];
						$adLocation = get_post_meta($post->ID, "_adLocation")[0];

						if(!empty($adPrice) && !empty($adLocation))
						{
							print '<strong>Prijs: </strong> ' . $adPrice . '<br />';
							print '<strong>Locatie: </strong> ' . $adLocation . '<br />';;
							print '<strong>Omschrijving: </strong><p>' . get_the_excerpt() . '</p>';
							print '<a href="'.site_url().'/bewerk-zoekertje?zoekertje='. $post->ID .'">Bewerk zoekertje</a>';
						}

				}
		}
		else
		{
			print "Er werden geen zoekertjes gevonden.";
		}
	}
	else
	{
		//niet-ingelogd
		echo "U moet zich aanmelden om deze pagina te bekijken.";
	}

}
?>