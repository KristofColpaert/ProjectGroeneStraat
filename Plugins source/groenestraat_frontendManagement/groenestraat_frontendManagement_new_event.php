<?php
	
	include_once('helper.php');

	/*
		Shortcode plugin
	*/	

	add_shortcode('new_event', 'prowp_new_event');

	function prowp_new_event()
	{
		save_new_event_form();
		show_new_event_form();
	}

	function show_new_event_form()
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
		<?php

		if(is_user_logged_in() && current_user_can('publish_posts') && !isset($_POST['eventPublish']))
		{
			?>
				
				<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
					
					<input class="textbox" id="eventTitle" name="eventTitle" type="text" placeholder="Titel" />

					<label for="eventDescription" class="normalize-text">Beschrijving</label><br \>
					<?php
						$settings = array('textarea_name' => 'eventDescription', 'media_buttons' => false);
						$content = ''; 
						$editor_id = 'eventDescription';

						wp_editor($content, $editor_id, $settings);
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

					<section class="date textbox left">
                        <input id="eventTime" readonly name="eventTime" type="text" class="normalize-text" placeholder="Datum start"/>
                    </section>
					<section class="date textbox right">
                    	<input class="normalize-text" id="eventEndTime" readonly name="eventEndTime" type="text" placeholder="Datum einde" />             
                    </section>

					<section class="date textbox left">
                        <input id="eventStartHour" name="eventStartHour" type="text" class="normalize-text" placeholder="Aanvangstijd (HH:MM)"/>
                    </section>

                    <section class="date textbox right">
                    	<input id="eventEndHour" name="eventEndHour" type="text" placeholder="Eindtijd (HH:MM)" />
                    </section>
					
					<input class="textbox normalize-text" id="eventLocation" name="eventLocation" type="text" placeholder="Locatie" />

					<label for="eventFeaturedImage" class="normalize-text">Afbeelding</label>
                  	<input id="eventFeaturedImage" name="eventFeaturedImage" type="file" accept="image/x-png, image/gif, image/jpeg" />

					<input id="eventPublish" name="eventPublish" class="form-button"  type="submit" value="Publiceer" />
				</form>
         		<script>
         			var nietLeeg = "Dit veld is verplicht!";

					var title = new LiveValidation('eventTitle', {validMessage:" "});
					title.add(Validate.Presence,{failureMessage:nietLeeg});

					var eventTime = new LiveValidation('eventTime', {validMessage:" "});
					eventTime.add(Validate.Presence,{failureMessage:nietLeeg});

					var eventEndTime = new LiveValidation('eventEndTime', {validMessage:" "});
					eventEndTime.add(Validate.Presence,{failureMessage:nietLeeg});

					var loc = new LiveValidation('eventLocation', {validMessage:" "});
					loc.add(Validate.Presence,{failureMessage:nietLeeg});

					var eventStartHour = new LiveValidation('eventStartHour', {validMessage:" "});
					eventStartHour.add(Validate.Presence,{failureMessage:nietLeeg});
					eventStartHour.add(Validate.Custom, {against: function checkTime(value){
				      	re = /([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/;
				     	if(!re.test(value)) {
				     		return false;
				      	}
				      	else return true;
				   	}, failureMessage:"Een tijdstip moet de structuur (HH:MM) hebben!"});

					var eventEndHour = new LiveValidation('eventEndHour', {validMessage:" "});
					eventEndHour.add(Validate.Presence,{failureMessage:nietLeeg});
					eventEndHour.add(Validate.Custom, {against: function checkTime(value){
				      	re = /([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/;
				      	if(!re.test(value)) {
				        	return false;
				      	}
				       	else return true;
				   	}, failureMessage:"Een tijdstip moet de structuur (HH:MM) hebben!"});

					var featuredImage = new LiveValidation('eventFeaturedImage', {validMessage:" "});
					featuredImage.add(Validate.Presence,{failureMessage:nietLeeg});
         		</script>
			<?php
		}

		else if(isset($_POST['eventPublish']))
		{}

		else 
		{
			?>
				<p class="error-message">U hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_new_event_form()
	{
		if(isset($_POST['eventPublish']))
		{
			if(!empty($_POST['eventTitle']) && 
				!empty($_POST['eventDescription']) &&
				!empty($_POST['eventTime']) &&
				$_FILES['eventFeaturedImage']['size'] > 0 &&
				!empty($_POST['eventLocation']) &&
				!empty($_POST['eventEndTime']) &&
				!empty($_POST['eventStartHour']) &&
				!empty($_POST['eventEndHour'])
				)
			{
				$eventTitle = $_POST['eventTitle'];
				$eventDescription = $_POST['eventDescription'];
				$parentProjectId = $_POST['parentProjectId'];
				$eventTime = $_POST['eventTime'];
				$eventEndTime = $_POST['eventEndTime'];
				$eventLocation = $_POST['eventLocation'];
				$eventStartHour = $_POST['eventStartHour'];
				$eventEndHour = $_POST['eventEndHour'];

				$date1 = strtotime($eventTime);
				$date2 = strtotime($eventEndTime);

				if($date1 > $date2)
				{
					?>
						<p class="error-message">Gelieve een startdatum in te voeren die vroeger valt dan de einddatum. Ga <a href="<?php echo home_url() . '/nieuw-event'; ?>">terug</a>.</p>
					<?php
					return;
				}

				if($date1 == $date2)
				{
					$start = explode(":", $eventStartHour);
					$end = explode(":", $eventEndHour);

					if($start[0] > $end[0])
					{
						?>
							<p class="error-message">Gelieve een aanvangstijd in te voeren die vroeger valt dan de eindtijd. Ga <a href="<?php echo home_url() . '/nieuw-event'; ?>">terug</a>.</p>
						<?php
						return;
					}

					if($start[0] == $end[0] && $start[1] > $end[1])
					{
						?>
							<p class="error-message">Gelieve een aanvangstijd in te voeren die vroeger valt dan de eindtijd. Ga <a href="<?php echo home_url() . '/nieuw-event'; ?>">terug</a>.</p>
						<?php
						return;
					}
				}

				if(null == get_page_by_title($eventTitle))
				{
					$slug = str_replace(" ", "-", $eventTitle);
					$current_user = wp_get_current_user();

					$args = array(
						'comment_status' => 'closed',
						'ping_status' => 'closed',
						'post_author' => $current_user->ID,
						'post_name' => $slug,
						'post_title' => $eventTitle,
						'post_content' => $eventDescription,
						'post_status' => 'publish',
						'post_type' => 'events',
					);

					$postId = wp_insert_post($args, false);

					if($parentProjectId != 0)
					{
						add_post_meta($postId, '_parentProjectId', $parentProjectId);
						$tempCategory = get_category_by_slug('projectevents');
						wp_set_post_categories($postId, array($tempCategory->term_id), true);
					}
					add_post_meta($postId, '_eventTime', $eventTime);
					add_post_meta($postId, '_eventEndTime', $eventEndTime);
					add_post_meta($postId, '_eventLocation', $eventLocation);
					add_post_meta($postId, '_eventStartHour', $eventStartHour);
					add_post_meta($postId, '_eventEndHour', $eventEndHour);

                   	if($_FILES['eventFeaturedImage']['size'] > 0)
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
	                    <h2 class="normalize-text center">Uw event wordt aangemaakt</h2>
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