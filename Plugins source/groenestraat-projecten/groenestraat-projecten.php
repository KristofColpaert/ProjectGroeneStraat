<?php
	/*
		Plugin Name: Groenestraat Projecten
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin voegt het custom post type Projecten toe. 
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
		initialize_projecten_capabilities();
	}

	function initialize_projecten_capabilities()
	{
		$roleAdministrator = get_role('administrator');
		$roleAuthor = get_role('author');
		$roleContributor = get_role('contributor');

		$roleAdministrator->add_cap('publish_projecten');
		$roleAdministrator->add_cap('edit_projecten');
		$roleAdministrator->add_cap('edit_others_projecten');
		$roleAdministrator->add_cap('delete_projecten');
		$roleAdministrator->add_cap('delete_others_projecten');
		$roleAdministrator->add_cap('read_private_projecten');
		$roleAdministrator->add_cap('edit_project');
		$roleAdministrator->add_cap('delete_project');
		$roleAdministrator->add_cap('read_project');
		$roleAdministrator->add_cap('edit_published_projecten');
		$roleAdministrator->add_cap('delete_published_projecten');

		$roleAuthor->add_cap('publish_projecten');
		$roleAuthor->add_cap('edit_project');
		$roleAuthor->add_cap('edit_projecten');
		$roleAuthor->add_cap('edit_published_projecten');
		$roleAuthor->add_cap('delete_projecten');
		$roleAuthor->add_cap('delete_published_projecten');
		$roleAuthor->add_cap('read_project');

		$roleContributor->add_cap('publish_projecten');
		$roleContributor->add_cap('edit_project');
		$roleContributor->add_cap('edit_projecten');
		$roleContributor->add_cap('edit_published_projecten');
		$roleContributor->add_cap('delete_projecten');
		$roleContributor->add_cap('delete_published_projecten');
		$roleContributor->add_cap('read_project');
		$roleContributor->add_cap('upload_files');
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
			'capabilities' => array(
				'publish_posts' => 'publish_projecten',
				'edit_posts' => 'edit_projecten',
				'edit_others_posts' => 'edit_others_projecten',
				'delete_posts' => 'delete_projecten',
				'delete_others_posts' => 'delete_others_projecten',
				'read_private_posts' => 'read_private_projecten',
				'edit_post' => 'edit_project',
				'delete_post' => 'delete_project',
				'read_post' => 'read_project',
				'edit_published_posts' => 'edit_published_projecten',
				'delete_published_posts' => 'delete_published_projecten'
			),
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
		
		?>
			<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>"/>
		<?php		
		$projectStreet = get_post_meta($post->ID, '_projectStreet', true);
		$projectCity = get_post_meta($post->ID, '_projectCity', true);
		$projectZipcode = get_post_meta($post->ID, '_projectZipcode', true);
		
		?>
			<label class="projectStreet" for="projectStreet">Straat van het project</label><br />
			<input type="text" id="projectStreet" name="_projectStreet" value="<?php echo $projectStreet; ?>" class="widefat"><br />

			<label for="projectCity">Gemeente van het project</label><br />
			<input type="text" id="projectCity" name="_projectCity" value="<?php echo $projectCity; ?>" class="widefat"><br />

			<label class="projectZipcode" for="projectZipcode">Postcode van het project</label><br />
			<input type="text" id="projectZipcode" name="_projectZipcode" value="<?php echo $projectZipcode; ?>" class="widefat"><br />

		<?php
    	wp_enqueue_script('validation', get_stylesheet_directory_uri() . '/js/livevalidation_standalone.compressed.js', array( 'jquery' ));
    	wp_enqueue_script('my_validation', plugins_url() . '/groenestraat-projecten/my_validation.js', array( 'jquery' ));
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

		if(!isset($_POST['_projectStreet']) || empty($_POST['_projectStreet']) ||
			!isset($_POST['_projectCity']) || empty($_POST['_projectCity']) ||
			!isset($_POST['_projectZipcode']) || empty($_POST['_projectZipcode'])
			)
		{
        	$error = new WP_Error('Er is een fout opgetreden. Gelieve alle gegevens (straat, gemeente, postcode) correct in te voeren. <a href="'. $_SERVER['HTTP_REFERER'] .'">Ga terug.</a>');
		    wp_die($error->get_error_code(), 'Error: Missing Arguments');
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

		if($events_meta['_projectStreet'] != null && $events_meta['_projectStreet'] != '' &&
			$events_meta['_projectCity'] != null && $events_meta['_projectCity'] != '')
		{
			//Get location and save Google Street View Image API code in database.
			$tempProjectStreet = str_replace(' ', '%20', $events_meta['_projectStreet']);
			$tempProjectCity = str_replace(' ', '%20', $events_meta['_projectCity']);

			$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $tempProjectStreet . '%20' . $tempProjectCity . '&key=AIzaSyChwJePvaLHTx1xlGAFUHrmjkPWKpVyGVA';
		}

		else
		{
			$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=Grote%20Markt%20Kortrijk&key=AIzaSyChwJePvaLHTx1xlGAFUHrmjkPWKpVyGVA';
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output = curl_exec($ch);   

		$json = json_decode($output, true);
		$lat = $json['results'][0]['geometry']['location']['lat'];
		$lng = $json['results'][0]['geometry']['location']['lng'];

		$imageUrl = 'https://maps.googleapis.com/maps/api/streetview?key=AIzaSyChwJePvaLHTx1xlGAFUHrmjkPWKpVyGVA&size=800x800&location=' . $lat . ',' . $lng . '&fov=90&heading=235&pitch=10';

		add_post_meta($post->ID, '_projectStreetViewThumbnail', $imageUrl);

		curl_close($ch);
	}
?>