<?php
	/*
	Plugin Name: Groenestraat Projects Post Extension
	Plugin URI: http://www.groenestraat.be
	Description: This plugin adds a custom metabox to the built-in Post type. In this metabox, users can choose whether or not they want to assign the post to a specific project they are a member of.
	Version: 1.0
	Author: Kristof Colpaert
	Author URI: http://www.groenestraat.be
	Text Domain: prowp-plugin
	License: GPLv2
	*/

	// Add actions
	add_action('load-post.php', 'post_metaboxes_setup');
	add_action('load-post-new.php', 'post_metaboxes_setup');
	add_action('save_post', 'post_metaboxes_save', 1, 2);

	// Method to setup the metaboxes that we want to show on the screen
	function post_metaboxes_setup() 
	{
	  add_action('add_meta_boxes', 'post_metaboxes_add');
	}

	// Create metaboxes to show on the screen
	function post_metaboxes_add() 
	{
		global $post; 

	  	add_meta_box('projectsParent', 'Project', 'post_metaboxes_callback', $post->post_type, 'normal', 'high');
	}

	// Display the post metabox
	function post_metaboxes_callback( $object, $box ) 
	{ 
		// Get projects
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

			$postParentId = get_post_meta($post->ID, '_projectParentId', true);
			echo '<select name="_projectParentId" class="widefat">';
			echo '<option value="0">Geen Project</option>';

			foreach($parents as $parent)
			{
				printf('<option value="%s"%s>%s</option', esc_attr($parent->ID), selected($parent->ID, $postParentId, false), esc_html($parent->post_title));
			}
			echo '</select>';
		}

	  	// Noncename needed to verify where the data originated
   		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
    	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

	}

	function post_metaboxes_save($post_id, $post)
	{
		if(!wp_verify_nonce($_POST['eventmeta_noncename'], plugin_basename(__FILE__)))
		{
			return $post->ID;
		}

		if(!current_user_can('edit_post', $post->ID))
		{
			return $post->ID;
		}

		$events_meta['_projectParentId'] = $_POST['_projectParentId'];

		if($events_meta['_projectParentId'] != 0)
		{
			$category = get_category_by_slug('projectartikels'); 
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