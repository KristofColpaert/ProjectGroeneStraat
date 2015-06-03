<?php
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
		if(isset($_GET['zoekertje']))
		{
			$current_user = wp_get_current_user();
			$zoekertje = get_post($_GET['zoekertje'], OBJECT);

			if($zoekertje != null && ($current_user->ID == $zoekertje->post_author || current_user_can('manage_options')) && current_user_can('edit_published_posts'))
			{
				?>
					<form action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
						<label for="adTitle">Titel van het zoekertje</label>
						<input id="adTitle" name="adTitle" type="text" value="<?php echo $zoekertje->post_title; ?>" />
						
						<label for="adDescription">Beschrijving van het zoekertje</label>
						<?php
							$settings = array('textarea_name' => 'adDescription');
							$content = $zoekertje->post_content; 
							$editor_id = 'adDescription';

							wp_editor($content, $editor_id, $settings);

							$parentProjectId = get_post_meta($zoekertje->ID, '_parentProjectId')[0];
							$adPrice = get_post_meta($zoekertje->ID, '_adPrice')[0];
							$adLocation = get_post_meta($zoekertje->ID, '_adLocation')[0];

						?>

						<label for="parentProjectId">Project waartoe het zoekertje behoort</label>
						<br />
						<select id="parentProjectId" name="parentProjectId">
							<option value="0">Geen project</option>
							<?php
								$parents = get_posts(
									array(
										'post_type' => 'projecten',
										'orderby' => 'title',
										'order' => 'ASC',
										'numberposts' => -1
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

						<label for="adPrice">Prijs van het zoekertje</label>
						<br />
						<input id="adPrice" name="adPrice" type="text" value="<?php echo $adPrice; ?>" />
						<br />

						<label for="adLocation">Locatie van het zoekertje</label>
						<br />
						<input id="adLocation" name="adLocation" type="text" value="<?php echo $adLocation; ?>" />
						<br />
						
						<input id="zoekertjeId" name="zoekertjeId" type="hidden" value="<?php echo $zoekertje->ID; ?>" />

						<input id="zoekertjeEdit" name="zoekertjeEdit" type="submit" value="Bewerk" />
					</form>
				<?php
			}

			else
			{
				?>
					<p>Dit zoekertje bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
				<?php
			}
		}

		else
		{
			?>
				<p>Dit event bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_edit_zoekertje_form()
	{
		if(isset($_POST['zoekertjeEdit']))
		{
			if(!empty($_POST['adTitle']) && 
				!empty($_POST['adDescription']) &&
				!empty($_POST['parentProjectId']) &&
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

					update_post_meta($postId, '_adPrice', $adPrice);
					update_post_meta($postId, '_adLocation', $adLocation);
					update_post_meta($postId, '_parentProjectId', $parentProjectId);
					?>
						<p>Uw zoekertje werd correct gewijzigd. Ga er <a href="<?php echo esc_url(get_permalink($postId)); ?>">meteen</a> naartoe.</p>
					<?php
				}

				else
				{
					?>
						<p>Helaas, er bestaat reeds een zoekertje met deze titel.</p>
					<?php
				}
			}

			else
			{
				?>
					<p>Gelieve alle gegevens correct in te voeren.</p>
				<?php
			}
		}
	}
?>