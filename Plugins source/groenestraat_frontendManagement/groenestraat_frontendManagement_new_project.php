<?php

	include_once('helper.php');

	/*
		Shortcode plugin
	*/

	add_shortcode('new_project', 'prowp_new_project');

	function prowp_new_project()
	{
		save_new_project_form();
		show_new_project_form();
	}

	function show_new_project_form()
	{
		if(current_user_can('publish_posts'))
		{
			?>
				<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
					<input id="projectTitle" class="textbox" name="projectTitle" type="text" placeholder="Titel"/>

					<label for="projectDescription" class="normalize-text">Beschrijving</label><br \>
					<?php 
						$settings = array('textarea_name' => 'projectDescription');
						$content = '';
						$editor_id = 'projectDescription';

						wp_editor($content, $editor_id, $settings);
					?>

					
					<input class="textbox" id="projectStreet" name="projectStreet" type="text" placeholder="Straat"/>

					
					<input class="textbox" id="projectCity" name="projectCity" type="text" placeholder="Gemeente"/>

					
					<input class="textbox" id="projectZipcode" name="projectZipcode" type="text" placeholder="Postcode" />

					<label for="projectFeaturedImage" class="normalize-text">Afbeelding</label>
                    
                        <input id="projectFeaturedImage" name="projectFeaturedImage" type="file" accept="image/x-png, image/gif, image/jpeg" />
					

					<input class="form-button" id="projectPublish" name="projectPublish" type="submit" value="Publiceer" />
				</form>
			<?php
		}

		else 
		{
			?>
				<p class="error-message">U hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_new_project_form()
	{
		if(isset($_POST['projectPublish']))
		{
            if(!empty($_POST['projectTitle'])&&!empty($_POST['projectDescription'])&&!empty($_POST['projectStreet'])&&!empty($_POST['projectCity'])&&!empty($_POST['projectZipcode'])){
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
					'comment_status' => 'closed',
					'ping_status' => 'closed',
					'post_author' => $current_user->ID,
					'post_name' => $slug,
					'post_title' => $projectTitle,
					'post_content' => $projectDescription,
					'post_status' => 'publish',
					'post_type' => 'projecten'
				);

				$postId = wp_insert_post($args, false);

				add_post_meta($postId, '_projectStreet', $projectStreet);
				add_post_meta($postId, '_projectCity', $projectCity);
				add_post_meta($postId, '_projectZipcode', $projectZipcode);

				if($_FILES)
				{
					foreach ($_FILES as $file => $array) 
					{
    					$newupload = insert_featured_image($file, $postId);
				    }
				}
                header('Location: '.get_permalink($postId));
				
			}

			else
			{
				?>
					<p class="error-message">Helaas, er bestaat reeds een project met deze titel.</p>
				<?php
			}
            }
            else{
                ?>
					<p class="error-message">Gelieve alle gegevens correct in te voeren.</p>
				<?php
            }
		}
	}
?>