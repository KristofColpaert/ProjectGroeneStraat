<?php

	include_once('helper.php');

	/*
		Shortcode plugin
	*/

	add_shortcode('edit_project', 'prowp_edit_project');

	function prowp_edit_project()
	{
		save_edit_project_form();
		show_edit_project_form();
	}

	function show_edit_project_form()
	{
		if(isset($_GET['project']))
		{
			$current_user = wp_get_current_user();
			$project = get_post($_GET['project'], OBJECT);

			if($project != null && ($current_user->ID == $project->post_author || current_user_can('manage_options')) && current_user_can('edit_published_posts'))
			{
				?>
					<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
						<label for="projectTitle" class="normalize-text">Titel</label>
						<input class="textbox" id="projectTitle" name="projectTitle" type="text" value="<?php echo $project->post_title; ?>"/>

						<label for="projectDescription" class="normalize-text">Beschrijving</label><br \>
						<?php 
							$settings = array('textarea_name' => 'projectDescription');
							$content = $project->post_content;
							$editor_id = 'projectDescription';

							wp_editor($content, $editor_id, $settings);

							$projectStreet = get_post_meta($project->ID, '_projectStreet')[0];
							$projectCity = get_post_meta($project->ID, '_projectCity')[0];
							$projectZipcode = get_post_meta($project->ID, '_projectZipcode')[0];
						?>

						<label for="projectStreet" class="normalize-text">Straat</label>
						<input class="textbox" id="projectStreet" name="projectStreet" type="text" value="<?php echo $projectStreet; ?>"/>

						<label for="projectCity" class="normalize-text">Gemeente</label>
						<input class="textbox" id="projectCity" name="projectCity" type="text" value="<?php echo $projectCity; ?>"/>

						<label for="projectZipcode" class="normalize-text">Postcode</label>
						<input class="textbox" id="projectZipcode" name="projectZipcode" type="text" value="<?php echo $projectZipcode; ?>"/>

						<label for="projectFeaturedImage" class="normalize-text">Upload Hoofdigsafbeelding</label>
						<input id="projectFeaturedImage" name="projectFeaturedImage" type="file" accept="image/x-png, image/gif, image/jpeg" />


						<input id="projectId" name="projectId" type="hidden" value="<?php echo $project->ID; ?>" />
						
						<input id="projectEdit" class="form-button" name="projectEdit" type="submit" value="Bewerk" />
					</form>
				<?php
			}

			else
			{
				?>
					<p class="error-message">Dit project bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
				<?php
			}
		}

		else
		{
			?>
				<p class="error-message">Dit project bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_edit_project_form()
	{
		if(isset($_POST['projectEdit']))
		{
			$projectId = $_POST['projectId'];
			$projectTitle = $_POST['projectTitle'];
			$projectDescription = $_POST['projectDescription'];
			$projectStreet = $_POST['projectStreet'];
			$projectCity = $_POST['projectCity'];
			$projectZipcode = $_POST['projectZipcode'];

			if(null == get_page_by_title($projectTitle))
			{
				$slug = str_replace(" ", "-", $projectTitle);
				$current_user = wp_get_current_user();

				$args = array(
					'ID' => $projectId,
					'post_name' => $slug,
					'post_title' => $projectTitle,
					'post_content' => $projectDescription
				);

				$postId = wp_update_post($args);

				update_post_meta($postId, '_projectStreet', $projectStreet);
				update_post_meta($postId, '_projectCity', $projectCity);
				update_post_meta($postId, '_projectZipcode', $projectZipcode);

				if($_FILES)
				{
					foreach($_FILES as $file => $array)
					{
						$newupload = insert_featured_image($file, $postId);
					}
				}
				?>
					<p>Uw project werd correct gewijzigd. Ga er <a href="<?php echo esc_url(get_permalink($postId)); ?>">meteen</a> naartoe.</p>
				<?php
			}

			else
			{
				?>
					<p>Helaas, er bestaat reeds een project met deze titel.</p>
				<?php
			}
		}
	}
?>