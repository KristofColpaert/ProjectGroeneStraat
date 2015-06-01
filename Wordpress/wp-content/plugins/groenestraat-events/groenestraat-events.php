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
			'supports' => array('title', 'editor'),
			'taxonomies' => array('category'),
			'menu_icon' => 'dashicons-calendar-alt',
			'menu_position' => 7,
			'capability_type' => 'post',
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
		global $post;
		
		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
		$eventTime = get_post_meta($post->ID, '_eventTime', true);
		$eventLocation = get_post_meta($post->ID, '_eventLocation', true);
		
		echo '<label class="eventTime" for="eventTime">Datum</label><br />';
    	echo '<input id="eventTime" type="date" name="_eventTime" value="' . $eventTime . '" class="widefat"><br />';    

    	echo '<label for="eventLocation">Locatie van het event</label>';
    	echo '<input id="eventLocation" type="text" name="_eventLocation" value="' . $eventLocation . '" class="widefat" />';
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

		$events_meta['_eventLocation'] = $_POST['_eventLocation'];
		$events_meta['_eventTime'] = $_POST['_eventTime'];
		
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
		echo 'hier';
		$parents = get_posts(
			array(
				'post_type' => 'projecten',
				'orderby' => 'title',
				'order' => 'ASC',
				'numberposts' => -1
			)
		);

		if(!empty($parents))
		{
			global $post; 

			$postParentId = get_post_meta($post->ID, '_parentProjectId', true);
			echo '<select name="_parentProjectId" class="widefat">';
			echo '<option value="0">Geen Project</option>';

			foreach($parents as $parent)
			{
				printf('<option value="%s"%s>%s</option>', esc_attr($parent->ID), selected($parent->ID, $postParentId, false), esc_html($parent->post_title));
			}
			echo '</select>';
		}

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