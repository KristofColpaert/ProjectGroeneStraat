<?php
	/*
	Plugin Name: Groenestraat Ads
	Plugin URI: http://www.groenestraat.be
	Description: This plugin adds the advertisement and advertisement post custom post types to your WordPress installation.
	Version: 1.0
	Author: Rodric Degroote
	Author URI: http://www.groenestraat.be
	Text Domain: prowp-plugin
	License: GPLv2
	*/

	// Install the plugin when activated
	register_activation_hook(__FILE__, 'prowp_ad_install');

	// Add actions
	//Onderstaande code zal ervoor zorgen dat er een nieuw menu-item wordt toegevoegd. Init is altijd verplicht (zal zorgen dat uw custom post type geïnitialiseerd is)
	add_action('init', 'prowp_register_zoekertjes');
	add_action('do_meta_boxes', 'hide_project_metabox_ad');

	//opslaan
	add_action('save_post', 'save_ad_metaboxes', 1, 2); 

	function prowp_ad_install()
	{
		add_ad_capability();
	}


	// Register the custom projects post type
	function prowp_register_zoekertjes()
	{
		$labels = array(
			'name' => 'Zoekertjes',
			'singular_name' => 'Zoekertje',
			'add_new' => 'Nieuw Zoekertje Toevoegen',
			'add_new_item' => 'Nieuw Zoekertje Toevoegen',
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
			'capability_type' => 'ads',
			'capabilities' => array(
				'publish_posts' => 'publish_ads',
				'edit_posts' => 'edit_ads',
				'edit_others_posts' => 'edit_others_ads',
				'delete_posts' => 'delete_ads',
				'delete_others_posts' => 'delete_others_ads',
				'read_private_posts' => 'read_private_ads',
				'edit_post' => 'edit_ad',
				'delete_post' => 'delete_ad',
				'read_post' => 'read_ad',
				'edit_published_posts' => 'edit_published_ads',
				'delete_published_posts' => 'delete_published_ads'
			),
			'has-archive' => true,
			'menu_icon' => 'dashicons-search',
			'menu_position' => 9,
			'supports' => array('title'),
			'rewrite' => array('slug' => 'ad'),
			'register_meta_box_cb' => 'add_ad_metaboxes'
		);

		//het registreren van een nieuwe custom type
		register_post_type('ads', $args);
	}

	// Add the Zoekertjes Meta Boxes
	function add_ad_metaboxes() 
	{
		global $post; 

    	add_meta_box('wpt_ads_location', 'Zoekertjegegevens', 'ad_metaboxes_callback', $post->post_type, 'normal', 'high');
	}

	// The Zoekertjes Metabox
	function ad_metaboxes_callback() {
		global $post;
		
		// Noncename needed to verify where the data originated
		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . 
		wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
		$zoekertjeName = get_post_meta($post->ID, '_zoekertjeName', true);
		$zoekertjeLocatie = get_post_meta($post->ID, '_zoekertjeLocatie', true);
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
	function save_ad_metaboxes($post_id, $post) {
		
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) 
		{
			return $post->ID;
		}

		// Is the user allowed to edit the post or page?
		if ( !current_user_can( 'edit_post', $post->ID ))
		{
			return $post->ID;
		}

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

	// Hide the project metabox from the screen
	function hide_project_metabox_ad()
	{
		remove_meta_box('projectsParent', 'Ads', 'normal');
	}

	// Add capabilities to roles.
	function add_ad_capability() 
	{
	    $roleAuthor = get_role('author');
	    $roleAdministrator = get_role('administrator');

	    $roleAuthor->add_cap('delete_ads');
	    $roleAuthor->add_cap('delete_published_ads');
	    $roleAuthor->add_cap('edit_ads');
	    $roleAuthor->add_cap('edit_published_ads');
	    $roleAuthor->add_cap('publish_ads');
	    $roleAuthor->add_cap('read_ad');
	    $roleAuthor->add_cap('edit_ad');

	    $roleAdministrator->add_cap('delete_ad');
	    $roleAdministrator->add_cap('delete_ads');
	    $roleAdministrator->add_cap('delete_published_ads');
	    $roleAdministrator->add_cap('edit_ad');
	    $roleAdministrator->add_cap('edit_ads');
	    $roleAdministrator->add_cap('edit_published_ads');
	    $roleAdministrator->add_cap('publish_ads');
	    $roleAdministrator->add_cap('read_ad');
	}
?>