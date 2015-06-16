<?php
	/*
		Plugin Name: Groenestraat Mijn Events Overzicht
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont de events waarvan de ingelogde user lid van is. 
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add Shortcodes
	*/
		
	add_shortcode('mijn_events','prowpt_persoonlijkeEventenOverzicht');

	register_activation_hook(__FILE__, 'prowp_persoonlijkeEventen_install');

	function prowp_persoonlijkeEventen_install()
	{
		//Persoonlijke overzicht van events
		makePersEventenShortcode('Mijn events','[mijn_events]','mijn events','publish','page','closed');
	}

	function makePersEventenShortcode($title,$content,$post_name,$post_status,$post_type,$ping_status)
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

function prowpt_persoonlijkeEventenOverzicht()
{
	?>
	
    <?php

	global $post;
	$userId = get_current_user_id(); 

	if(is_user_logged_in())
	{
		//eventen ophalen waar hij zelf auteur van is.
		$post_author = $userId;

		global $post;
		$the_query = new WP_Query(
				array(
					'author' => $userId,
					'post_type' => 'events',
					'order' => 'ASC',
					'orderby' => 'date')
				);

		if ($the_query->have_posts()) {
					?> 
						<script>
						        $(".contentwrapper").addClass("container");
						        $(".container").removeClass("contentwrapper");
						        $("#main").unwrap();
						        $(".container").unwrap();
						        $(".title").remove();
						        $(".container").append('<section class="sub-menu"><ul><li><a onClick="history.go(-1)">Terug</a></li></ul><section class="options"><form action="<?php $_SERVER['PHP_SELF']; ?>" method="GET"><input type="text" name="zoekveld" class="textbox" placeholder="Zoeken op event"><input type="submit" class="form-button" value="zoeken" name="zoeken"></form></section>');
						    </script>
					<?php
					while ($the_query->have_posts() ) {
						$the_query->the_post();	
						$eventTime = get_post_meta($post->ID, "_eventTime")[0];
						$eventEndTime = get_post_meta($post->ID, "_eventEndTime")[0];
						$eventLocation = get_post_meta($post->ID, "_eventLocation")[0];
						$eventStartHour = get_post_meta($post->ID, "_eventStartHour")[0];
						$eventEndHour = get_post_meta($post->ID, "_eventEndHour")[0];

						if(!empty($eventTime) && !empty($eventEndTime) && !empty($eventLocation) && !empty($eventStartHour) && !empty($eventEndHour))
						{
							?>
								<section class="list-item" style="margin-top:3%;margin-bottom:1.5%">
									<h1><?php echo get_the_title(); ?></h1>
									<p><?php echo the_content(); ?></p><br/><br/>
									<p>Van <strong><?php echo $eventTime . ' (' . $eventStartHour . ')' . '</strong> tot <strong>' . $eventEndTime . ' (' . $eventEndHour . ')' . '</strong> te <strong>' . $eventLocation; ?></strong></p><br/>
									<a class="confirm-button-green" href="<?php echo site_url().'/bewerk-event?event='. $post->ID; ?>">Bewerk event</a>
									<a class="confirm-button-green" href="<?php echo site_url().'/verwijder-event?event='. $post->ID; ?>">Verwijder event</a>
								</section>
							<?php
						}

					}
		}
		else
		{
			?>
				<p class="error-message">Er werden geen events gevonden.</p>
			<?php
		}

	}
	else
	{
			?>
				<p class="error-message">U moet zich aanmelden om deze pagina te bekijken.</p>
			<?php
	}

}
?>