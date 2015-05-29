<?php
	/**
	 * The template for displaying all single posts and attachments
	 *
	 * @package Groenestraat
	 * @subpackage Groenestraat Template
	 * @since Groenestraat Template 1.0
	 */

	get_header();
	
	//Custom query to a post
	global $query_string;

	$projects = new WP_Query($query_string);
	while(have_posts() : the_post());
		echo the_title();
	endwhile;

	get_footer(); 
?>