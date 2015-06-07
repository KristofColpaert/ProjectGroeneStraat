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
		global $wpdb;
		//projecten ophalen waar hij op gesubscribed heeft.
		$results = $wpdb->get_results($wpdb->prepare( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %d", $subscriber, $userId), ARRAY_A);
			
			foreach($results as $result)
			{
					//voor iedere waarde met subscriberId zullen we het project gaan ophalen.
					$projectId = $result['post_id'];
					$projecten[] = get_post($projectId, OBJECT);
			}

		//projecten ophalen waar hij zelf auteur van is.
		$post_type = "projecten";
		$post_author = $userId;
		$results = $wpdb->get_results($wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s AND post_author = %d ", $post_type, $post_author), ARRAY_A);

			foreach($results as $result)
			{
					$projectId = $result['ID'];
					$projecten[] = get_post($projectId, OBJECT);
			}

		foreach($projecten as $project)
		{
			$projectAdminId = $project->post_author;

			$title = $project->post_title;
			$omschrijving = $project->post_content;

			$street = get_post_meta($project->ID, "_projectStreet")[0];
			$city = get_post_meta($project->ID, "_projectCity")[0];
			$zipcode = get_post_meta($project->ID, "_projectZipcode")[0];

			if(!empty($title) && !empty($omschrijving) && !empty($street) && !empty($city) && !empty($zipcode))
			{
				print '<h1>' . $title . '</h1>';
				print '<strong>Street: </strong> ' . $street . '<br />';
				print '<strong>City: </strong> ' . $city . '<br />';;
				print '<strong>Zipcode: </strong> ' . $zipcode . '<br />';;
				print '<strong>Omschrijving: </strong><p>' . $omschrijving . '</p>';
				if($userId == $projectAdminId)
				{
					print '<a href="'.site_url().'/bewerk-project?project='. $project->ID .'">Bewerk project</a>';
				}
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