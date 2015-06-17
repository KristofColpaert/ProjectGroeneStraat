<?php
	/*
		Plugin Name: Groenestraat Mijn Artikels Overzicht
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont de artikels die de ingelogde gebruiker aangemaakt heeft. 
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert, Koen Van Crombrugge en Vincent De Ridder
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add Shortcodes
	*/
		
	add_shortcode('mijn_artikels','prowpt_persoonlijkeArtikelsOverzicht');

	register_activation_hook(__FILE__, 'prowp_persoonlijkeArtikels_install');

	function prowp_persoonlijkeArtikels_install()
	{
		//Persoonlijke overzicht van artikels
		makePersArtikelsShortcode('Mijn artikels','[mijn_artikels]','mijn artikels','publish','page','closed');
	}

	function makePersArtikelsShortcode($title,$content,$post_name,$post_status,$post_type,$ping_status)
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

function prowpt_persoonlijkeArtikelsOverzicht()
{
	global $post;

	$artikels = array();
	$userId = get_current_user_id(); 

	if(is_user_logged_in())
	{	
		//artikels ophalen die ingelogde gebruiker zelf heeft aangemaakt.
		$post_author = $userId;

		global $post;
		$the_query = new WP_Query(
				array(
					'author' => $userId,
					'post_type' => 'post',
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
			        $(".container").append('<section class="sub-menu"><ul><li><a onClick="history.go(-1)">Terug</a></li></ul><section class="options"><form action="<?php $_SERVER['PHP_SELF']; ?>" method="GET"><input type="text" name="zoekveld" class="textbox" placeholder="Zoeken op artikel"><input type="submit" class="form-button" value="zoeken" name="zoeken"></form></section>');
			 	</script>
			<?php

				while ($the_query->have_posts() ) {
						$the_query->the_post();	
						?> 
							<section class="list-item" style="margin-top:3%;margin-bottom:1.5%">
								<h1><?php echo get_the_title(); ?></h1>
								<p><?php echo the_content(); ?></p><br/>
								<a class="confirm-button-green" href="<?php echo site_url().'/bewerk-artikel?artikel='. $post->ID; ?>">Bewerk artikel</a>
								<a class="confirm-button-green" href="<?php echo site_url().'/verwijder-artikel?artikel='. $post->ID; ?>">Verwijder artikel</a>
							</section>
						<?php
				}
		}
		else
		{
			?>
				<p class="error-message">Er werden geen artikels gevonden. <a class="normalize-text" href="<?php echo home_url(); ?>/artikels">Terug</a></p>
			<?php
		}
	}
	else
	{
		?>
			<p class="error-message">U moet zich eerst aanmelden om deze pagina te bekijken. <a class="normalize-text" href="<?php echo home_url(); ?>/login">Aanmelden</a></p>
		<?php
	}
}
?>