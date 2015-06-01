<?php
	/*
		Plugin Name: Groenestraat Projecten
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin voegt het Projecten custom post type toe. 
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	/*
		Install plugin
	*/

	register_activation_hook(__FILE__, 'prowp_projecten_install');

	function prowp_projecten_install()
	{
		wp_create_category('Projectartikels');
	}
	
	/*
		Add actions
	*/
	
	add_action('init', 'prowp_register_projecten');
	add_action('save_post', 'save_projecten_metaboxes', 1, 2);

	/*
		Register the custom post type for projecten
	*/
	
	function prowp_register_projecten()
	{
		$labels = array(
			'name' => __('Projecten'), 
			'singular_name' => __('Project'),
			'add_new' => __('Nieuw project'),
			'add_new_item' => __('Nieuw project'),
			'edit_item' => __('Bewerk project'),
			'new_item' => __('Nieuw project'),
			'all_items' => __('Alle projecten'),
			'view_item' => __('Bekijk project'),
			'search_item' => __('Zoek projecten'),
			'not_found' => __('Geen projecten gevonden.'),
			'not_found_in_trash' => __('Geen projecten gevonden in prullenbak.'),
			'menu_name' => __('Projecten')
		);
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'projecten'),
			'supports' => array('title', 'editor', 'thumbnail'),
			'menu_icon' => 'dashicons-carrot',
			'menu_position' => 6,
			'capability_type' => 'post',
			'register_meta_box_cb' => 'add_projecten_metaboxes'
		);
		
		register_post_type('projecten', $args);
	}

	/*
		Add metaboxes to the custom post type
	*/

	function add_projecten_metaboxes()
	{
		global $post;

		add_meta_box('projectenMetaboxes', 'Projectgegevens', 'projecten_metaboxes_callback', $post->post_type, 'normal', 'high');
	}

	function projecten_metaboxes_callback()
	{
		global $post;
		
		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
		$projectStreet = get_post_meta($post->ID, '_projectStreet', true);
		$projectCity = get_post_meta($post->ID, '_projectCity', true);
		$projectZipcode = get_post_meta($post->ID, '_projectZipcode', true);
		
		echo '<label class="projectStreet" for="projectStreet">Straat van het project</label><br />';
    	echo '<input id="projectStreet" type="text" name="_projectStreet" value="' . $projectStreet . '" class="widefat"><br />';    

    	echo '<label for="projectCity">Gemeente van het project</label>';
    	echo '<input id="projectCity" type="text" name="_projectCity" value="' . $projectCity . '" class="widefat" />';

    	echo '<label for="projectZipcode">Postcode van het project</label>';
    	echo '<input id="projectZipcode" type="text" name="_projectZipcode" class="widefat" value="'. $projectZipcode . '" />';
	}

	function save_projecten_metaboxes($post_id, $post)
	{
		if (!isset( $_POST['eventmeta_noncename'] ) || !wp_verify_nonce($_POST['eventmeta_noncename'], plugin_basename(__FILE__))) 
		{
			return $post->ID;
		}

		if (!current_user_can('edit_post', $post->ID))
		{
			return $post->ID;
		}

		$events_meta['_projectStreet'] = $_POST['_projectStreet'];
		$events_meta['_projectCity'] = $_POST['_projectCity'];
		$events_meta['_projectZipcode'] = $_POST['_projectZipcode'];
		
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