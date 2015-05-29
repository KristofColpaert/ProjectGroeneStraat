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

	/*
	** Installation and actions
	*/

	register_activation_hook(__FILE__, 'prowp_ad_install');

	add_action('init', 'prowp_register_ads');
	add_action('do_meta_boxes', 'hide_project_metabox_ad');
	add_action('save_post', 'save_ad_metaboxes', 1, 2); 

	// The method applies the new capabilities
	function prowp_ad_install()
	{
		add_ad_capability();
		flush_rewrite_rules();
	}

	/*
	** Register the custom post type for events
	*/

	function prowp_register_ads()
	{
		$labels = array(
			'name' => 'Ads',
			'singular_name' => 'Ad',
			'add_new' => 'Nieuw Ad toevoegen',
			'add_new_item' => 'Nieuw Ad toevoegen',
			'edit_item' => 'Ad bewerken',
			'new_item' => 'Nieuw Ad',
			'all_items' => 'Alle Ads',
			'view_item' => 'Ad weergeven',
			'search_items' => 'Zoek Ads',
			'not_found' => 'Er werden geen Ads gevonden',
			'not_found_in_trash' => 'Er werden geen Ads gevonden in de prullenbak',
			'menu_name' => 'Ads'
		);

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
			'has_archive' => true,
			'menu_icon' => 'dashicons-search',
			'menu_position' => 9,
			'supports' => array('title'),
			'rewrite' => array('slug' => 'ad'),
			'register_meta_box_cb' => 'add_ad_metaboxes'
		);

		register_post_type('ads', $args);
	}

	/*
	** Provide metaboxes for the custom post type
	*/

	function add_ad_metaboxes() 
	{
		global $post;

    	add_meta_box('wpt_events_location', 'Zoekertjegegegevens', 'ad_metaboxes_callback', $post->post_type, 'normal', 'high');
	}

	function ad_metaboxes_callback() 
	{
		global $post;
		
		// Noncename needed to verify where the data originated
		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . 
		wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
		$adName = get_post_meta($post->ID, '_adName', true);
		$adLocation = get_post_meta($post->ID, '_adLocation', true);
		$adDescription = get_post_meta($post->ID, '_adDescription', true);
		
		echo '<label class="adName" for="adName">Naam</label><br />';
    	echo '<input id="adName" type="text" name="_adName" value="' . $adName . '" class="widefat"><br />';    

    	echo '<label for="adLocation">Locatie van het zoekertje</label>';
    	echo '<input id="adLocation" type="text" name="_adLocation" value="' . $adLocation . '" class="widefat" />';

    	echo '<label for="adDescription">Beschrijving van het zoekertje</label>';
    	echo '<input id="adDescription" type="text" name="_adDescription" value="' . $adDescription . '" class="widefat" />';
	}

	function save_ad_metaboxes($post_id, $post) 
	{
		if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) 
		{
			return $post->ID;
		}

		if ( !current_user_can( 'edit_post', $post->ID ))
		{
			return $post->ID;
		}

		$events_meta['_adName'] = $_POST['_adName'];
		$events_meta['_adLocation'] = $_POST['_adLocation'];
		$events_meta['_adDescription'] = $_POST['_adDescription'];
		
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

	function hide_project_metabox_ad()
	{
		remove_meta_box('projectsParent', 'Ads', 'normal');
	}

	/*
	** Add capabilities for all kinds of roles in WordPress
	*/

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
	    $roleAdministrator->add_cap('read_ads');
	}
?>