<?php
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
		if(current_user_can('publish_posts'))
		{
			?>
				<form class="createForm" action="<?php $_SERVER['REQUEST_URI'] ?>" method="POST" enctype="multipart/form-data">
					<label for="adTitle">Titel van het zoekertje</label>
					<input id="adTitle" name="adTitle" type="text" />

					<label for="adDescription">Beschrijving van het zoekertje</label>
					<?php
						$settings = array('textarea_name' => 'adDescription');
						$content = ''; 
						$editor_id = 'adDescription';

						wp_editor($content, $editor_id, $settings);
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
									?>
										<option value="<?php echo $parent->ID; ?>"><?php echo $parent->post_title; ?></option>
									<?php
								}
							}
						?>
					</select>
					<br />

					<label for="adPrice">Prijs van het zoekertje</label>
					<input id="adPrice" name="adPrice" type="text" />

					<label for="adLocation">Locatie van het zoekertje</label>
					<input id="adLocation" name="adLocation" type="text" />

					<input id="adPublish" name="adPublish" type="submit" value="Publiceer" />
				</form>
			<?php
		}

		else
		{
			?>
				<p>U hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_new_zoekertje_form()
	{
		if(isset($_POST['adPublish']))
		{
			if(!empty($_POST['adTitle']) &&
				!empty($_POST['adDescription']) &&
				!empty($_POST['parentProjectId']) &&
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
						'post_type' => 'zoekertjes'
					);

					$postId = wp_insert_post($args, false);

					if($parentProjectId != 0)
					{
						add_post_meta($postId, '_parentProjectId', $parentProjectId);
					}
					add_post_meta($postId, '_adPrice', $adPrice);
					add_post_meta($postId, '_adLocation', $adLocation);

					?>
						<p>Uw zoekertje werd correct toegevoegd. Ga er <a href="<?php echo esc_url(get_permalink($postId)); ?>">meteen</a> naartoe.</p>
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