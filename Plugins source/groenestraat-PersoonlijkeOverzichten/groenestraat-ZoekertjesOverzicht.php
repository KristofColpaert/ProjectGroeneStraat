<?php
	/*
		Plugin Name: Groenestraat Mijn Zoekertjes Overzicht
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont de zoekertjes waarvan de ingelogde gebruiker lid van is. 
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaertt
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add Shortcodes
	*/
		
	add_shortcode('mijn_zoekertjes','prowpt_persoonlijkeZoekertjesOverzicht');

	register_activation_hook(__FILE__, 'prowp_persoonlijkeZoekertjes_install');

	function prowp_persoonlijkeZoekertjes_install()
	{
		//Persoonlijke overzicht van zoekertjes
		makePersZoekertjesShortcode('Mijn zoekertjes','[mijn_zoekertjes]','Mijn zoekertjes','publish','page','closed');
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

						?>
							<h2><?php echo get_the_title(); ?></h2>
						<?php
						$adPrice = get_post_meta($post->ID, "_adPrice")[0];
						$adLocation = get_post_meta($post->ID, "_adLocation")[0];

						if(!empty($adPrice) && !empty($adLocation))
						{
							?>
							<strong>Prijs: </strong><p><?php echo $adPrice; ?></p>
							<strong>Locatie: </strong><p><?php echo $adLocation; ?></p>
							<strong>Omschrijving: </strong><p><?php echo get_the_excerpt(); ?></p>
							<a href="<?php echo site_url().'/bewerk-zoekertje?zoekertje='. $post->ID; ?>">Bewerk zoekertje</a>
							<?php
						}

				}
		}
		else
		{
			?>
				<p>Er werden geen zoekertjes gevonden.</p>
			<?php
		}
	}
	else
	{
			?>
				<p>U moet zich aanmelden om deze pagina te bekijken.</p>
			<?php
	}

}
?>