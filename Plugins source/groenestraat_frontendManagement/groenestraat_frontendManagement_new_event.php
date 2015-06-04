<?php
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

		if(current_user_can('publish_posts'))
		{
			?>
				
				<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
					
					<input class="textbox" id="eventTitle" name="eventTitle" type="text" placeholder="Titel" />

					<label for="eventDescription" class="normalize-text">Beschrijving</label><br \>
					<?php
						$settings = array('textarea_name' => 'eventDescription');
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
				

					<section class="date textbox left">
                        <input id="eventTime" readonly name="eventTime" type="text" class="normalize-text" placeholder="Datum start"/>
                    </section>
					<section class="date textbox right">
                    <input class="normalize-text" id="eventEndTime" readonly name="eventEndTime" type="text" placeholder="Datum einde" />             
                    </section>
					
					
					<input class="textbox normalize-text" id="eventLocation" name="eventLocation" type="text" placeholder="Locatie" />

					<input id="eventPublish" name="eventPublish" class="form-button"  type="submit" value="Publiceer" />
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

	function save_new_event_form()
	{
		if(isset($_POST['eventPublish']))
		{
			if(!empty($_POST['eventTitle']) && 
				!empty($_POST['eventDescription']) &&
				!empty($_POST['parentProjectId']) &&
				!empty($_POST['eventTime']) &&
				!empty($_POST['eventLocation']) &&
				!empty($_POST['eventEndTime'])
				)
			{
				$eventTitle = $_POST['eventTitle'];
				$eventDescription = $_POST['eventDescription'];
				$parentProjectId = $_POST['parentProjectId'];
				$eventTime = $_POST['eventTime'];
				$eventEndTime = $_POST['eventEndTime'];
				$eventLocation = $_POST['eventLocation'];

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
						'post_type' => 'events'
					);

					$postId = wp_insert_post($args, false);

					if($parentProjectId != 0)
					{
						add_post_meta($postId, '_parentProjectId', $parentProjectId);
					}
					add_post_meta($postId, '_eventTime', $eventTime);
					add_post_meta($postId, '_eventEndTime', $eventEndTime);
					add_post_meta($postId, '_eventLocation', $eventLocation);
                    header('Location: '.get_permalink($postId));
					?>
						
					<?php
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