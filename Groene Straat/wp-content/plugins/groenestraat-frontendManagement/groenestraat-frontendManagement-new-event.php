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

		if(is_user_logged_in() && current_user_can('publish_events') && !isset($_POST['eventPublish']))
		{
			if(isset($_GET['project']))
			{
				$project = get_post($_GET['project']);
				$current_user = wp_get_current_user();
				$meta = get_post_meta($project->ID, '_subscriberId');

				if(!in_array($current_user->ID, $meta))
				{
					?>
						<p class="error-message">U hebt geen toegang tot de gevraagde pagina. Ga <a href="javascript:history.back()-1">terug.</a></p>
					<?php
					return;
				}
			}
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

					<section class="left">
                            
                             <section id="startDate" class="date textbox">
                                <input id="eventTime" name="eventTime" readonly class="normalize-text" type="text" value="" />                   
                            </section>
                           
                                <input id="eventStartHour" class="textbox" name="eventStartHour" type="text" class="normalize-text" placeholder="Aanvangstijd (HH:MM)" value="" />
                        </section>
                        <section class="right">
                            <section id="endDate" class="date textbox">
                                <input id="eventEndTime" name="eventEndTime" readonly type="text" class="normalize-text" value="" />
                            </section>
                             
                                <input id="eventEndHour" class="textbox" name="eventEndHour" type="text" placeholder="Eindtijd (HH:MM)" value="" />
                           
                        </section>
					
					<input class="textbox normalize-text" id="eventLocation" name="eventLocation" type="text" placeholder="Locatie" />

					<label for="eventFeaturedImage" class="normalize-text">Afbeelding</label>
                  	<div id="file" style="height:0px;overflow:hidden">
                        <input id="eventFeaturedImage" class="image-upload" name="eventFeaturedImage" type="file" accept="image/x-png, image/gif, image/jpeg" />
                    </div>
                  	<button type="button" class="confirm-button" id="upload" onclick="chooseFile();">Kies afbeelding</button>

					<input id="eventPublish" name="eventPublish" class="form-button"  type="submit" value="Publiceer" />
				</form>
         		<script>
                    $(document).ready(function () {
                        $("#eventFeaturedImage").on("change", function () {
                            $("#upload").toggleClass("confirm-button");
                             $("#upload").toggleClass("confirm-button-green");
                        });
                    });
         			var nietLeeg = "Dit veld is verplicht!";

					var title = new LiveValidation('eventTitle', {validMessage:" "});
					title.add(Validate.Presence,{failureMessage:nietLeeg});

					var eventTime = new LiveValidation('eventTime', {validMessage:" ", onlyOnSubmit: true});
					eventTime.add(Validate.Presence,{failureMessage:nietLeeg});

					var eventEndTime = new LiveValidation('eventEndTime', {validMessage:" ", onlyOnSubmit: true});
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
                    
                    window.onload = imageValidationFix();
                    function imageValidationFix(){
                        id = "file";
                        document.getElementById(id).addEventListener('DOMNodeInserted', function(ev){
                            
                            
                            var span = $("#"+id+' span');
                                if(span != null){
                                    span.insertAfter("#upload");
                                    $("#upload").css({"margin-right":($(".contentwrapper").width()-$("#upload").outerWidth()-5)+"px"});
                                }
                            });
                    }
                    /* Fix */
                    
                    window.onload = fixSpan();
                    
                    function fixSpan(){
                            document.getElementById('startDate').addEventListener('DOMNodeInserted', function(ev){
                            var span = $('#startDate span');
                                if(span != null){
                                    span.insertAfter("#startDate");
                                }
                            });
                            document.getElementById('endDate').addEventListener('DOMNodeInserted', function(ev){
                            var span = $('#endDate span');
                                if(span != null){
                                    span.insertAfter("#endDate");
                                }
                            });
                    }
                   
         		</script>
			<?php
		}

		else if(isset($_POST['eventPublish']))
		{}

		else 
		{
			?>
				<p class="error-message">U bent niet ingelogd of u hebt de rechten niet om deze pagina te bekijken. Ga <a href="javascript:history.back()-1">terug.</a></p>
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
				$eventTitle = sanitize_text_field($_POST['eventTitle']);
				$eventDescription = $_POST['eventDescription'];
				$parentProjectId = sanitize_text_field($_POST['parentProjectId']);
				$eventTime = sanitize_text_field($_POST['eventTime']);
				$eventEndTime = sanitize_text_field($_POST['eventEndTime']);
				$eventLocation = sanitize_text_field($_POST['eventLocation']);
				$eventStartHour = sanitize_text_field($_POST['eventStartHour']);
				$eventEndHour = sanitize_text_field($_POST['eventEndHour']);

				$date1 = strtotime($eventTime);
				$date2 = strtotime($eventEndTime);

				if($date1 > $date2)
				{
					?>
						<p class="error-message">Gelieve een startdatum in te voeren die vroeger valt dan de einddatum. Ga <a class="normalize-text" href="javascript:history.back()-1">terug</a></p>
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
							<p class="error-message">Gelieve een aanvangstijd in te voeren die vroeger valt dan de eindtijd. Ga <a class="normalize-text" href="javascript:history.back()-1">terug.</a></p>
						<?php
						return;
					}

					if($start[0] == $end[0] && $start[1] > $end[1])
					{
						?>
							<p class="error-message">Gelieve een aanvangstijd in te voeren die vroeger valt dan de eindtijd. Ga <a class="normalize-text" href="javascript:history.back()-1">terug.</a></p>
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
						<p class="error-message">Helaas, er bestaat reeds een event met deze titel. <a class="normalize-text" href="javascript:history.back()-1">Terug</a></p>
					<?php
				}
			}

			else
			{
				?>
					<p class="error-message">Gelieve alle gegevens correct in te voeren. <a class="normalize-text" href="javascript:history.back()-1">Terug</a></p>
				<?php
			}
		}
	}
?>