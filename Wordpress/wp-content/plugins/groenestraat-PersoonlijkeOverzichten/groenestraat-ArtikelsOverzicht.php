<?php
	/*
		Plugin Name: Groenestraat Persoonlijke Artikels Overzicht
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont de artikels die de ingelogde user aangemaakt heeft. 
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add Shortcodes
	*/
		
	add_shortcode('persoonlijke_artikels','prowpt_persoonlijkeArtikelsOverzicht');

	register_activation_hook(__FILE__, 'prowp_persoonlijkeArtikels_install');

	function prowp_persoonlijkeArtikels_install()
	{
		//Persoonlijke overzicht van artikels
		makePersArtikelsShortcode('Persoonlijke artikels','[persoonlijke_artikels]','persoonlijke artikels','publish','page','closed');
	}

	function makePersArtikelsShortcode($title,$content,$post_name,$post_status,$post_type,$ping_status)
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

function prowpt_persoonlijkeArtikelsOverzicht()
{
	global $post;

	$artikels = array();
	$userId = get_current_user_id(); 

	if(is_user_logged_in())
	{	
		//artikels ophalen die ingelogde gebruiker zelf heeft aangemaakt.
		$post_author = $userId;

		global $post;
		$the_query = new WP_Query(
				array(
					'author' => $userId,
					'post_type' => 'post',
					'order' => 'ASC',
					'orderby' => 'date')
				);

		if ($the_query->have_posts()) {
				while ($the_query->have_posts() ) {
						$the_query->the_post();	

						echo '<h2>' . get_the_title() . '</h2>';
						echo '<strong>Omschrijving: </strong><p>' . the_content() . '</p>';
							
						echo '<a href="'.site_url().'/bewerk-artikel?artikel='. $post->ID .'">Bewerk artikel</a>';
				}
		}
		else
		{
			print "Er werden geen artikels gevonden.";
		}
	}
	else
	{
		//niet-ingelogd
		echo "U moet zich aanmelden om deze pagina te bekijken.";
	}

}
?>