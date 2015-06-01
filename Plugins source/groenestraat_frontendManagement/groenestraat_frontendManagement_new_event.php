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
		if(current_user_can('publish_events'))
		{
			?>
				
				<form action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
					<label for="eventTitle">Titel van het event</label>
					<input id="eventTitle" name="eventTitle" type="text" />

					<label for="eventDescription">Beschrijving van het event</label>
					<?php
						$settings = array('textarea_name' => 'eventDescription');
						$content = ''; 
						$editor_id = 'eventDescription';

						wp_editor($content, $editor_id, $settings);
					?>

					<label for="eventParent">
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
?>