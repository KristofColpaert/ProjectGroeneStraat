<?php
	/*
	Plugin Name: Aanmaken Zoekertje (Groenestraat.be)
	Plugin URI: http://www.groenestraat.be
	Description: This plugin adds the project and project post custom post types
	to your WordPress installation.
	Version: 1.0
	Author: Rodric Degroote
	Author URI: http://www.groenestraat.be
	Text Domain: prowp-plugin
	License: GPLv2
	*/

	// Install the plugin when activated
	//register_activation_hook(__FILE__, 'prowp_install');

	// Add actions
	//Onderstaande code zal ervoor zorgen dat er een nieuw menu-item wordt toegevoegd. Init is altijd verplicht (zal zorgen dat uw custom post type geïnitialiseerd is)
	add_action('init', 'prowp_register_zoekertjes');
	add_action( 'add_meta_boxes', 'add_zoekertjes_metaboxes' );

	//opslaan
	add_action('save_post', 'wpt_save_zoekertjes_meta', 1, 2); 


	// Register the custom projects post type
	function prowp_register_zoekertjes()
	{
		$labels = array('name' => 'Zoekertjes',
				'singular_name' => 'Zoekertje',
				'add_new' => 'Toevoegen Zoekertje',
				'add_new_item' => 'Toevoegen Zoekertje',
				'edit_item' => 'Zoekertje bewerken',
				'new_item' => 'Nieuw Zoekertje',
				'all_items' => 'Alle Zoekertjes',
				'view_item' => 'Zoekertje weergeven',
				'search_items' => 'Zoek Zoekertjes',
				'not_found' => 'Er werden geen Zoekertjes gevonden',
				'not_found_in_trash' => 'Er werden geen Zoekertjes gevonden in de prullenbak',
				'menu_name' => 'Zoekertjes'
			);

		//een array van argumenten
		$args = array(
			'labels' => $labels,
			'public' => true,
			'menu_icon' => 'dashicons-search',
			'supports' => array('title'),
			);

		//het registreren van een nieuwe custom type
		register_post_type('Zoekertjes', $args);
	}

	// Add the Events Meta Boxes
	function add_zoekertjes_metaboxes() {
    	add_meta_box('wpt_zoekertjes_location', 'Informatie', 'wpt_zoekertjes_location', 'Zoekertjes', 'normal', 'high');
	}

	// The Event Location Metabox

	function wpt_zoekertjes_location() {
		global $post;
		
		// Noncename needed to verify where the data originated
		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . 
		wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
		$zoekertjeName = get_post_meta($post->ID, '_zoekertjeName', true);
		$zoekertjeLocatie = get_post_meta($post->ID, '_zoekertjeLocatie', true);
		//echo($eventTime);
		$zoekertjeBeschrijving = get_post_meta($post->ID, '_zoekertjeBeschrijving', true);
		
		// Echo out the field
		echo '<label class="zoekertjeLabel" for="zoekertjeName">Naam</label>';
    	echo '<input id="zoekertjeName" type="text" name="_zoekertjeName" value="' . $zoekertjeName  . '" class="widefat" />';

    	echo '<label for="zoekertjeLabel">Locatie</label>';
    	echo '<input id="zoekertjeLocatie" type="text" name="_zoekertjeLocatie" value="' . $zoekertjeLocatie . '" class="widefat" />';    

  		echo '<label for="zoekertjeLabel">Meer info</label>';
    	echo '<textarea id="zoekertjeBeschrijving" type="text" name="_zoekertjeBeschrijving" class="widefat" >' .  $zoekertjeBeschrijving . '</textarea>';
	}


	// Save the Metabox Data
	function wpt_save_zoekertjes_meta($post_id, $post) {
		
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
		}

		// Is the user allowed to edit the post or page?
		if ( !current_user_can( 'edit_post', $post->ID ))
			return $post->ID;

		// OK, we're authenticated: we need to find and save the data
		// We'll put it into an array to make it easier to loop though.
		
		$events_meta['_zoekertjeName'] = $_POST['_zoekertjeName'];
		$events_meta['_zoekertjeLocatie'] = $_POST['_zoekertjeLocatie'];
		$events_meta['_zoekertjeBeschrijving'] = $_POST['_zoekertjeBeschrijving'];
		
		// Add values of $events_meta as custom fields
		foreach ($events_meta as $key => $value) 
		{ 
		// Cycle through the $events_meta array!
			if( $post->post_type == 'revision' )
			{
				return;
			}

			// Don't store custom data twice
			$value = implode(',', (array)$value); 
			// If $value is an array, make it a CSV (unlikely)

			if(get_post_meta($post->ID, $key, FALSE)) 
			{ 
				// If the custom field already has a value
				update_post_meta($post->ID, $key, $value);
			} 
			else 
			{ 
				// If the custom field doesn't have a value
				add_post_meta($post->ID, $key, $value);
			}

			if(!$value)
			{
				delete_post_meta($post->ID, $key); // Delete if blank
			}

		}

	}
?>