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
		if(is_user_logged_in() && current_user_can('publish_projecten') && !isset($_POST['projectPublish']))
		{
			?>
				<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
					<input id="projectTitle" class="textbox" name="projectTitle" type="text" placeholder="Titel"/>

					<label for="projectDescription" class="normalize-text">Beschrijving</label><br \>
					<?php 
						$settings = array('textarea_name' => 'projectDescription', 'media_buttons' => false);
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
				<script>
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

		else if(isset($_POST['projectPublish']))
		{}

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
            if(!empty($_POST['projectTitle']) && 
            	!empty($_POST['projectDescription']) &&
            	!empty($_POST['projectStreet'])&& 
            	!empty($_POST['projectCity'])&& 
            	!empty($_POST['projectZipcode']))
            {
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

					if($_FILES['projectFeaturedImage']['size'] > 0)
					{
						foreach ($_FILES as $file => $array) 
						{
	    					$newupload = insert_featured_image($file, $postId);
					    }
					}

					else
					{
						$tempProjectStreet = str_replace(' ', '%20', $projectStreet);
						$tempProjectCity = str_replace(' ', '%20', $projectCity);
						$apiKey = get_option('_applicationId');

						$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $tempProjectStreet . '%20' . $tempProjectCity . '&key=' . $apiKey;

						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url); 
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
						$output = curl_exec($ch);   

						$json = json_decode($output, true);
						$lat = $json['results'][0]['geometry']['location']['lat'];
						$lng = $json['results'][0]['geometry']['location']['lng'];

						$imageUrl = 'https://maps.googleapis.com/maps/api/streetview?key=' . $apiKey . '&size=800x800&location=' . $lat . ',' . $lng . '&fov=90&heading=235&pitch=10';

						add_post_meta($postId, '_projectStreetViewThumbnail', $imageUrl);

						curl_close($ch);
					}

					?>
						<img class="center" src="<?php echo get_template_directory_uri() ?>/img/ball.gif" />
	                    <script>
	                        $('.title').remove();
	                    </script>
	                    <h2 class="normalize-text center">Uw project wordt aangemaakt</h2>
					<?php
					
					echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . esc_url(get_permalink($postId)) . '">'; 
					return;			
				}

				else
				{
					?>
						<p class="error-message">Helaas, er bestaat reeds een project met deze titel.</p>
					<?php
				}
	        }
	        
	        else
	        {
	            ?>
					<p class="error-message">Gelieve alle gegevens correct in te voeren.</p>
				<?php
	        }
		}
	}
?>