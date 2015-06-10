<?php
	/*
		Plugin Name: Groenestraat Events
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin voegt het Events custom post type toe. 
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	/*
		Install plugin
	*/

	register_activation_hook(__FILE__, 'prowp_events_install');

	function prowp_events_install()
	{
		wp_create_category('Projectevents');
		initialize_events_capabilities();
	}

	function initialize_events_capabilities()
	{
		$roleAdministrator = get_role('administrator');
		$roleAuthor = get_role('author');
		$roleContributor = get_role('contributor');

		$roleAdministrator->add_cap('publish_events');
		$roleAdministrator->add_cap('edit_events');
		$roleAdministrator->add_cap('edit_others_events');
		$roleAdministrator->add_cap('delete_events');
		$roleAdministrator->add_cap('delete_others_events');
		$roleAdministrator->add_cap('read_private_events');
		$roleAdministrator->add_cap('edit_event');
		$roleAdministrator->add_cap('delete_event');
		$roleAdministrator->add_cap('read_event');
		$roleAdministrator->add_cap('edit_published_events');
		$roleAdministrator->add_cap('delete_published_events');

		$roleAuthor->add_cap('publish_events');
		$roleAuthor->add_cap('edit_event');
		$roleAuthor->add_cap('edit_events');
		$roleAuthor->add_cap('edit_published_events');
		$roleAuthor->add_cap('delete_events');
		$roleAuthor->add_cap('delete_published_events');
		$roleAuthor->add_cap('read_event');

		$roleContributor->add_cap('publish_events');
		$roleContributor->add_cap('edit_event');
		$roleContributor->add_cap('edit_events');
		$roleContributor->add_cap('edit_published_events');
		$roleContributor->add_cap('delete_events');
		$roleContributor->add_cap('delete_published_events');
		$roleContributor->add_cap('read_event');
		$roleContributor->add_cap('upload_files');
	}
	
	/*
		Add actions
	*/
	
	add_action('init', 'prowp_register_events');
	add_action('save_post', 'save_events_metaboxes', 1, 2);
	add_action('save_post', 'parentproject_metaboxes_save_events', 1, 2);
	add_action('add_meta_boxes', 'parentproject_metaboxes_add_events');

	/*
		Register the custom post type for events
	*/

	
	function prowp_register_events()
	{
		$labels = array(
			'name' => __('Events'), 
			'singular_name' => __('Event'),
			'add_new' => __('Nieuw event'),
			'add_new_item' => __('Nieuw event'),
			'edit_item' => __('Bewerk event'),
			'new_item' => __('Nieuw event'),
			'all_items' => __('Alle events'),
			'view_item' => __('Bekijk event'),
			'search_item' => __('Zoek events'),
			'not_found' => __('Geen events gevonden.'),
			'not_found_in_trash' => __('Geen events gevonden in prullenbak.'),
			'menu_name' => __('Events')
		);
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'events'),
			'supports' => array('title', 'editor', 'thumbnail'),
			'taxonomies' => array('category'),
			'menu_icon' => 'dashicons-calendar-alt',
			'menu_position' => 7,
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
			'register_meta_box_cb' => 'add_events_metaboxes'
		);
		
		register_post_type('events', $args);
	}
	
	/*
		Add metaboxes to the custom post type
	*/
	
	function add_events_metaboxes()
	{
		global $post;
		
		add_meta_box('eventsMetaboxes', 'Eventgegevens', 'events_metaboxes_callback', $post->post_type, 'normal', 'high');
	}
	
	function events_metaboxes_callback()
	{

	?>
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

		<script>
		  	$(document).ready(function() {
				$('#eventTime').datepicker();
				$('#eventEndTime').datepicker();
			});
		</script>

		<?php

		global $post;
		
		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
		$eventTime = get_post_meta($post->ID, '_eventTime', true);
		$eventLocation = get_post_meta($post->ID, '_eventLocation', true);
		$eventEndTime = get_post_meta($post->ID, '_eventEndTime', true);
		$eventStartHour = get_post_meta($post->ID, '_eventStartHour', true);
		$eventEndHour = get_post_meta($post->ID, '_eventEndHour', true);

		echo '<label class="eventTime" for="eventTime">Datum</label><br />';
    	echo '<input id="eventTime" readonly type="text" name="_eventTime" value="' . $eventTime . '" class="widefat"><br />';    

		echo '<label class="eventEndTime" for="eventEndTime">Einddatum</label><br />';
    	echo '<input id="eventEndTime" readonly type="text" name="_eventEndTime" value="' . $eventEndTime . '" class="widefat"><br />';  

    	echo '<label class="eventStartHour" for="eventStartHour">Aanvangstijd (HH:MM)</label><br />';
    	echo '<input id="eventStartHour" type="text" name="_eventStartHour" value="' . $eventStartHour . '" class="widefat"><br />';  

    	echo '<label class="eventEndHour" for="eventEndHour">Eindtijd (HH:MM)</label><br />';
    	echo '<input id="eventEndHour" type="text" name="_eventEndHour" value="' . $eventEndHour . '" class="widefat"><br />';  

    	echo '<label for="eventLocation">Locatie van het event</label>';
    	echo '<input id="eventLocation" type="text" name="_eventLocation" value="' . $eventLocation . '" class="widefat" />';

    	wp_enqueue_script('validation', get_stylesheet_directory_uri() . '/js/livevalidation_standalone.compressed.js', array( 'jquery' ));
    	wp_enqueue_script('my_validation', plugins_url() . '/groenestraat-events/my_validation.js', array( 'jquery' ));
	}
	
	function save_events_metaboxes($post_id, $post) 
	{

		if (!isset( $_POST['eventmeta_noncename'] ) || !wp_verify_nonce($_POST['eventmeta_noncename'], plugin_basename(__FILE__))) 
		{
			return $post->ID;
		}

		if (!current_user_can('edit_post', $post->ID))
		{
			return $post->ID;
		}

		if(!isset($_POST['_eventLocation']) || empty($_POST['_eventLocation']) ||
			!isset($_POST['_eventTime']) || empty($_POST['_eventTime']) ||
			!isset($_POST['_eventEndTime']) || empty($_POST['_eventEndTime']) ||
			!isset($_POST['_eventStartHour']) || empty($_POST['_eventStartHour']) ||
			!isset($_POST['_eventEndHour']) || empty($_POST['_eventEndHour'])
			)
		{
        	$error = new WP_Error('Er is een fout opgetreden. Gelieve alle gegevens (locatie, begindatum, einddatum) correct in te voeren. <a href="'. $_SERVER['HTTP_REFERER'] .'">Ga terug.</a>');
		    wp_die($error->get_error_code(), 'Error: Missing Arguments');
		}

		$events_meta['_eventLocation'] = $_POST['_eventLocation'];
		$events_meta['_eventTime'] = $_POST['_eventTime'];
		$events_meta['_eventEndTime'] = $_POST['_eventEndTime'];
		$events_meta['_eventStartHour'] = $_POST['_eventStartHour'];
		$events_meta['_eventEndHour'] = $_POST['_eventEndHour'];

		foreach ($events_meta as $key => $value) 
		{ 
			if($post->post_type == 'revision')
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

	/*
		Show parent project metabox.
	*/

	function parentproject_metaboxes_add_events()
	{
		add_meta_box('parentproject', 'Project', 'parentproject_metaboxes_callback_events', 'events', 'normal', 'high');
	}

	function parentproject_metaboxes_callback_events( $object, $box ) 
	{ 
		global $post; 

		$current_user = wp_get_current_user();
		$parents = get_posts(
			array(
				'post_type' => 'projecten',
				'orderby' => 'title',
				'order' => 'ASC',
				'numberposts' => -1,
				'meta_key' => '_subscriberId',
				'meta_value' => $current_user->ID,
				'meta_operator' => '='
			)
		);

		$postParentId = get_post_meta($post->ID, '_parentProjectId', true);
		echo '<select name="_parentProjectId" class="widefat">';
		echo '<option value="0">Geen Project</option>';
		if(!empty($parents))
		{
			foreach($parents as $parent)
			{
				printf('<option value="%s"%s>%s</option>', esc_attr($parent->ID), selected($parent->ID, $postParentId, false), esc_html($parent->post_title));
			}
		}
		echo '</select>';

   		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
	}

	function parentproject_metaboxes_save_events($post_id, $post)
	{
		if(!isset( $_POST['eventmeta_noncename'] ) || !wp_verify_nonce($_POST['eventmeta_noncename'], plugin_basename(__FILE__)))
		{
			return $post->ID;
		}

		if(!current_user_can('edit_post', $post->ID))
		{
			return $post->ID;
		}

		$events_meta['_parentProjectId'] = $_POST['_parentProjectId'];

		if($events_meta['_parentProjectId'] != 0)
		{
			$category = get_category_by_slug('projectevents');			
  			$categoryId = $category->term_id;

  			wp_set_post_categories($post->ID, array($categoryId), false);
		}

		else 
		{
			wp_set_post_categories($post->ID, null, false);
		}

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
?>