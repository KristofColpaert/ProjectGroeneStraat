<?php
	/*
	Plugin Name: Groenestraat Events
	Plugin URI: http://www.groenestraat.be
	Description: This plugin adds the event and event post custom post types to your WordPress installation.
	Version: 1.0
	Author: Rodric Degroote
	Author URI: http://www.groenestraat.be
	Text Domain: prowp-plugin
	License: GPLv2
	*/

	// Install the plugin when activated
	register_activation_hook(__FILE__, 'prowp_event_install');

	// Add actions
	//Onderstaande code zal ervoor zorgen dat er een nieuw menu-item wordt toegevoegd. Init is altijd verplicht (zal zorgen dat uw custom post type geïnitialiseerd is)
	add_action('init', 'prowp_register_events');
	add_action('do_meta_boxes', 'hide_project_metabox');

	//Opslaan
	add_action('save_post', 'save_event_metaboxes', 1, 2); 

	// Install the custom post type for projects and add a category
	function prowp_event_install()
	{
		wp_create_category('Projectartikels');
		add_event_capability();
	}

	// Register the custom projects post type
	function prowp_register_events()
	{
		$labels = array(
			'name' => 'Events',
			'singular_name' => 'Event',
			'add_new' => 'Nieuw Event toevoegen',
			'add_new_item' => 'Nieuw Event toevoegen',
			'edit_item' => 'Event bewerken',
			'new_item' => 'Nieuw Event',
			'all_items' => 'Alle Events',
			'view_item' => 'Event weergeven',
			'search_items' => 'Zoek Events',
			'not_found' => 'Er werden geen Events gevonden',
			'not_found_in_trash' => 'Er werden geen Events gevonden in de prullenbak',
			'menu_name' => 'Events'
		);

		//een array van argumenten
		$args = array(
			'labels' => $labels,
			'public' => true,
			'capability_type' => 'events',
			'capabilities' => array(
				'publish_posts' => 'publish_events',
				'edit_posts' => 'edit_events',
				'edit_others_posts' => 'edit_others_events',
				'delete_posts' => 'delete_events',
				'delete_others_posts' => 'delete_others_events',
				'read_private_posts' => 'read_private_evetns',
				'edit_post' => 'edit_event',
				'delete_post' => 'delete_event',
				'read_post' => 'read_event',
				'edit_published_posts' => 'edit_published_events',
				'delete_published_posts' => 'delete_published_events'
			),
			'has_archive' => true,
			'menu_icon' => 'dashicons-calendar',
			'menu_position' => 8,
			'supports' => array('title'),
			'rewrite' => array('slug' => 'event'),
			'register_meta_box_cb' => 'add_event_metaboxes'
		);

		//het registreren van een nieuwe custom type
		register_post_type('events', $args);
	}

	// Add the Events Meta Boxes
	function add_event_metaboxes() {
		global $post;

    	add_meta_box('wpt_events_location', 'Eventgegegevens', 'event_metaboxes_callback', $post->post_type, 'normal', 'high');
	}

	// The Event Location Metabox

	function event_metaboxes_callback() {
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
		echo '<label class="eventTime" for="eventTime">Datum</label><br />';
    	echo '<input id="eventTime" type="date" name="_eventTime" value="' . $eventTime . '" class="eventTime"><br />';    

    	echo '<label for="eventLocation">Locatie van het event</label>';
    	echo '<input id="eventLocation" type="text" name="_eventLocation" value="' . $eventLocation . '" class="widefat" />';

    	echo '<label for="eventMoreInfo">Meer info</label>';
    	echo '<input id="eventMoreInfo" type="text" name="_eventMoreInfo" value="' . $eventMoreInfo . '" class="widefat" />';
	}

	// Save the Metabox Data
	function save_event_metaboxes($post_id, $post) {
		
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

	// Hide the project metabox from the screen
	function hide_project_metabox()
	{
		remove_meta_box('projectsParent', 'Events', 'normal');
	}

	// Add capabilities to roles.
	function add_event_capability() 
	{
	    $roleAuthor = get_role('author');
	    $roleAdministrator = get_role('administrator');

	    $roleAuthor->add_cap('delete_events');
	    $roleAuthor->add_cap('delete_published_events');
	    $roleAuthor->add_cap('edit_events');
	    $roleAuthor->add_cap('edit_published_events');
	    $roleAuthor->add_cap('publish_events');
	    $roleAuthor->add_cap('read_event');
	    $roleAuthor->add_cap('edit_event');

	    $roleAdministrator->add_cap('delete_event');
	    $roleAdministrator->add_cap('delete_events');
	    $roleAdministrator->add_cap('delete_published_events');
	    $roleAdministrator->add_cap('edit_event');
	    $roleAdministrator->add_cap('edit_events');
	    $roleAdministrator->add_cap('edit_published_events');
	    $roleAdministrator->add_cap('publish_events');
	    $roleAdministrator->add_cap('read_event');
	}
?>