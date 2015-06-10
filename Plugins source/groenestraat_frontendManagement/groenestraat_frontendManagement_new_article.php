<?php 

	include_once('helper.php');

	/*
		Shortcode plugin
	*/

	add_shortcode('new_article', 'prowp_new_article');

	function prowp_new_article()
	{
		save_new_article_form();
		show_new_article_form();
	}

	/*
		Plugin methods
	*/

	function show_new_article_form()
	{
		if(is_user_logged_in() && current_user_can('edit_posts') && !isset($_POST['articlePublish']))
		{
			?>
				<form class="createForm" action="<?php $_SERVER['REQUEST_URI'] ?>" method="POST" enctype="multipart/form-data">
					<input class="textbox" id="articleTitle" id="articleTitle" name="articleTitle" type="text" placeholder="Titel" />

					<label for="articleDescription" class="normalize-text">Beschrijving</label><br \>
					<?php
						$settings = array('textarea_name' => 'articleDescription');
						$content = ''; 
						$editor_id = 'articleDescription';

						wp_editor($content, $editor_id, $settings);
					?>

					<label for="parentProjectId" class="normalize-text">Project waartoe het artikel behoort</label>
					<br />
					<select class="textbox combobox" id="parentProjectId" name="parentProjectId">
						<option value="0">Geen project</option>
						<?php
							$current_user = wp_get_current_user();
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

					<input class="textbox" id="articleTags" name="articleTags" type="text" placeholder="Tags (gescheiden door komma's)" />

					<label for="articleCategories" class="normalize-text">Categorieën waartoe het artikel behoort</label>
					<br />
					<select class="textbox combobox" id="articleCategories" name="articleCategories[]" multiple size="5">
						<?php
							$categories = get_categories(
								array(
									'type' => 'post',
									'orderby' => 'name',
									'order' => 'ASC',
									'hide_empty' => 0
								)
							);

							if(!empty($categories))
							{
								foreach($categories as $category)
								{
									if($category->name != 'Projectartikels' && $category->name != 'Projectzoekertjes' && $category->name != 'Projectevents')
									{
										?>
											<option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
										<?php
									}
								}
							}
						?>
					</select>

					<label for="articleFeaturedImage" class="normalize-text">Afbeelding</label>
                  	<input id="articleFeaturedImage" name="articleFeaturedImage" type="file" accept="image/x-png, image/gif, image/jpeg" />
					
					<input id="articlePublish" name="articlePublish" type="submit" value="Publiceer" class="form-button" />
				</form>
				<script>
					var nietLeeg = "Dit veld is verplicht!";

					var title = new LiveValidation('articleTitle', {validMessage:" "});
					title.add(Validate.Presence,{failureMessage:nietLeeg});

					var featuredImage = new LiveValidation('articleFeaturedImage', {validMessage:" "});
					featuredImage.add(Validate.Presence,{failureMessage:nietLeeg});
				</script>
			<?php
		}

		else if(isset($_POST['articlePublish']))
		{}

		else
		{
			?>
				<p class="error-message">U hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_new_article_form()
	{
		if(isset($_POST['articlePublish']))
		{
			if(!empty($_POST['articleTitle']) &&
				!empty($_POST['articleDescription']) &&
				$_FILES['articleFeaturedImage']['size'] > 0
				)
			{
				$articleTitle = $_POST['articleTitle'];
				$articleDescription = $_POST['articleDescription'];
				$parentProjectId = $_POST['parentProjectId'];
				$articleTags = $_POST['articleTags'];
				$articleCategories = $_POST['articleCategories'];

				if(null == get_page_by_title($articleTitle))
				{
					$slug = str_replace(" ", "-", $articleTitle);
					$tags = str_replace(" ,", ",", $articleTags);
					$tags = str_replace(", ", ",", $tags);
					$current_user = wp_get_current_user();

					if(current_user_can('publish_posts'))
					{
						$args = array(
							'comment_status' => 'open',
							'ping_status' => 'closed',
							'post_author' => $current_user->ID,
							'post_name' => $slug,
							'post_title' => $articleTitle,
							'post_content' => $articleDescription,
							'post_status' => 'publish',
							'post_type' => 'post'
						);
					}
					
					else
					{
						$args = array(
							'comment_status' => 'open',
							'ping_status' => 'closed',
							'post_author' => $current_user->ID,
							'post_name' => $slug,
							'post_title' => $articleTitle,
							'post_content' => $articleDescription,
							'post_status' => 'pending',
							'post_type' => 'post'
						);
					}

					$postId = wp_insert_post($args, false);

					if($tags != '')
					{
						wp_set_post_tags($postId, $tags, false);
					}
					
					if(count($articleCategories) != 0)
					{
						wp_set_post_categories($postId, $articleCategories, false);
					}

					if($parentProjectId != 0)
					{
						add_post_meta($postId, '_parentProjectId', $parentProjectId);
						$tempCategory = get_category_by_slug('projectartikels');
						wp_set_post_categories($postId, array($tempCategory->term_id), true);
					}

					if($_FILES['articleFeaturedImage']['size'] > 0)
					{
						foreach($_FILES as $file => $array)
						{
							$newupload = insert_featured_image($file, $postId);
						}
					}

					if(current_user_can('publish_posts'))
					{
						?>
							<img class="center" src="<?php echo get_template_directory_uri() ?>/img/ball.gif" />
		                    <script>
		                        $('.title').remove();
		                    </script>
		                    <h2 class="normalize-text center">Uw artikel wordt aangemaakt</h2>
						<?php

						echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . esc_url(get_permalink($postId)) . '">'; 
						return;
					}

					else
					{
						?>
							<img class="center" src="<?php echo get_template_directory_uri() ?>/img/ball.gif" />
		                    <script>
		                        $('.title').remove();
		                    </script>
		                    <h2 class="normalize-text center">Uw artikel wordt in de wachtrij geplaatst voor goedkeuring</h2>
						<?php

						echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . esc_url(home_url()) . '">'; 
						return;
					}
				}

				else
				{
					?>
						<p class="error-message">Helaas, er bestaat reeds een artikel met deze titel.</p>
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