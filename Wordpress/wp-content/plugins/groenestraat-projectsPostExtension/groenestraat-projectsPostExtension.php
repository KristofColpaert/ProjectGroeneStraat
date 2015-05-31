<?php
	/*
	Plugin Name: Groenestraat Projecten Uitbreiding
	Plugin URI: http://www.groenestraat.be
	Description: Deze plugin zorgt ervoor dat berichten, events en zoekertjes kunnen toegekend worden aan een project (of aan de algemene site).
	Version: 1.0
	Author: Rodric Degroote, Kristof Colpaert
	Author URI: http://www.groenestraat.be
	Text Domain: prowp-plugin
	License: GPLv2
	*/

	/*
		Add actions
	*/
	add_action('load-post.php', 'parentproject_metaboxes_setup');
	add_action('load-post-new.php', 'parentproject_metaboxes_setup');
	add_action('save_post', 'parentproject_metaboxes_save', 1, 2);

	/*
		Set up parent project metaboxes
	*/

	function parentproject_metaboxes_setup() 
	{
	  add_action('add_meta_boxes', 'parentproject_metaboxes_add');
	}

	function parentproject_metaboxes_add() 
	{
		global $post; 

	  	add_meta_box('parentproject', 'Project', 'parentproject_metaboxes_callback', $post->post_type, 'normal', 'high');
	}

	function parentproject_metaboxes_callback( $object, $box ) 
	{ 
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
				printf('<option value="%s"%s>%s</option', esc_attr($parent->ID), selected($parent->ID, $postParentId, false), esc_html($parent->post_title));
			}
			echo '</select>';
		}

   		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	}

	function parentproject_metaboxes_save($post_id, $post)
	{
		if(!wp_verify_nonce($_POST['eventmeta_noncename'], plugin_basename(__FILE__)))
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