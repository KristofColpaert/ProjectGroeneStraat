<?php
	/*
		Plugin Name: Groenestraat Project LedenOverzicht
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont een overzicht van de leden van een bepaald project.
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add actions
	*/

//deleten van user gekoppeld aan het project.
if(isset($_POST["ProjectID"]) && isset($_POST["Verwijderen"]))
{
	if(!empty($_POST["ProjectID"]) && !empty($_POST["Verwijderen"]))
	{
		$post_id = $_POST["ProjectID"];
		$meta_key = "_subscriberId";
		$meta_value =  $_POST["Verwijderen"];
		delete_post_meta($post_id, $meta_key, $meta_value); 
	}
}

add_shortcode('leden_project','prowpt_ledenProject_overzicht');

register_activation_hook(__FILE__, 'prowp_ledenProject_install');

	function prowp_ledenProject_install()
	{
		makeLedenProjectShortcode('Leden project','[leden_project]','leden project','publish','page','closed');
	}

	function makeLedenProjectShortcode($title,$content,$post_name,$post_status,$post_type,$ping_status)
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


	function prowpt_ledenProject_overzicht()
	{
		if(is_user_logged_in())
		{
			$userId = get_current_user_id(); 

			global $wpdb;
			$projectID = 300;
			$subscriber = "_subscriberId";

			//ophalen alle gebruikers die gesubscribed hebben op het geselecteerde project
			$users = $wpdb->get_results($wpdb->prepare( "SELECT post_id, meta_key, meta_value AS userId FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $projectID, $subscriber), ARRAY_A);

			?>
				<form method="POST" class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
			<?php
			if(count($users) > 0)
			{
				foreach ($users as $user) 
				{
					$userData = get_userdata($user["userId"]);

					//als user gelijk is als de ingelogde user dan wordt hij die niet getoond;
					if($user->ID == get_current_user_id())
					{
						continue;
					}

					$eersteLetter = str_split($userData->display_name, 1)[0];

					if(!in_array($eersteLetter, $letters))
					{
						$letters[] = $eersteLetter;
						echo '<h1>' . $eersteLetter . '</h1>';
					}

					echo '<a href="/member-informatie?userid=' . $userData->ID . '">' . esc_html( $userData->display_name ) . '</a>
					<a href="" value="' . $userData->ID . '" name="Verwijderen"> delete </a>';
				}

			}
			else
			{
				echo "Het project heeft nog geen leden.";
			}

			?>
				<br />
				<br />
				<input type="submit" value="Toevoegen" name="Toevoegen" />
				<input type="hidden" value="<?php echo $projectID ?>" name="ProjectID" />
				</form>
			<?php
			
		}
		else
		{
			echo "Gelieve u aan te melden om deze pagina te bekijken.";
		}
	}


?>