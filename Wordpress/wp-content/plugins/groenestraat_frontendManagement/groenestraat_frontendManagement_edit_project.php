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

			if($project != null && $current_user->ID == $project->post_author && current_user_can('edit_published_posts'))
			{
				?>
					<form action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
						<label for="projectTitle">Titel van het project</label>
						<input id="projectTitle" name="projectTitle" type="text" value="<?php echo $project->post_title; ?>"/>

						<label for="projectDescription">Beschrijving van het project</label>
						<?php 
							$settings = array('textarea_name' => 'projectDescription');
							$content = $project->post_content;
							$editor_id = 'projectDescription';

							wp_editor($content, $editor_id, $settings);

							$projectStreet = get_post_meta($project->ID, '_projectStreet')[0];
							$projectCity = get_post_meta($project->ID, '_projectCity')[0];
							$projectZipcode = get_post_meta($project->ID, '_projectZipcode')[0];
						?>

						<label for="projectStreet">Straat van het project</label>
						<input id="projectStreet" name="projectStreet" type="text" value="<?php echo $projectStreet; ?>"/>

						<label for="projectCity">Gemeente van het project</label>
						<input id="projectCity" name="projectCity" type="text" value="<?php echo $projectCity; ?>"/>

						<label for="projectZipcode">Postcode van het project</label>
						<input id="projectZipcode" name="projectZipcode" type="text" value="<?php echo $projectZipcode; ?>"/>

						<label for="projectFeaturedImage">Stel een hoofdingsafbeelding in</label>
						<input id="projectFeaturedImage" name="projectFeaturedImage" type="file" accept="image/x-png, image/gif, image/jpeg" />

						<br />

						<input id="projectId" name="projectId" type="hidden" value="<?php echo $project->ID; ?>" />
						
						<input id="projectPublish" name="projectPublish" type="submit" value="Bewerk" />
					</form>
				<?php
			}

			else
			{
				?>
					<p>Dit project bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.
				<?php
			}
		}
	}

	function save_edit_project_form()
	{
		if(isset($_POST['projectPublish']))
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
					<p>Uw project werd correct gewijzigd. Ga er <a href="<?php echo esc_url(get_permalink($postId)); ?>">meteen</a> naartoe.
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