<?php
	/*
		Plugin Name: Groenestraat Zoekertjes
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin voegt het Zoekertjes custom post type toe. 
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	/*
		Install plugin
	*/

	register_activation_hook(__FILE__, 'prowp_zoekertjes_install');

	function prowp_zoekertjes_install()
	{
		wp_create_category('Projectzoekertjes');
	}
	
	/*
		Add actions
	*/
	
	add_action('init', 'prowp_register_zoekertjes');
	add_action('save_post', 'save_zoekertjes_metaboxes', 1, 2);
	add_action('save_post', 'parentproject_metaboxes_save_zoekertjes', 1, 2);
	add_action('add_meta_boxes', 'parentproject_metaboxes_add_zoekertjes');
	
	/*
		Register the custom post type for zoekertjes
	*/
	
	function prowp_register_zoekertjes()
	{
		$labels = array(
			'name' => __('Zoekertjes'), 
			'singular_name' => __('Zoekertje'),
			'add_new' => __('Nieuw zoekertje'),
			'add_new_item' => __('Nieuw zoekertje'),
			'edit_item' => __('Bewerk zoekertje'),
			'new_item' => __('Nieuw zoekertje'),
			'all_items' => __('Alle zoekertjes'),
			'view_item' => __('Bekijk zoekertje'),
			'search_item' => __('Zoek zoekertjes'),
			'not_found' => __('Geen zoekertjes gevonden.'),
			'not_found_in_trash' => __('Geen zoekertjes gevonden in prullenbak.'),
			'menu_name' => __('Zoekertjes')
		);
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'zoekertjes'),
			'supports' => array('title'),
			'taxonomies' => array('category'),
			'menu_icon' => 'dashicons-search',
			'menu_position' => 8,
			'capability_type' => 'post',
			'register_meta_box_cb' => 'add_zoekertjes_metaboxes'
		);
		
		register_post_type('zoekertjes', $args);
	}
	
	/*
		Add metaboxes to the custom post type
	*/
	
	function add_zoekertjes_metaboxes()
	{
		global $post; 
		
		add_meta_box('zoekertjesMetaboxes', 'Zoekertjegegevens', 'zoekertjes_metaboxes_callback', $post->post_type, 'normal', 'high');
	}
	
	function zoekertjes_metaboxes_callback()
	{
		global $post; 
		
		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
		$adName = get_post_meta($post->ID, '_adName', true);
		$adLocation = get_post_meta($post->ID, '_adLocation', true);
		$adDescription = get_post_meta($post->ID, '_adDescription', true);
		
		echo '<label class="adName" for="adName">Naam</label><br />';
    	echo '<input id="adName" type="text" name="_adName" value="' . $adName . '" class="widefat"><br />';    

    	echo '<label for="adLocation">Locatie van het zoekertje</label>';
    	echo '<input id="adLocation" type="text" name="_adLocation" value="' . $adLocation . '" class="widefat" />';

    	echo '<label for="adDescription">Beschrijving van het zoekertje</label>';
    	echo '<textarea id="adDescription" rows=10 name="_adDescription" class="widefat">' . $adDescription . '</textarea>';
	}
	
	function save_zoekertjes_metaboxes($post_id, $post)
	{
		if (!isset( $_POST['eventmeta_noncename'] ) || !wp_verify_nonce($_POST['eventmeta_noncename'], plugin_basename(__FILE__))) 
		{
			return $post->ID;
		}

		if (!current_user_can('edit_post', $post->ID))
		{
			return $post->ID;
		}

		$events_meta['_adName'] = $_POST['_adName'];
		$events_meta['_adLocation'] = $_POST['_adLocation'];
		$events_meta['_adDescription'] = $_POST['_adDescription'];
		
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

	function parentproject_metaboxes_add_zoekertjes()
	{
		add_meta_box('parentproject', 'Project', 'parentproject_metaboxes_callback_zoekertjes', 'zoekertjes', 'normal', 'high');
	}

	function parentproject_metaboxes_callback_zoekertjes( $object, $box ) 
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

	function parentproject_metaboxes_save_zoekertjes($post_id, $post)
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
			$category = get_category_by_slug('projectzoekertjes');		
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