<?php

	include_once('helper.php');

	/*
		Shortcode plugin
	*/

	add_shortcode('edit_zoekertje', 'prowp_edit_zoekertje');

	function prowp_edit_zoekertje()
	{
		save_edit_zoekertje_form();
		show_edit_zoekertje_form();
	}

	function show_edit_zoekertje_form()
	{
		if(is_user_logged_in() && isset($_GET['zoekertje']) && !isset($_POST['zoekertjeEdit']))
		{
			$current_user = wp_get_current_user();
			$zoekertje = get_post($_GET['zoekertje'], OBJECT);

			if($zoekertje->post_type != 'zoekertjes')
			{
				?>
					<p class="error-message">Dit zoekertje bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
				<?php
				return;
			}

			if($zoekertje != null && ($current_user->ID == $zoekertje->post_author || current_user_can('manage_options')) && current_user_can('edit_published_posts'))
			{
				?>
					<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
						<label for="adTitle">Titel</label>
						<input class="textbox" id="adTitle" name="adTitle" type="text" value="<?php echo $zoekertje->post_title; ?>" />
						
						<label class="normalize-text" for="adDescription">Beschrijving</label><br \>
						<?php
							$settings = array('textarea_name' => 'adDescription', 'media_buttons' => false);
							$content = $zoekertje->post_content; 
							$editor_id = 'adDescription';

							wp_editor($content, $editor_id, $settings);

							$parentProjectId = get_post_meta($zoekertje->ID, '_parentProjectId')[0];
							$adPrice = get_post_meta($zoekertje->ID, '_adPrice')[0];
							$adLocation = get_post_meta($zoekertje->ID, '_adLocation')[0];
						?>

						<label class="normalize-text" for="parentProjectId">Project waartoe het zoekertje behoort</label>
						<br />
						<select class="input combobox" id="parentProjectId" name="parentProjectId">
							<option value="0" >Geen project</option>
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
										printf('<option value="%s"%s>%s</option>', esc_attr($parent->ID), selected($parent->ID, $parentProjectId, false), esc_html($parent->post_title));	
									}
								}
							?>
						</select>
						<br />

						<label for="adPrice" class="normalize-text">Prijs</label>
						
						<input class="textbox" id="adPrice" name="adPrice" type="text" value="<?php echo $adPrice; ?>" />
						

						<label for="adLocation" class="normalize-text">Locatie</label>
						
						<input class="textbox" id="adLocation" name="adLocation" type="text" value="<?php echo $adLocation; ?>" />
						
						<label for="adFeaturedImage" class="normalize-text">Afbeelding</label>
                  		<input id="adFeaturedImage" name="adFeaturedImage" type="file" accept="image/x-png, image/gif, image/jpeg" />
						
						<input id="zoekertjeId" name="zoekertjeId" type="hidden" value="<?php echo $zoekertje->ID; ?>" />

						<input id="zoekertjeEdit" class="form-button" name="zoekertjeEdit" type="submit" value="Bewerk" />
					</form>
					<script>
						var nietLeeg = "Dit veld is verplicht!";

						var title = new LiveValidation('adTitle', {validMessage:" "});
						title.add(Validate.Presence,{failureMessage:nietLeeg});

						var price = new LiveValidation('adPrice', {validMessage:" "});
						price.add(Validate.Presence,{failureMessage:nietLeeg});
						price.add(Validate.Numericality,{onlyInteger:true, notANumberMessage: "Een prijs moet een getal in euro zijn!"});

						var loc = new LiveValidation('adLocation', {validMessage:" "});
						loc.add(Validate.Presence,{failureMessage:nietLeeg});
					</script>
				<?php
			}

			else
			{
				?>
					<p class="error-message">Dit zoekertje bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
				<?php
			}
		}

		else if(isset($_POST['zoekertjeEdit']))
		{}

		else
		{
			?>
				<p class="error-message">Dit event bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_edit_zoekertje_form()
	{
		if(isset($_POST['zoekertjeEdit']))
		{
			if(!empty($_POST['adTitle']) && 
				!empty($_POST['adDescription']) &&
				!empty($_POST['adPrice']) &&
				!empty($_POST['adLocation']) &&
				!empty($_POST['zoekertjeId'])
				)
			{
				$zoekertjeId = $_POST['zoekertjeId'];
				$adTitle = $_POST['adTitle'];
				$adDescription = $_POST['adDescription'];
				$adPrice = $_POST['adPrice'];
				$adLocation = $_POST['adLocation'];
				$parentProjectId = $_POST['parentProjectId'];

				if(null == get_page_by_title($adTitle))
				{
					$slug = str_replace(" ", "-", $adTitle);
					$current_user = wp_get_current_user();

					$args = array(
						'ID' => $zoekertjeId,
						'post_name' => $slug,
						'post_title' => $adTitle,
						'post_content' => $adDescription
					);

					$postId = wp_update_post($args);

					update_post_meta($postId, '_adTitle', $adTitle);
					update_post_meta($postId, '_adPrice', $adPrice);
					update_post_meta($postId, '_adLocation', $adLocation);
					update_post_meta($postId, '_parentProjectId', $parentProjectId);

					if($parentProjectId != 0)
					{
						$tempCategory = get_category_by_slug('projectzoekertjes');
						wp_set_post_categories($postId, array($tempCategory->term_id), true);
					}

					else
					{
						wp_set_post_categories($postId, null, false);
					}


					if($_FILES['adFeaturedImage']['size'] != 0)
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
	                    <h2 class="normalize-text center">Uw zoekertje wordt bewerkt</h2>
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