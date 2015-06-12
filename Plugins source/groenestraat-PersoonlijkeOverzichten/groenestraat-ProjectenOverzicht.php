<?php
	/*
		Plugin Name: Groenestraat Mijn Projecten Overzicht
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont de projecten waarvan de ingelogde gebruiker lid van is. 
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add Shortcodes
	*/
		
	add_shortcode('mijn_projecten','prowpt_persoonlijkeProjectenOverzicht');

	register_activation_hook(__FILE__, 'prowp_persoonlijkeProjecten_install');

	function prowp_persoonlijkeProjecten_install()
	{
		makePersProjectenShortcode('Mijn projecten','[mijn_projecten]','mijn projecten','publish','page','closed');
	}

	function makePersProjectenShortcode($title,$content,$post_name,$post_status,$post_type,$ping_status)
	{
		$args = array(
			'post_title' => $title,
			'post_content' => $content,
			'post_name' => $post_name,
			'post_status' => $post_status, 
			'post_type' => $post_type,
			'ping_status' => $ping_status
		);
		wp_insert_post($args);
	}




function prowpt_persoonlijkeProjectenOverzicht()
{
	global $post;

	$subscriber = "_subscriberId";
	$userId = get_current_user_id(); 

	if(is_user_logged_in())
	{
		//ophalen subscribers
		global $post;

		//ophalen projecten waarop hij gesubscribed is en heeft aangemaakt
		$projectenSubscribed = array(
					'post_type' => 'projecten',
					'order' => 'ASC',
					'orderby' => 'date',
					'meta_key' => '_subscriberId',
					'meta_compare' => '=',
            		'meta_query' => array(
                		array(
                        'key' => '_subscriberId',
                        'value' => $userId)));

		$the_query = new WP_Query($projectenSubscribed);
				
		if ($the_query->have_posts()) {
				while ($the_query->have_posts() ) {
							$the_query->the_post();	

							$projectAdminId = $post->post_author;
							?>
								<h2><?php echo get_the_title(); ?></h2>
							<?php	
							$street = get_post_meta($post->ID, "_projectStreet")[0];
							$city = get_post_meta($post->ID, "_projectCity")[0];
							$zipcode = get_post_meta($post->ID, "_projectZipcode")[0];

							if(!empty($street) && !empty($city) && !empty($zipcode))
							{
								?>
								<h1><?php echo $title; ?></h1>
								<strong>Street</strong><p><?php echo $street; ?></p>
								<strong>City</strong><p><?php echo $city; ?></p>
								<strong>Zipcode</strong><p><?php echo $zipcode; ?></p>
								<strong>Omschrijving</strong><p><?php echo get_the_excerpt(); ?></p>
								<?php
								if($userId == $projectAdminId)
								{
									?>
									<a href="<?php echo site_url() . '/bewerk-project?project=' .  $post->ID ?>">Bewerk project</a>
									<?php
								}
							}
				}

		}
		else
		{
			?>	
				<p>Er werden geen projecten gevonden.</p>
			<?php
		}
	}
	else
	{
		?>	
			<p>U moet zich aanmelden om deze pagina te bekijken.</p>
		<?php
	}

}
?>