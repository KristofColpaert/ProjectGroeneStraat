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
		if(is_user_logged_in() && isset($_GET['project']) && !isset($_POST['projectEdit']))
		{
			$current_user = wp_get_current_user();
			$project = get_post($_GET['project'], OBJECT);

			if($project->post_type != 'projecten')
			{
				?>
					<p class="error-message">Dit project bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga <a href="javascript:history.back()-1">terug.</a></p>
				<?php
				return;
			}

			if($project != null && ($current_user->ID == $project->post_author || current_user_can('manage_options')) && current_user_can('edit_published_projecten'))
			{
				?>
					<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
						<label for="projectTitle" class="normalize-text">Titel</label>
						<input class="textbox" id="projectTitle" name="projectTitle" type="text" value="<?php echo $project->post_title; ?>"/>

						<label for="projectDescription" class="normalize-text">Beschrijving</label><br \>
						<?php 
							$settings = array('textarea_name' => 'projectDescription', 'media_buttons' => false);
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
						<div style="height:0px;overflow:hidden">
                        	<input id="projectFeaturedImage" class="image-upload" name="projectFeaturedImage" type="file" accept="image/x-png, image/gif, image/jpeg" />
                    	</div>
                  	<button type="button" class="confirm-button" id="upload" onclick="chooseFile();">Kies afbeelding</button>


						<input id="projectId" name="projectId" type="hidden" value="<?php echo $project->ID; ?>" />
						
						<input id="projectEdit" class="form-button" name="projectEdit" type="submit" value="Bewerk" />
					</form>
					<script>
                        $(document).ready(function () {
                            $("#projectFeaturedImage").on("change", function () {
                                $("#upload").toggleClass("confirm-button");
                                 $("#upload").toggleClass("confirm-button-green");
                            });
                        });
						var nietLeeg = "Dit veld is verplicht!";

						var title = new LiveValidation('projectTitle', {validMessage:" "});
						title.add(Validate.Presence,{failureMessage:nietLeeg});

						var street = new LiveValidation('projectStreet', {validMessage:" "});
						street.add(Validate.Presence,{failureMessage:nietLeeg});
						street.add(Validate.Length,{maximum:30, tooLongMessage: "Maximum 30 tekens lang!"});

						var city = new LiveValidation('projectCity', {validMessage:" "});
						city.add(Validate.Presence,{failureMessage:nietLeeg});
						city.add(Validate.Length,{maximum:20, tooLongMessage: "Maximum 20 tekens lang!"});

						var zipcode = new LiveValidation('projectZipcode', {validMessage:" "});
						zipcode.add(Validate.Presence,{failureMessage:nietLeeg});
						zipcode.add(Validate.Length,{is:4, wrongLengthMessage: "Een postcode moet 4 cijfers bevatten!"});
						zipcode.add(Validate.Numericality,{onlyInteger:true, notANumberMessage: "Een postcode moet een getal zijn!"});
					</script>
				<?php
			}

			else
			{
				?>
					<p class="error-message">Dit project bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga <a href="javascript:history.back()-1">terug.</a></p>
				<?php
			}
		}

		else if(isset($_POST['projectEdit']))
		{}

		else
		{
			?>
				<p class="error-message">Dit project bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga <a href="javascript:history.back()-1">terug.</a></p>
			<?php
		}
	}

	function save_edit_project_form()
	{
		if(isset($_POST['projectEdit']))
		{
			if(!empty($_POST['projectId']) &&
				!empty($_POST['projectTitle']) &&
				!empty($_POST['projectDescription']) &&
				!empty($_POST['projectStreet']) &&
				!empty($_POST['projectCity']) &&
				!empty($_POST['projectZipcode'])
			)
			{
				$projectId = sanitize_text_field($_POST['projectId']);
				$projectTitle = sanitize_text_field($_POST['projectTitle']);
				$projectDescription = $_POST['projectDescription'];
				$projectStreet = sanitize_text_field($_POST['projectStreet']);
				$projectCity = sanitize_text_field($_POST['projectCity']);
				$projectZipcode = sanitize_text_field($_POST['projectZipcode']);

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

					if($_FILES['projectFeaturedImage']['size'] != 0)
					{
						foreach($_FILES as $file => $array)
						{
	                            $newupload = insert_featured_image($file, $postId); 						
						}
					}
	                
					?>
						<img class="center" src="<?php echo get_template_directory_uri() ?>/img/ball.gif" />
		                <script>
		                    $('.title').remove();
		                </script>
		                <h2 class="normalize-text center">Uw project wordt bewerkt</h2>
					<?php

					echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . esc_url(get_permalink($postId)) . '">'; 
					return;	
				}

				else
				{
					?>
						<p class="error-message">Helaas, er bestaat reeds een project met deze titel. Ga <a href="javascript:history.back()-1">terug.</a></p>
					<?php
				}
			}

			else
			{
				?>
					<p class="error-message">Gelieve alle gegevens correct in te voeren. Ga <a href="javascript:history.back()-1">terug.</a></p>
				<?php
			}
		}
	}
?>