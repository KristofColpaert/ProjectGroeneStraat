<?php
	/*
		Plugin Name: Groenestraat Projectleden Overzicht
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont een overzicht van de leden van een bepaald project. Leden kunnen verwijderd en/of toegevoegd worden.
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add actions
	*/

	add_shortcode('project_leden','prowpt_projectleden_overzicht');

	add_action('wp_ajax_nopriv_delete_project_member', 'delete_project_member');
	add_action('wp_ajax_delete_project_member', 'delete_project_member');
	add_action('wp_ajax_nopriv_get_add_form', 'get_add_form');
	add_action('wp_ajax_get_add_form', 'get_add_form');
	add_action('wp_ajax_nopriv_add_project_member', 'add_project_member');
	add_action('wp_ajax_add_project_member', 'add_project_member');
	add_action('wp_ajax_nopriv_show_updated_project_members', 'show_updated_project_members');
	add_action('wp_ajax_show_updated_project_members', 'show_updated_project_members');

	register_activation_hook(__FILE__, 'prowp_ledenProject_install');

	function prowp_ledenProject_install()
	{
		makeLedenProjectShortcodePage('Projectleden','[project_leden]','projectleden','publish','page','closed');
	}

	function makeLedenProjectShortcodePage($title,$content,$post_name,$post_status,$post_type,$ping_status)
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

	/*
		Plugin methods
	*/

	function prowpt_projectleden_overzicht()
	{
		if(is_user_logged_in() && isset($_GET['project']))
		{
			global $wpdb;
			$letters = array();
			$current_user = wp_get_current_user();
			$project = get_post($_GET['project']);
			$previousEersteLetter;
			$isFirst = true;

			if($project != null && ($project->post_author == $current_user->ID))
			{
				$subscriber = '_subscriberId';
				$users = $wpdb->get_results($wpdb->prepare("SELECT a.post_id, a.meta_key AS aKey, a.meta_value AS userId, b.user_nicename AS username FROM $wpdb->postmeta a INNER JOIN $wpdb->users b ON a.meta_value = b.ID WHERE a.post_id = %d AND a.meta_key = %s ORDER BY b.user_nicename ASC", $project->ID, $subscriber), ARRAY_A);
				?>
					<a href="<?php echo get_permalink($project->ID); ?>">Ga terug naar het project</a>
					<section id="projectMemberContainer">
				<?php
				if(count($users) > 1)
				{
					foreach ($users as $user)
					{
						$userData = get_userdata($user["userId"]);

						// Wanneer user gelijk is aan de ingelogde user, dan wordt hij die niet getoond
						if(!$userData->ID == $current_user->ID)
						{
							continue;
						}

						$previousEersteLetter = str_split($userData->display_name, 1)[0];
						continue;
					}

					foreach ($users as $user) 
					{
						$userData = get_userdata($user["userId"]);

						// Wanneer user gelijk is aan de ingelogde user, dan wordt hij die niet getoond
						if($userData->ID == $current_user->ID)
						{
							continue;
						}

						$eersteLetter = str_split($userData->display_name, 1)[0];

						if(!in_array($eersteLetter, $letters))
						{
							if($previousEersteLetter != $eersteLetter)
							{
								if($isFirst)
								{
									$isFirst = false;
								}

								else
								{
									?>
										</section>
									<?php
								}
							}

							$letters[] = $eersteLetter;
							$previousEersteLetter = $eersteLetter;
							?>
								<section class="projectMemberContainer" id="projectMemberContainer<?php echo $eersteLetter; ?>">
								<h1><?php echo $eersteLetter; ?></h1>
							<?php
						}

						$firstname = get_user_meta($user['userId'], 'first_name', true);
						$name = get_user_meta($user['userId'], 'last_name', true);

						?>
							<section class="projectMember" id="projectMemberContainer<?php echo $userData->ID; ?>">
								<a href="<?php echo home_url(); ?>/member-informatie?userid=<?php echo $userData->ID; ?>"><?php if($firstname != '' && $name != '')echo $firstname . " " . $name; else echo $userData->display_name ?></a>
								<input type="button" value="Verwijder" class="projectMemberDelete form-button" data="<?php echo $project->ID . ';' . $user['userId']; ?>" id="projectLedenDelete<?php echo $userData->ID; ?>" />
							</section>
						<?php
					}
				}
				else
				{
					?>
						<p class="error-message">Het project heeft geen leden. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a> of voeg er toe.</p>
					<?php
				}

				?>
					</section>
					<section id="projectMemberSubmitContainer" data="<?php echo $project->ID; ?>">
						<input type="button" class="form-button" value="Leden toevoegen" id="projectMemberSubmitMember" data="<?php echo $project->ID; ?>" />
					</section>
				<?php
			}

			else
			{
				?>
					<p class="error-message">U hebt geen toegang tot het gevraagde project. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
				<?php
			}
		}
		else
		{
			?>
				<p class="error-message">Dit project bestaat niet of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function delete_project_member()
	{
		if(isset($_POST['projectId']) && isset($_POST['userId']))
		{
			$projectId = $_POST['projectId'];
			$userId = $_POST['userId'];
			delete_post_meta($projectId, '_subscriberId', $userId);
			echo 'success';
			die();
		}
		echo 'fail';
		die();
	}

	function get_add_form()
	{
		?>
			<form>
				<input type="text" class="textbox" id="projectMemberAddUsername" placeholder="Gebruikersnaam/e-mailadres"/>
			</form>
		<?php
		die();
	}

	function add_project_member()
	{
		if(isset($_POST['username']) && isset($_POST['projectId']))
		{
			$username = $_POST['username'];
			$projectId = $_POST['projectId'];

			$user = get_user_by('email', $username);
			$project = get_post($projectId, OBJECT);

			if($user != null && $project != null)
			{
				add_post_meta($project->ID, '_subscriberId', $user->ID);
				echo 'success';
				die();
			}
		}
		echo 'failed';
		die();
	}

	function show_updated_project_members()
	{
		if(isset($_POST['projectId']) && isset($_POST['username']))
		{
			$username = $_POST['username'];
			$projectId = $_POST['projectId'];

			$user = get_user_by('email', $username);
			$project = get_post($projectId, OBJECT);

			if($user != null && $project != null)
			{
				$firstname = get_user_meta($user->ID, 'first_name', true);
				$name = get_user_meta($user->ID, 'last_name', true);
				$display_name = get_user_meta($user->ID, 'display_name', true);

				?>
					<a class="newProjectMember" href="/member-informatie?userid=<?php echo $user->ID; ?>"><?php if($firstname != '' && $name != '')echo $firstname . " " . $name; else echo $display_name?></a><br />
				<?php
				die();
			}
		}

		echo 'fail';
		die();
	}
?>