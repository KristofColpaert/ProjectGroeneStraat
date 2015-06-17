<?php
	/*
		Plugin Name: Groenestraat Mijn Projecten Overzicht
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont de projecten waarvan de ingelogde gebruiker lid van is. 
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert, Koen Van Crombrugge en Vincent De Ridder
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
		global $post;

		$projectenAdmin = array(
					'post_type' => 'projecten',
					'order' => 'ASC',
					'orderby' => 'date');

		$the_query = new WP_Query($projectenAdmin);				
		if ($the_query->have_posts()) {
				?>
					<script>
				        $(".contentwrapper").addClass("container");
				        $(".container").removeClass("contentwrapper");
				        $("#main").unwrap();
				        $(".container").unwrap();
				        $(".title").remove();
				        $(".container").append('<section class="sub-menu"><ul><li><a onClick="history.go(-1)">Terug</a></li></ul><section class="options"><form action="<?php $_SERVER['PHP_SELF']; ?>" method="GET"><input type="text" name="zoekveld" class="textbox" placeholder="Zoeken op project"><input type="submit" class="form-button" value="zoeken" name="zoeken"></form></section>');
				    </script>
				<?php
				while ($the_query->have_posts() )
				{
							$the_query->the_post();	
							$projectAdminId = $post->post_author;

							if($projectAdminId == $userId)
							{
								$street = get_post_meta($post->ID, "_projectStreet")[0];
								$city = get_post_meta($post->ID, "_projectCity")[0];
								$zipcode = get_post_meta($post->ID, "_projectZipcode")[0];

								if(!empty($street) && !empty($city) && !empty($zipcode))
								{
										?>
											<section class="list-item" style="margin-top:3%;margin-bottom:1.5%">
												<h1><?php echo get_the_title(); ?></h1>
												<p><?php echo the_content(); ?></p><br/>
												<a class="confirm-button-green" href="<?php echo site_url() . '/bewerk-project?project=' .  $post->ID ?>">Bewerk project</a>
												<a class="confirm-button-green" href="<?php echo site_url() . '/verwijder-project?project=' .  $post->ID ?>">Verwijder project</a>
											</section>
										<?php
								}
							}
							else
							{
								$subscribers = get_post_meta($post->ID, "_subscriberId");
								foreach($subscribers as $subscriber)
								{
									if($subscriber == $userId)
									{
										$street = get_post_meta($post->ID, "_projectStreet")[0];
										$city = get_post_meta($post->ID, "_projectCity")[0];
										$zipcode = get_post_meta($post->ID, "_projectZipcode")[0];

										if(!empty($street) && !empty($city) && !empty($zipcode))
										{
											?>
												<section class="list-item" style="margin-top:3%;margin-bottom:1.5%">
													<h1><?php echo get_the_title(); ?></h1>
													<p><?php echo the_content(); ?></p><br/>
													<a class="confirm-button-green" href="<?php echo site_url() . '/bewerk-project?project=' .  $post->ID ?>">Bewerk project</a>
													<a class="confirm-button-green" href="<?php echo site_url() . '/verwijder-project?project=' .  $post->ID ?>">Verwijder project</a>
												</section>
											<?php
										}	
									}
								}
							}	
				}

		}
		else
		{
			?>	
				<p class="error-message">Er werden geen projecten gevonden. <a class="normalize-text" href="<?php echo home_url(); ?>/projecten">Terug</a></p>		
			<?php
		}
	}
	else
	{
		?>	
			<p class="error-message">U moet zich aanmelden om deze pagina te bekijken. <a class="normalize-text" href="<?php echo home_url(); ?>/login">Aanmelden</a></p>
			
		<?php
	}

}
?>