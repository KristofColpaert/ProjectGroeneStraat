<?php
	/*
	Plugin Name: Groenestraat Template Extension
	Plugin URI: http://www.groenestraat.be
	Description: This plugin selects the right template for all post types.
	Version: 1.0
	Author: Kristof Colpaert
	Author URI: http://www.groenestraat.be
	Text Domain: prowp-plugin
	License: GPLv2
	*/
	
	// Add filters
	add_filter( 'template_include', 'include_template_function', 1 );

	// This method selects the right template file for all post types
	function include_template_function( $template_path ) 
	{
    	if(get_post_type() == 'events') 
    	{
            if(is_single())
	   		{
            	if($theme_file = locate_template(array('single-events.php'))) 
            	{
            		$template_path = $theme_file;
            	} 

            	else 
            	{
               	 	$template_path = plugin_dir_path( __FILE__ ) . '/single-events.php';
            	}
           }
    	}

    	if(get_post_type() == 'projects')
    	{
            if(is_single())
	   		{
            	if($theme_file = locate_template(array('single-projects.php'))) 
            	{
            		$template_path = $theme_file;
            	} 

            	else 
            	{
               	 	$template_path = plugin_dir_path( __FILE__ ) . '/single-projects.php';
            	}
           }
    	}

    	if(get_post_type() == 'ads')
    	{
            if(is_single())
            {
                if($theme_file = locate_template(array('single-ads.php'))) 
                {
                    $template_path = $theme_file;
                } 

                else 
                {
                    $template_path = plugin_dir_path( __FILE__ ) . '/single-ads.php';
                }
           }
    	}

    	return $template_path;
	}
?>