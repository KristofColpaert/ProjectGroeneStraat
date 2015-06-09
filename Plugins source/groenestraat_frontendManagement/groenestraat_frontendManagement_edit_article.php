<?php
	/*
		Shortcode plugin
	*/

	add_shortcode('edit_article', 'prowp_edit_article');

	/*
		Plugin methods
	*/

	function prowp_edit_article()
	{
		save_edit_article_form();
		show_edit_article_form();
	}

	function show_edit_article_form()
	{
		if(is_user_logged_in() && isset($_GET['artikel']) && !isset($_POST['articleEdit']))
		{
			$current_user = wp_get_current_user();
			$article = get_post($_GET['artikel'], OBJECT);

			if($article->post_type != 'post' || get_post_status($article->ID) != 'publish')
			{
				?>
					<p class="error-message">Dit artikel bestaat (nog) niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
				<?php
				return;
			}

			if($article != null && ($current_user->ID == $article->post_author || current_user_can('manage_options')) && current_user_can('edit_published_posts'))
			{
				?>
					<form class="createForm" action="<?php $_SERVER['REQUEST_URI'] ?>" method="POST" enctype="multipart/form-data">
						<input class="textbox" id="articleTitle" id="articleTitle" name="articleTitle" type="text" placeholder="Titel" value="<?php echo $article->post_title; ?>" />

						<label for="articleDescription" class="normalize-text">Beschrijving</label><br \>
						<?php
							$settings = array('textarea_name' => 'articleDescription');
							$content = $article->post_content; 
							$editor_id = 'articleDescription';

							wp_editor($content, $editor_id, $settings);

							$parentProject = get_post_meta($article->ID, '_parentProjectId');
							$parentProjectId;
							if(count($parentProject) > 0)
							{
								$parentProjectId = $parentProject[0];
							}

							$tags = wp_get_post_tags($article->ID);
							$taglist = '';
							if(!empty($tags))
							{
								$size = count($tags);
								$count = 0;
								foreach($tags as $tag)
								{
									if($count == $size - 1)
									{
										$taglist = $taglist . $tag->name;
									}

									else
									{
										$taglist = $taglist . $tag->name . ',';
										$count++;
									}
								}

							}
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
										printf('<option value="%s"%s>%s</option>', esc_attr($parent->ID), selected($parent->ID, $parentProjectId, false), esc_html($parent->post_title));
									}
								}
							?>
						</select>
						<br />

						<input class="textbox" id="articleTags" name="articleTags" type="text" placeholder="Tags (gescheiden door komma's)" value="<?php echo $taglist; ?>" />

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
								$articleCategories = wp_get_post_categories($article->ID);

								if(!empty($categories))
								{
									foreach($categories as $category)
									{
										if($category->name != 'Projectartikels' && $category->name != 'Projectzoekertjes' && $category->name != 'Projectevents')
										{
											if(in_array($category->term_id, $articleCategories))
											{
												?>
													<option selected value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
												<?php
											}

											else
											{
												?>
													<option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
												<?php
											}
											
										}
									}
								}
							?>
						</select>
						
						<input id="articleId" name="articleId" type="hidden" value="<?php echo $article->ID; ?>" />

						<input id="articleEdit" name="articleEdit" type="submit" value="Bewerk" class="form-button" />
					</form>
				<?php
			}

			else
			{
				?>
					<p class="error-message">Dit artikel bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
				<?php
			}
		}

		else if(isset($_POST['articleEdit']))
		{}

		else
		{
			?>
				<p class="error-message">Dit artikel bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_edit_article_form()
	{
		if(isset($_POST['articleEdit']))
		{
			if(!empty($_POST['articleTitle']) &&
				!empty($_POST['articleDescription'])
				)
			{
				$articleId = $_POST['articleId'];
				$articleTitle = $_POST['articleTitle'];
				$articleDescription = $_POST['articleDescription'];
				$articleTags = $_POST['articleTags'];
				$articleCategories = $_POST['articleCategories'];
				$parentProjectId = $_POST['parentProjectId'];

				if(null == get_page_by_title($articleTitle))
				{
					$slug = str_replace(" ", "-", $articleTitle);
					$current_user = wp_get_current_user();

					$args = array(
						'ID' => $articleId,
						'post_name' => $slug,
						'post_title' => $articleTitle,
						'post_content' => $articleDescription
					);

					$postId = wp_update_post($args);
					$tags = str_replace(" ,", ",", $articleTags);
					$tags = str_replace(", ", ",", $tags);
					wp_set_post_tags($postId, $tags, false);
					wp_set_post_categories($postId, $articleCategories, false);

					update_post_meta($postId, '_parentProjectId', $parentProjectId);
					if($parentProjectId != 0)
					{
						$tempCategory = get_category_by_slug('projectartikels');
						wp_set_post_categories($postId, array($tempCategory->term_id), true);
					}

					?>
						<img class="center" src="<?php echo get_template_directory_uri() ?>/img/ball.gif" />
		                <script>
		                    $('.title').remove();
		                </script>
		                <h2 class="normalize-text center">Uw artikel wordt bewerkt</h2>
					<?php

					echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . esc_url(get_permalink($postId)) . '">'; 
					return;
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