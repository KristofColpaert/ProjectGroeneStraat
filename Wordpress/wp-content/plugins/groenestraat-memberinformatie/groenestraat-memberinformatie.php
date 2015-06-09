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

				echo '<strong>Gebruikersnaam: </strong><label>'. $user->user_login . '</label><br />';

				if(get_user_meta($user->ID, "rpr_gegevens", true) == 1)
				{
					echo '<strong>E-mail: </strong><label>'. $user->user_email  . '</label><br />';
					if($usermeta['rpr_straat'][0] != "" || $usermeta['rpr_postcode'][0] != "" || $usermeta['rpr_gemeente'][0] != "")
					{
						echo '<strong>Adres: </strong><label>'. $usermeta['rpr_straat'][0] . ', ' . $usermeta['rpr_postcode'][0] . ' ' . $usermeta['rpr_gemeente'][0] . '</label><br />';
					}
					if($usermeta['rpr_telefoon'][0] != "")
					{
						echo '<strong>Telefoon: </strong><label>'. $usermeta['rpr_telefoon'][0]  .'</label><br />';
					}
				}

				echo get_avatar($userid); 

				print '<hr />';
				print '<h1> Activiteiten gebruiker</h1>';
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

						echo '<h2>' . get_the_title() . '</h2>';
						echo '<p>' . get_the_excerpt() . '</p>';

						$meta = get_post_meta($post->ID);
						switch ($post_type) {
						    case "projecten":
						    	$projectStreet = $meta['_projectStreet'][0];
								$projectCity = $meta['_projectCity'][0];
								$projectZipcode = $meta['_projectZipcode'][0];
								print "<label>Straat:</label><p>" . $projectStreet . '</p>';
								print "<label>Stad:</label><p>" . $projectCity . '</p>';
								print "<label>Zipcode:</label><p>" . $projectZipcode . '</p>';
						        break;
						    case "events":
						       	$eventTime = $meta['_eventTime'][0];
								$eventEndTime = $meta['_eventEndTime'][0];
								$eventLocation = $meta['_eventLocation'][0];
								print "<label>Startdatum:</label><p>" . $eventTime . '</p>';
								print "<label>Einddatum:</label><p>" . $eventEndTime . '</p>';
								print "<label>Locatie:</label><p>" . $eventLocation . '</p>';
						        break;
						    case "zoekertjes":
						        $adPrice = $meta['_adPrice'][0];
								$adLocation = $meta['_adLocation'][0];
								print "<label>Prijs:</label><p> € " . $adPrice . '</p>';
								print "<label>Locatie:</label><p>" . $adLocation . '</p>';
						        break;
						}
						echo '<hr />';
					}
				} else {
					echo "<h2>Er werden geen activiteiten gevonden.</h2>";
				}

				echo '<a href="'.site_url().'/leden-overzicht">Terug naar ledenoverzicht</a>';

			}
			else
			{
				echo "Er werd een verkeerde URL ingegeven.";
			}
		}
		else
		{
			echo "Gelieve u aan te melden om deze pagina te bekijken.";
		}
	}

?>