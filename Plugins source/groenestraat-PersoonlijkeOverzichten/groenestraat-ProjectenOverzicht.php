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
		makePersProjectenShortcode('Mijn projecten','[persoonlijke_projecten]','persoonlijke projecten','publish','page','closed');
	}

	function makePersProjectenShortcode($title,$content,$post_name,$post_status,$post_type,$ping_status)
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

	$subscriber = "_subscriberId";
	$userId = get_current_user_id(); 

	if(is_user_logged_in())
	{
		//ophalen subscribers
		global $post;

		$projectenSubscribed = array(
					'post_author' => $userId,
					'post_type' => 'projecten',
					'order' => 'ASC',
					'orderby' => 'date',
					'meta_key' => '_subscriberId',
					'meta_compare' => '=',
            		'meta_query' => array(
                		array(
                        'key' => '_subscriberId',
                        'value' => $userId)));

		$projectenAdmin = array(
					'author' => $userId,
					'post_type' => 'projecten',
					'order' => 'ASC',
					'orderby' => 'date'
					);


		//subscribers 
		$the_query_subscribed = new WP_Query($projectenSubscribed);
		$the_query_admin = new WP_Query($projectenAdmin);
				

		//projecten waarop ingelogde gebruiker is op gesubscribed (ingeschreven)
		if ($the_query_subscribed->have_posts()) {
				while ($the_query_subscribed->have_posts() ) {
							$the_query_subscribed->the_post();	

							echo '<h2>' . get_the_title() . '</h2>';
							$projectAdminId = $post->post_author;

							$street = get_post_meta($post->ID, "_projectStreet")[0];
							$city = get_post_meta($post->ID, "_projectCity")[0];
							$zipcode = get_post_meta($post->ID, "_projectZipcode")[0];

							if(!empty($street) && !empty($city) && !empty($zipcode))
							{
									print '<h1>' . $title . '</h1>';
									print '<strong>Street: </strong> ' . $street . '<br />';
									print '<strong>City: </strong> ' . $city . '<br />';;
									print '<strong>Zipcode: </strong> ' . $zipcode . '<br />';;
									print '<strong>Omschrijving: </strong><p>' . get_the_excerpt() . '</p>';
							}
				}

		}

		//projecten die de ingelogde gebruiker zelf heeft aangemaakt
		if ($the_query_admin->have_posts()) {
				while ($the_query_admin->have_posts() ) {
							$the_query_admin->the_post();	

							echo '<h2>' . get_the_title() . '</h2>';
							$projectAdminId = $post->post_author;

							$street = get_post_meta($post->ID, "_projectStreet")[0];
							$city = get_post_meta($post->ID, "_projectCity")[0];
							$zipcode = get_post_meta($post->ID, "_projectZipcode")[0];

							if(!empty($street) && !empty($city) && !empty($zipcode))
							{
									print '<h1>' . $title . '</h1>';
									print '<strong>Street: </strong> ' . $street . '<br />';
									print '<strong>City: </strong> ' . $city . '<br />';;
									print '<strong>Zipcode: </strong> ' . $zipcode . '<br />';;
									print '<strong>Omschrijving: </strong><p>' . get_the_excerpt() . '</p>';
									if($userId == $projectAdminId)
									{
										print '<a href="'.site_url().'/bewerk-project?project='. $post->ID .'">Bewerk project</a>';
									}
							}
				}

		}
	}

}
?>