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
		Add actions
	*/
	
	add_action('init', 'prowp_register_zoekertjes');
	add_action('save_post', 'save_zoekertjes_metaboxes', 1, 2);
	
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
		if (!wp_verify_nonce($_POST['eventmeta_noncename'], plugin_basename(__FILE__))) 
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
?>