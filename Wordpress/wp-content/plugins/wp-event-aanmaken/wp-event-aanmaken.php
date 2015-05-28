<?php
	/*
	Plugin Name: Aanmaken Event (Groenestraat.be)
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
	add_action('init', 'prowp_register_events');
	add_action( 'add_meta_boxes', 'add_events_metaboxes' );

	//opslaan
	add_action('save_post', 'wpt_save_events_meta', 1, 2); 

	// Register the custom projects post type
	function prowp_register_events()
	{
		$labels = array('name' => 'Events',
				'singular_name' => 'Event',
				'add_new' => 'Toevoegen Event',
				'add_new_item' => 'Toevoegen Event',
				'edit_item' => 'Event bewerken',
				'new_item' => 'Nieuw Event',
				'all_items' => 'Alle Eventen',
				'view_item' => 'Event weergeven',
				'search_items' => 'Zoek Eventen',
				'not_found' => 'Er werden geen Eventen gevonden',
				'not_found_in_trash' => 'Er werden geen Eventen gevonden in de prullenbak',
				'menu_name' => 'Eventen'
			);

		//een array van argumenten
		$args = array(
			'labels' => $labels,
			'public' => true,
			'menu_icon' => 'dashicons-calendar',
			'supports' => false,
			);

		//het registreren van een nieuwe custom type
		register_post_type('Eventen', $args);
	}

	// Add the Events Meta Boxes
	function add_events_metaboxes() {
    	add_meta_box('wpt_events_location', 'Informatie', 'wpt_events_location', 'Eventen', 'normal', 'high');
	}

	// The Event Location Metabox

	function wpt_events_location() {
		global $post;
		
		// Noncename needed to verify where the data originated
		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . 
		wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
		$eventName = get_post_meta($post->ID, '_eventName', true);
		$eventTime = get_post_meta($post->ID, '_eventTime', true);
		//echo($eventTime);
		$eventLocation = get_post_meta($post->ID, '_eventLocation', true);
		$eventMoreInfo= get_post_meta($post->ID, '_eventMoreInfo', true);
		
		// Echo out the field
		echo '<label class="eventLabel" for="eventName">Naam van het event</label>';
    	echo '<input id="eventName" type="text" name="_eventName" value="' . $eventName  . '" class="widefat" />';

		echo '<label class="eventTime" for="eventTime">Datum</label><br />';
    	echo '<input id="eventTime" type="date" name="_eventTime" value="' . $eventTime . '" class="eventTime"><br />';    

    	echo '<label for="eventLocation">Locatie van het event</label>';
    	echo '<input id="eventLocation" type="text" name="_eventLocation" value="' . $eventLocation . '" class="widefat" />';

    	echo '<label for="eventMoreInfo">Meer info</label>';
    	echo '<input id="eventMoreInfo" type="text" name="_eventMoreInfo" value="' . $eventMoreInfo . '" class="widefat" />';
	}

	// Save the Metabox Data
	function wpt_save_events_meta($post_id, $post) {
		
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
		
		$events_meta['_eventName'] = $_POST['_eventName'];
		$events_meta['_eventLocation'] = $_POST['_eventLocation'];
		$events_meta['_eventMoreInfo'] = $_POST['_eventMoreInfo'];
		$events_meta["_eventTime"] = $_POST['_eventTime'];
		
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