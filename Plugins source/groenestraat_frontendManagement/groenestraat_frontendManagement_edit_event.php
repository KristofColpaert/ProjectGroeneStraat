<?php
	
	include_once('helper.php');

	/*
		Shortcode plugin
	*/

	add_shortcode('edit_event', 'prowp_edit_event');

	function prowp_edit_event()
	{
		save_edit_event_form();
		show_edit_event_form();
	}

	function show_edit_event_form()
	{
		if(is_user_logged_in() && isset($_GET['event']) && !isset($_POST['eventEdit']))
		{
			$current_user = wp_get_current_user();
			$event = get_post($_GET['event'], OBJECT);

			if($event->post_type != 'events')
			{
				?>
					<p class="error-message">Dit event bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
				<?php
				return;
			}

			if($event != null && ($current_user->ID == $event->post_author || current_user_can('manage_options')) && current_user_can('edit_published_posts'))
			{
                ?>
                    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
			<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

			<script>
			  	$(document).ready(function() {
					$('#eventTime').datepicker({
                        showOn: "both",
                        buttonImage: "<?php echo get_template_directory_uri() ?>/img/calendar_small.png",
                        buttonImageOnly: true,
                        nextText: ">"
                    });
					$('#eventEndTime').datepicker({
                        showOn: "both",
                        buttonImage: "<?php echo get_template_directory_uri() ?>/img/calendar_small.png",
                        buttonImageOnly: true
                    });
                   
                    
				});
			</script>
					<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
						<label for="eventTitle" class="normalize-text">Titel</label>
						<input class="textbox" id="eventTitle" name="eventTitle" type="text" value="<?php echo $event->post_title; ?>" />
						
						<label for="eventDescription" class="normalize-text">Beschrijving</label><br \>
						<?php
							$settings = array('textarea_name' => 'eventDescription');
							$content = $event->post_content; 
							$editor_id = 'eventDescription';

							wp_editor($content, $editor_id, $settings);

							$parentProjectId = get_post_meta($event->ID, '_parentProjectId')[0];
							$eventTime = get_post_meta($event->ID, '_eventTime')[0];
							$eventEndTime = get_post_meta($event->ID, '_eventEndTime')[0];
							$eventLocation = get_post_meta($event->ID, '_eventLocation')[0];

							echo $eventTime;
						?>

						<label for="parentProjectId" class="normalize-text">Project waartoe het event behoort</label>
						<br />
						<select class="input combobox" id="parentProjectId" name="parentProjectId">
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
											printf('<option value="%s"%s>%s</option>', esc_attr($parent->ID), selected($parent->ID, $parentProjectId, false), esc_html($parent->post_title));	
									}
								}
							?>
						</select>
						<br />
                        <label  for="eventTime" class="normalize-text left labeldate">Van</label><label  for="eventEndTime" class="normalize-text right labeldate">Tot</label>
                        <section class="date textbox left">

                            <input id="eventTime" name="eventTime" readonly class="normalize-text" type="text" value="<?php echo $eventTime; ?>" />                   </section>

                        <section class="date textbox right">
						
						
                            <input id="eventEndTime" name="eventEndTime" readonly type="text" class="normalize-text" value="<?php echo $eventEndTime; ?>" /></section>
						<br />

						<label for="eventLocation" class="normalize-text">Locatie</label>
						<input class="textbox" id="eventLocation" name="eventLocation" type="text" value="<?php echo $eventLocation; ?>" />
						
						<label for="eventFeaturedImage" class="normalize-text">Afbeelding</label>
                  		<input id="eventFeaturedImage" name="eventFeaturedImage" type="file" accept="image/x-png, image/gif, image/jpeg" />

						<input id="eventId" name="eventId" type="hidden" value="<?php echo $event->ID; ?>" />

						<input class="form-button" id="eventEdit" name="eventEdit" type="submit" value="Bewerk" />
					</form>
				<?php
			}

			else
			{
				?>
					<p class="error-message">Dit event bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
				<?php
			}
		}

		else if(isset($_POST['eventEdit']))
		{}

		else
		{
			?>
				<p class="error-message">Dit event bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_edit_event_form()
	{
		if(isset($_POST['eventEdit']))
		{
			if(!empty($_POST['eventTitle']) && 
				!empty($_POST['eventDescription']) &&
				!empty($_POST['eventTime']) &&
				!empty($_POST['eventLocation']) &&
				!empty($_POST['eventEndTime']) &&
				!empty($_POST['eventId'])
				)
			{
				$eventId = $_POST['eventId'];
				$eventTitle = $_POST['eventTitle'];
				$eventDescription = $_POST['eventDescription'];
				$eventTime = $_POST['eventTime'];
				$eventEndTime = $_POST['eventEndTime'];
				$eventLocation = $_POST['eventLocation'];
				$parentProjectId = $_POST['parentProjectId'];

				if(null == get_page_by_title($eventTitle))
				{
					$slug = str_replace(" ", "-", $eventTitle);
					$current_user = wp_get_current_user();

					$args = array(
						'ID' => $eventId,
						'post_name' => $slug,
						'post_title' => $eventTitle,
						'post_content' => $eventDescription
					);

					$postId = wp_update_post($args);

					update_post_meta($postId, '_eventTime', $eventTime);
					update_post_meta($postId, '_eventEndTime', $eventEndTime);
					update_post_meta($postId, '_eventLocation', $eventLocation);
					update_post_meta($postId, '_parentProjectId', $parentProjectId);

					if($parentProjectId != 0)
					{
						$tempCategory = get_category_by_slug('projectevents');
						wp_set_post_categories($postId, array($tempCategory->term_id), true);
					}
                    
                    if($_FILES['eventFeaturedImage']['size'] != 0)
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
						<p class="error-message">Helaas, er bestaat reeds een event met deze titel.</p>
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