<?php
	/*
		Plugin Name: Groenestraat MemberInformatie
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont het profiel van een user. 
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add Shortcodes
	*/

	add_shortcode('member_informatie','prowpt_memberinformatie');

	register_activation_hook(__FILE__, 'prowp_memberinformatie_install');

	function prowp_memberinformatie_install()
	{
		makeMemberInformationShortcode('Member informatie','[member_informatie]','member informatie','publish','page','closed');
	}

	function makeMemberInformationShortcode($title,$content,$post_name,$post_status,$post_type,$ping_status)
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


	function prowpt_memberinformatie()
	{
		if(is_user_logged_in())
		{
			if(isset($_GET["userid"]))
			{
				$userid = $_GET["userid"];
				$user = get_userdata($userid);
				$usermeta = get_user_meta($userid);

				?>
						<strong>Gebruikersnaam: </strong><p><?php echo $user->user_login; ?></p>
				<?php
				if(get_user_meta($user->ID, "rpr_gegevens", true) == 1)
				{
					?>
						<strong>E-mail: </strong><p><?php echo $user->user_email; ?></p>
					<?php
					if($usermeta['rpr_straat'][0] != "" || $usermeta['rpr_postcode'][0] != "" || $usermeta['rpr_gemeente'][0] != "")
					{
					?>
						<strong>Adres: </strong><p><?php echo $usermeta['rpr_straat'][0] . ', ' . $usermeta['rpr_postcode'][0] . ' ' . $usermeta['rpr_gemeente'][0]; ?></p>
					<?php
					}
					if($usermeta['rpr_telefoon'][0] != "")
					{
					?>
						<strong>Telefoon: </strong><p><?php echo $usermeta['rpr_telefoon'][0]; ?></p>
					<?php
					}
				}

				echo get_avatar($userid); 
				?>
					<h1>Activiteiten gebruiker</h1>
				<?php
				global $post;
				$the_query = new WP_Query(
				array(
					'author' => $userid,
					'post_type' => array( 'projecten', 'events', 'zoekertjes', 'post'),
					'order' => 'ASC',
					'orderby' => 'date')
				);

				if ($the_query->have_posts()) {
					while ($the_query->have_posts() ) {
						$the_query->the_post();	
						$post_type = $post->post_type;

						?>
						<h2><?php echo get_the_title(); ?></h2>
						<p><strong>Omschrijving: </strong></p><p><?php echo get_the_excerpt(); ?></p>
						<?php

						$meta = get_post_meta($post->ID);
						switch ($post_type) {
						    case "projecten":
						    	$projectStreet = $meta['_projectStreet'][0];
								$projectCity = $meta['_projectCity'][0];
								$projectZipcode = $meta['_projectZipcode'][0];

								?>	
									<p><strong>Straat: </strong></p><p><?php echo $projectStreet; ?></p>
									<p><strong>Gemeente: </strong></p><p><?php echo $projectCity; ?></p>
									<p><strong>Postcode: </strong></p><p><?php echo $projectZipcode; ?></p>
								<?php
						        break;
						    case "events":
						       	$eventTime = $meta['_eventTime'][0];
								$eventEndTime = $meta['_eventEndTime'][0];
								$eventLocation = $meta['_eventLocation'][0];

								?>
									<p><strong>Van: </strong></p><p><?php echo $eventTime; ?></p>
									<p><strong>Tot: </strong></p><p><?php echo $eventEndTime; ?></p>
									<p><strong>Locatie: </strong></p><p><?php echo $eventLocation; ?></p>
								<?php
						        break;
						    case "zoekertjes":
						        $adPrice = $meta['_adPrice'][0];
								$adLocation = $meta['_adLocation'][0];

								?>
									<p><strong>Prijs: </strong></p><p><?php echo $adPrice; ?></p>
									<p><strong>Locatie: </strong></p><p><?php echo $adLocation; ?></p>
								<?php
						        break;
						}
					}
				} else {
					?>
						<h2>Er werden geen activiteiten gevonden.</h2>
					<?php
				}

				?>
					<a href="<?php echo site_url().'/leden-overzicht'; ?>">Terug naar ledenoverzicht</a>
				<?php
			}
			else
			{
				?>
					<p>De URL die u hebt meegegeven is niet geldig.</p>
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