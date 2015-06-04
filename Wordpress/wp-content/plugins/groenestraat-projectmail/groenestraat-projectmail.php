<?php
	/*
		Plugin Name: Groenestraat ProjectMail
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin zorgt ervoor dat een projectbeheerder een e-mail kan versturen naar leden van zijn project. 
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	if(isset($_POST["Verzenden"]))
	{
		add_action('plugins_loaded', 'register_sendMail_project_members');
	}

	function register_sendMail_project_members()
	{
		if(isset($_POST["Projecten"]) && isset($_POST["Onderwerp"]) && isset($_POST["Bericht"]))
		{
			if(!empty($_POST["Projecten"]) && !empty($_POST["Onderwerp"]) && !empty($_POST["Bericht"]))
			{
				$selectedProjectId = $_POST["Projecten"];
				$onderwerp = $_POST["Onderwerp"];
				$bericht = $_POST["Bericht"];

				global $wpdb;

				$subscriber = "_subscriberId";
				//ophalen alle gebruikers die gesubscribed hebben op het geselecteerde project
				$results = $wpdb->get_results($wpdb->prepare( "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $selectedProjectId, $subscriber), ARRAY_A);

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

				foreach($results as $result)
				{
					$id = $result['post_id'];
					$postNaam = get_post($id, ARRAY_A)['post_title'];

					$userId= $result['meta_value'];
					$userEmail = get_userdata($userId)->user_email;

					if(wp_mail($userEmail, $onderwerp, $bericht . $postNaam, $headers))
					{
							//echo "Verstuurd";

					}
					else
					{		
							//echo "Niet verstuurd";
					}
				}
			}	
		}		
	}

	add_shortcode('mail_projectmembers', 'prowp_mail');
	register_activation_hook(__FILE__, 'prowp_mailProjectmembers_install');

	function prowp_mailProjectmembers_install()
	{
		//mail pagina aanmaken users
		makeMailUserPage('Project members mailen','[mail_projectmembers]','project-mail-members','publish','page','closed');
	}

	function makeMailUserPage($title,$content,$post_name,$post_status,$post_type,$ping_status)
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

	function prowp_mail()
	{
		show_mail_projectmembers_form();
	}

	function show_mail_projectmembers_form()
	{
		//alle projecten ophalen en overlopen. Dan kijken of author_id = userId
		if(is_user_logged_in())
		{
			?>
				<form method="POST" class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
					<input type="text" name="Onderwerp" placeholder="Vul hier uw onderwerp in" /><br />
					<select name="Projecten">
						<?php
							global $wpdb;
							$postType = "projecten";
							$postAuthor = get_current_user_id();

							$projecten = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $wpdb->posts 
								WHERE post_author = %d AND post_type = %s", $postAuthor, $postType), ARRAY_A);
							print_r($projecten);
							foreach($projecten as $project)
							{
								print "<option value='".$project["ID"]."'>". $project["post_title"]. "</option>";
							}
						?>
					</select><br />
					<textarea name="Bericht">
					</textarea><br />

					<input type="submit" value="Verzenden" name="Verzenden" />
				</form>
			<?php
		}
	}
?>