<?php
	/*
	Plugin Name: Groenestraat Projects
	Plugin URI: http://www.groenestraat.be
	Description: This plugin adds the project and project post custom post types to your WordPress installation. Capabilities for all kinds of roles are also set.
	Version: 1.0
	Author: Kristof Colpaert
	Author URI: http://www.groenestraat.be
	Text Domain: prowp-plugin
	License: GPLv2
	*/

	// Install the plugin when activated
	register_activation_hook(__FILE__, 'prowp_project_install');

	// Add actions
	add_action('init', 'prowp_register_projects');
	add_action('save_post', 'save_location_metaboxes', 1, 2);
	add_action('do_meta_boxes', 'show_custom_featured_imagebox');

	/*
	** Install the custom post type
	*/

	// Install the custom post type for projects and add a category
	function prowp_project_install()
	{
		wp_create_category('Projectartikels');
		add_project_capability();
	}

	/*
	** Register the custom post type for projects
	*/

	// Register the custom post type
	function prowp_register_projects()
	{
		$args = array(
			'public' => true,
			'capability_type' => 'projects',
			'capabilities' => array(
				'publish_posts' => 'publish_projecten',
				'edit_posts' => 'edit_projecten',
				'edit_others_posts' => 'edit_others_projecten',
				'delete_posts' => 'delete_projecten',
				'delete_others_posts' => 'delete_others_projecten',
				'read_private_posts' => 'read_private_projecten',
				'edit_post' => 'edit_project',
				'delete_post' => 'delete_project',
				'read_post' => 'read_project',
				'edit_published_posts' => 'edit_published_projecten',
				'delete_published_posts' => 'delete_published_projecten'
			),
			'has_archive' => true,
			'labels' => array(
				'name' => 'Projecten',
				'singular_name' => 'Project',
				'add_new' => 'Nieuw Project toevoegen',
				'add_new_item' => 'Nieuw Project toevoegen',
				'edit_item' => 'Project bewerken',
				'new_item' => 'Nieuw Project',
				'all_items' => 'Alle Projecten',
				'view_item' => 'Project weergeven',
				'search_items' => 'Zoek Projecten',
				'not_found' => 'Er werden geen Projecten gevonden',
				'not_found_in_trash' => 'Er werden geen Projecten gevonden in de prullenbak',
				'menu_name' => 'Projecten'
			),
			'rewrite' => array('slug' => 'project'),
			'supports' => array('title', 'editor', 'thumbnail'),
			'menu_icon' => 'dashicons-format-aside',
			'menu_position' => 5,
			'register_meta_box_cb' => 'add_location_metaboxes'
		);

		register_post_type('projects', $args);
	}

	/*
	** Provide metaboxes for the custom post type
	*/

	// Add the location metabox
	function add_location_metaboxes()
	{
		global $post;

		add_meta_box('projectsLocation', 'Locatiegegevens', 'location_metaboxes_callback', $post->post_type, 'normal', 'high');
	}

	// Generate the HTML for the metabox
	function location_metaboxes_callback()
	{
		global $post;

		// Noncename needed to verify where the data originated
   		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

    	// Get the original data if it has already been entered
    	$locationStreet = get_post_meta($post->ID, '_locationStreet', true);
    	$locationCity = get_post_meta($post->ID, '_locationCity', true);
    	$locationZipcode = get_post_meta($post->ID, '_locationZipcode', true);
    	echo '<label class="projectLabel" for="locationStreet">Straatnaam van het project</label>';
    	echo '<input id="locationStreet" type="text" name="_locationStreet" value="' . $locationStreet  . '" class="widefat" />';
    	echo '<label for="locationCity">Gemeente van het project</label>';
    	echo '<input id="locationCity" type="text" name="_locationCity" value="' . $locationCity . '" class="widefat" />';
    	echo '<label for="locationZipcode">Postcode van het project</label>';
    	echo '<input id="locationZipcode" type="text" name="_locationZipcode" value="' . $locationZipcode . '" class="widefat" />';
	}

	// Save the metabox data
	function save_location_metaboxes($post_id, $post)
	{
		if(!wp_verify_nonce($_POST['eventmeta_noncename'], plugin_basename(__FILE__)))
		{
			return $post->ID;
		}

		if(!current_user_can('edit_post', $post->ID))
		{
			return $post->ID;
		}

		$events_meta['_locationStreet'] = $_POST['_locationStreet'];
		$events_meta['_locationCity'] = $_POST['_locationCity'];
		$events_meta['_locationZipcode'] = $_POST['_locationZipcode'];

		foreach($events_meta as $key => $value)
		{
			if($post->post_type == 'revision')
			{
				return;
			}

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

	// Show the custom featured imagebox
	function show_custom_featured_imagebox()
	{
	    remove_meta_box('postimagediv', 'Projects', 'side');
	    remove_meta_box('projectsParent', 'Projects', 'normal');
	    add_meta_box('postimagediv', __('Hoofdingsafbeelding'), 'post_thumbnail_meta_box', 'Projects', 'normal', 'high');
	}

	/*
	** Add capabilities for all kinds of roles in WordPress
	*/

	// Add capabilities to roles.
	function add_project_capability() 
	{
	    $roleAuthor = get_role('author');
	    $roleAdministrator = get_role('administrator');

	    $roleAuthor->add_cap('delete_projecten');
	    $roleAuthor->add_cap('delete_published_projecten');
	    $roleAuthor->add_cap('edit_projecten');
	    $roleAuthor->add_cap('edit_published_projecten');
	    $roleAuthor->add_cap('publish_projecten');
	    $roleAuthor->add_cap('read_project');
	    $roleAuthor->add_cap('edit_project');

	    $roleAdministrator->add_cap('delete_project');
	    $roleAdministrator->add_cap('delete_projecten');
	    $roleAdministrator->add_cap('delete_published_projecten');
	    $roleAdministrator->add_cap('edit_project');
	    $roleAdministrator->add_cap('edit_projecten');
	    $roleAdministrator->add_cap('edit_published_projecten');
	    $roleAdministrator->add_cap('publish_projecten');
	    $roleAdministrator->add_cap('read_project');
	}
?>