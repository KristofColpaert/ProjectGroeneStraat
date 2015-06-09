<?php
	/*
		Plugin Name: Groenestraat
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin initialiseert de WordPress-installatie en zorgt ervoor dat alle nodige plugins geactiveerd worden.
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	/*
		Install plugins
	*/
	register_activation_hook(__FILE__, 'prowp_groenestraat_install');
	add_action('admin_head','check_post_type_and_remove_media_buttons');

	function prowp_groenestraat_install()
	{
		makeInitialShortcodePage('Registreren', '', 'registreren', 'publish', 'page', 'closed','page-register.php');
		makeInitialShortcodePage('Login', '', 'login', 'publish', 'page', 'closed', 'page-login.php');
		makeInitialShortcodePage('Forgot', '', 'forgot', 'publish', 'page', 'closed', 'page-forgot.php');
		makeInitialShortcodePage('Artikels', '', 'artikels', 'publish', 'page', 'closed', 'page-artikels.php');
		register_main_menu();
		activate_all_plugins();
	}

	/*
		Plugin methods
	*/

	function makeInitialShortcodePage($title,$content,$post_name,$post_status,$post_type,$ping_status,$page_template)
	{
		$args = array(
			'post_title' => $title,
			'post_content' => $content,
			'post_name' => $post_name,
			'post_status' => $post_status, 
			'post_type' => $post_type,
			'ping_status' => $ping_status,
			'page_template' => $page_template
		);
		wp_insert_post($args);
	}

	function check_post_type_and_remove_media_buttons()
	{
		global $current_screen;

		if('projecten' == $current_screen->post_type
			|| 'zoekertjes' == $current_screen->post_type
			|| 'events' == $current_screen->post_type)
		{
			remove_action('media_buttons', 'media_buttons');
		}

		else if('post' == $current_screen->post_type)
		{
			remove_meta_box( 'postimagediv', 'post', 'side' );
		}
	}

	function register_main_menu()
	{
		$menuExists = wp_get_nav_menu_object('Main menu');

		if(!$menuExists)
		{
			$menuId = wp_create_nav_menu('Main menu');

			wp_update_nav_menu_item($menuId, 0, array(
				'menu-item-title' => __('Artikels'),
				'menu-item-classes' => 'basic-menu-item normalize-text',
				'menu-item-url' => home_url('/artikels'),
				'menu-item-status' => 'publish'
			));

			wp_update_nav_menu_item($menuId, 0, array(
				'menu-item-title' => __('Projecten'),
				'menu-item-classes' => 'basic-menu-item normalize-text',
				'menu-item-url' => home_url('/projecten'),
				'menu-item-status' => 'publish'
			));

			wp_update_nav_menu_item($menuId, 0, array(
				'menu-item-title' => __('Events'),
				'menu-item-classes' => 'basic-menu-item normalize-text',
				'menu-item-url' => home_url('/events'),
				'menu-item-status' => 'publish'
			));

			wp_update_nav_menu_item($menuId, 0, array(
				'menu-item-title' => __('Zoekertjes'),
				'menu-item-classes' => 'basic-menu-item normalize-text',
				'menu-item-url' => home_url('/zoekertjes'),
				'menu-item-status' => 'publish'
			));
		}
	}

	function activate_all_plugins()
	{
		add_action('update_option_active_plugins', function()
		{
			if(is_plugin_inactive('custom-forgot-mail/index.php'))
			{
				activate_plugin('custom-forgot-mail/index.php');
			}
			
			if(is_plugin_inactive('groenestraat_frontendManagement/groenestraat_frontendManagement.php'))
			{
				activate_plugin('groenestraat_frontendManagement/groenestraat_frontendManagement.php');
			}
			
			if(is_plugin_inactive('groenestraat-contactForm7Extension/groenestraat-contactForm7Extension.php'))
			{
				activate_plugin('groenestraat-contactForm7Extension/groenestraat-contactForm7Extension.php');
			}

			if(is_plugin_inactive('groenestraat-createManager/groenestraat-createManager.php'))
			{
				activate_plugin('groenestraat-createManager/groenestraat-createManager.php');
			}

			if(is_plugin_inactive('groenestraat-deleteManager/groenestraat-deleteManager.php'))
			{
				activate_plugin('groenestraat-deleteManager/groenestraat-deleteManager.php');
			}

			if(is_plugin_inactive('groenestraat-eventMember/groenestraat-eventMember.php'))
			{
				activate_plugin('groenestraat-eventMember/groenestraat-eventMember.php');
			}

			if(is_plugin_inactive('groenestraat-events/groenestraat-events.php'))
			{
				activate_plugin('groenestraat-events/groenestraat-events.php');
			}

			if(is_plugin_inactive('groenestraat-kalender/groenestraat-kalender.php'))
			{
				activate_plugin('groenestraat-kalender/groenestraat-kalender.php');
			}

			if(is_plugin_inactive('groenestraat-ledenoverzicht/groenestraat-ledenoverzicht.php'))
			{
				activate_plugin('groenestraat-ledenoverzicht/groenestraat-ledenoverzicht.php');
			}

			if(is_plugin_inactive('groenestraat-memberinformatie/groenestraat-memberinformatie.php'))
			{
				activate_plugin('groenestraat-memberinformatie/groenestraat-memberinformatie.php');
			}

			if(is_plugin_inactive('groenestraat-nieuwsbrief/groenestraat-nieuwsbrief.php'))
			{
				activate_plugin('groenestraat-nieuwsbrief/groenestraat-nieuwsbrief.php');
			}

			if(is_plugin_inactive('groenestraat-PersoonlijkeOverzichten/groenestraat-EventenOverzicht.php'))
			{
				activate_plugin('groenestraat-PersoonlijkeOverzichten/groenestraat-EventenOverzicht.php');
			}

			if(is_plugin_inactive('groenestraat-PersoonlijkeOverzichten/groenestraat-ProjectenOverzicht.php'))
			{
				activate_plugin('groenestraat-PersoonlijkeOverzichten/groenestraat-ProjectenOverzicht.php');
			}

			if(is_plugin_inactive('groenestraat-PersoonlijkeOverzichten/groenestraat-ZoekertjesOverzicht.php'))
			{
				activate_plugin('groenestraat-PersoonlijkeOverzichten/groenestraat-ZoekertjesOverzicht.php');
			}

			if(is_plugin_inactive('groenestraat-projectArticleManagement/groenestraat-projectArticleManagement.php'))
			{
				activate_plugin('groenestraat-projectArticleManagement/groenestraat-projectArticleManagement.php');
			}

			if(is_plugin_inactive('groenestraat-projecten/groenestraat-projecten.php'))
			{
				activate_plugin('groenestraat-projecten/groenestraat-projecten.php');
			}

			if(is_plugin_inactive('groenestraat-projectmail/groenestraat-projectmail.php'))
			{
				activate_plugin('groenestraat-projectmail/groenestraat-projectmail.php');
			}

			if(is_plugin_inactive('groenestraat-projectMember/groenestraat-projectMember.php'))
			{
				activate_plugin('groenestraat-projectMember/groenestraat-projectMember.php');
			}

			if(is_plugin_inactive('groenestraat-projectsPostExtension/groenestraat-projectsPostExtension.php'))
			{
				activate_plugin('groenestraat-projectsPostExtension/groenestraat-projectsPostExtension.php');
			}

			if(is_plugin_inactive('groenestraat-registrationExtension/groenestraat-registrationExtension.php'))
			{
				activate_plugin('groenestraat-registrationExtension/groenestraat-registrationExtension.php');
			}

			if(is_plugin_inactive('groenestraat-zoekertjes/groenestraat-zoekertjes.php'))
			{
				activate_plugin('groenestraat-zoekertjes/groenestraat-zoekertjes.php');
			}
			if(is_plugin_inactive('register-plus-redux/register-plus-redux.php'))
			{
				activate_plugin('register-plus-redux/register-plus-redux.php');
			}

			if(is_plugin_inactive('wordpress-social-login/wp-social-login.php'))
			{
				activate_plugin('wordpress-social-login/wp-social-login.php');
			}

			if(is_plugin_inactive('wp-user-avatar/wp-user-avatar.php'))
			{
				activate_plugin('wp-user-avatar/wp-user-avatar.php');
			}

			if(is_plugin_inactive('groenestraat-projectLedenoverzicht/groenestraat-projectLedenoverzicht.php'))
			{
				activate_plugin('groenestraat-projectLedenoverzicht/groenestraat-projectLedenoverzicht.php');
			}
		});
	}
?>