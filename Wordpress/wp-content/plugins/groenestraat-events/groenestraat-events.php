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

	/*
	** Installation and actions
	*/

	register_activation_hook(__FILE__, 'prowp_event_install');

	add_action('init', 'prowp_register_events');
	add_action('do_meta_boxes', 'hide_project_metabox_event');
	add_action('save_post', 'save_event_metaboxes', 1, 2); 

	// The method applies the new capabilities
	function prowp_event_install()
	{
		add_event_capability();
	}

	/*
	** Register the custom post type for events
	*/

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
				'read_private_posts' => 'read_private_events',
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

		register_post_type('events', $args);
	}

	/*
	** Provide metaboxes for the custom post type
	*/

	function add_event_metaboxes() 
	{
		global $post;

    	add_meta_box('wpt_events_location', 'Eventgegegevens', 'event_metaboxes_callback', $post->post_type, 'normal', 'high');
	}

	function event_metaboxes_callback() 
	{
		global $post;
		
		// Noncename needed to verify where the data originated
		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . 
		wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
		$eventTime = get_post_meta($post->ID, '_eventTime', true);
		$eventLocation = get_post_meta($post->ID, '_eventLocation', true);
		$eventMoreInfo= get_post_meta($post->ID, '_eventMoreInfo', true);
		
		echo '<label class="eventTime" for="eventTime">Datum</label><br />';
    	echo '<input id="eventTime" type="date" name="_eventTime" value="' . $eventTime . '" class="eventTime"><br />';    

    	echo '<label for="eventLocation">Locatie van het event</label>';
    	echo '<input id="eventLocation" type="text" name="_eventLocation" value="' . $eventLocation . '" class="widefat" />';

    	echo '<label for="eventMoreInfo">Meer info</label>';
    	echo '<input id="eventMoreInfo" type="text" name="_eventMoreInfo" value="' . $eventMoreInfo . '" class="widefat" />';
	}

	function save_event_metaboxes($post_id, $post) 
	{
		if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) 
		{
			return $post->ID;
		}

		if ( !current_user_can( 'edit_post', $post->ID ))
		{
			return $post->ID;
		}

		$events_meta['_eventLocation'] = $_POST['_eventLocation'];
		$events_meta['_eventMoreInfo'] = $_POST['_eventMoreInfo'];
		$events_meta["_eventTime"] = $_POST['_eventTime'];
		
		foreach ($events_meta as $key => $value) 
		{ 
			if( $post->post_type == 'revision' )
			{
				return;
			}

			$value = implode(',', (array)$value); 

			if(get_post_meta($post->ID, $key, FALSE)) 
			{ 
				update_post_meta($post->ID, $key, $value);
			} 
			else 
			{ 
				add_post_meta($post->ID, $key, $value);
			}

			if(!$value)
			{
				delete_post_meta($post->ID, $key);
			}
		}
	}

	function hide_project_metabox_event()
	{
		remove_meta_box('projectsParent', 'Events', 'normal');
	}

	/*
	** Add capabilities for all kinds of roles in WordPress
	*/

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