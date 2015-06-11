<?php

	include_once('helper.php');

	/*
		Shortcode plugin
	*/

	add_shortcode('new_zoekertje', 'prowp_new_zoekertje');

	function prowp_new_zoekertje()
	{
		save_new_zoekertje_form();
		show_new_zoekertje_form();
	}

	function show_new_zoekertje_form()
	{
		if(is_user_logged_in() && current_user_can('publish_zoekertjes') && !isset($_POST['adPublish']))
		{
			if(isset($_GET['project']))
			{
				$project = get_post($_GET['project']);
				$current_user = wp_get_current_user();
				$meta = get_post_meta($project->ID, '_subscriberId');

				if(!in_array($current_user->ID, $meta))
				{
					?>
						<p class="error-message">U hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
					<?php
					return;
				}
			}
			?>
				<form class="createForm" action="<?php $_SERVER['REQUEST_URI'] ?>" method="POST" enctype="multipart/form-data">
					
					<input class="textbox" id="adTitle" name="adTitle" type="text" placeholder="Titel" />

					<label for="adDescription" class="normalize-text">Beschrijving</label><br \>
					<?php
						$settings = array('textarea_name' => 'adDescription', 'media_buttons' => false);
						$content = ''; 
						$editor_id = 'adDescription';

						wp_editor($content, $editor_id, $settings);
					?>

					<label for="parentProjectId" class="normalize-text">Project waartoe het zoekertje behoort</label>
					<br />
					<select class="textbox combobox" id="parentProjectId" name="parentProjectId">
						<option value="0">Geen project</option>
						<?php
							$parents = get_posts(
								array(
									'post_type' => 'projecten',
									'orderby' => 'title',
									'order' => 'ASC',
									'numberposts' => -1,
									'meta_key' => '_subscriberId',
									'meta_value' => $current_user->ID,
									'meta_operator' => '='
								)
							);

							if(!empty($parents))
							{
								foreach($parents as $parent)
								{
									if(isset($_GET['project']))
									{
										$tempProject = get_post($_GET['project'], OBJECT);
										if($tempProject != null && $tempProject->ID == $parent->ID)
										{
											?>
												<option selected value="<?php echo $parent->ID; ?>"><?php echo $parent->post_title; ?></option>
											<?php
										}
									}

									else
									{
										?>
											<option value="<?php echo $parent->ID; ?>"><?php echo $parent->post_title; ?></option>
										<?php
									}
								}
							}
						?>
					</select>
					<br />

					<input class="textbox" id="adPrice" name="adPrice" type="text" placeholder="Prijs" />
					<input class="textbox" id="adLocation" name="adLocation" type="text" placeholder="Locatie" />

					<label for="adFeaturedImage" class="normalize-text">Afbeelding</label>
                    <div style="height:0px;overflow:hidden">
                        <input id="adFeaturedImage" class="image-upload" name="adFeaturedImage" type="file" accept="image/x-png, image/gif, image/jpeg" />
                    </div>
                  	<button type="button" class="confirm-button" id="upload" onclick="chooseFile();">Kies afbeelding</button>

					<input id="adPublish" name="adPublish" type="submit" value="Publiceer" class="form-button" />
				</form>
				<script>
                    $(document).ready(function () {
                        $("#adFeaturedImage").on("change", function () {
                            $("#upload").toggleClass("confirm-button");
                             $("#upload").toggleClass("confirm-button-green");
                        });
                    });
                   
					var nietLeeg = "Dit veld is verplicht!";

					var title = new LiveValidation('adTitle', {validMessage:" "});
					title.add(Validate.Presence,{failureMessage:nietLeeg});

					var price = new LiveValidation('adPrice', {validMessage:" "});
					price.add(Validate.Presence,{failureMessage:nietLeeg});
					price.add(Validate.Numericality,{onlyInteger:true, notANumberMessage: "Een prijs moet een getal in euro zijn!"});

					var loc = new LiveValidation('adLocation', {validMessage:" "});
					loc.add(Validate.Presence,{failureMessage:nietLeeg});

					var featuredImage = new LiveValidation('adFeaturedImage', {validMessage:" "});
					featuredImage.add(Validate.Presence,{failureMessage:nietLeeg});
				</script>
			<?php
		}

		else if(isset($_POST['adPublish']))
		{}

		else
		{
			?>
				<p class="error-message">U hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_new_zoekertje_form()
	{
		if(isset($_POST['adPublish']))
		{
			if(!empty($_POST['adTitle']) &&
				!empty($_POST['adDescription']) &&
				$_FILES['adFeaturedImage']['size'] > 0 &&
				!empty($_POST['adPrice']) &&
				!empty($_POST['adLocation'])
				)
			{
				$adTitle = $_POST['adTitle'];
				$adDescription = $_POST['adDescription'];
				$parentProjectId = $_POST['parentProjectId'];
				$adPrice = $_POST['adPrice'];
				$adLocation = $_POST['adLocation'];

				if(null == get_page_by_title($adTitle))
				{
					$slug = str_replace(" ", "-", $adTitle);
					$current_user = wp_get_current_user();

					$args = array(
						'comment_status' => 'closed',
						'ping_status' => 'closed',
						'post_author' => $current_user->ID,
						'post_name' => $slug,
						'post_title' => $adTitle,
						'post_content' => $adDescription,
						'post_status' => 'publish',
						'post_type' => 'zoekertjes',
					);

					$postId = wp_insert_post($args, false);

					if($parentProjectId != 0)
					{
						add_post_meta($postId, '_parentProjectId', $parentProjectId);
						$tempCategory = get_category_by_slug('projectzoekertjes');
						wp_set_post_categories($postId, array($tempCategory->term_id), true);
					}
					add_post_meta($postId, '_adPrice', $adPrice);
					add_post_meta($postId, '_adLocation', $adLocation);

					if($_FILES['adFeaturedImage']['size'] > 0)
					{
						foreach ($_FILES as $file => $array) 
						{
	    					$newupload = insert_featured_image($file, $postId);
					    }
					}

					?>
						<img class="center" src="<?php echo get_template_directory_uri() ?>/img/ball.gif" />
	                    <script>
	                        $('.title').remove();
	                    </script>
	                    <h2 class="normalize-text center">Uw zoekertje wordt aangemaakt</h2>
					<?php

					echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . esc_url(get_permalink($postId)) . '">'; 
					return;
				}

				else
				{
					?>
						<p class="error-message">Helaas, er bestaat reeds een zoekertje met deze titel.</p>
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
