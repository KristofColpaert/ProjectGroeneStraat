<?php
	/*
		Shortcode plugin
	*/

	add_shortcode('delete_event', 'prowp_delete_event');

	function prowp_delete_event()
	{
		save_delete_event_form();
		show_delete_event_form();
	}

	function show_delete_event_form()
	{
		if(is_user_logged_in() && isset($_GET['event']) && !isset($_POST['eventDelete']))
		{
			$current_user = wp_get_current_user();
			$event = get_post($_GET['event'], OBJECT);

			if($event != null && ($current_user->ID == $event->post_author || current_user_can('manage_options')) && current_user_can('delete_published_events'))
			{
				?>
					<p class="alert-message normalize-text">Bent u zeker dat u het event <strong><a href="<?php echo $event->guid; ?>"><?php echo $event->post_title; ?></a></strong> wilt verwijderen?</p>
					<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
						<input id="eventId" name="eventId" type="hidden" value="<?php echo $event->ID; ?>" />
						<input class="confirm-button" id="eventDelete" name="eventDelete" type="submit" value="Verwijder" />
                        <a class="cancel-button" href="<?php echo $event->guid; ?>">Annuleren</a><div class="clear"></div>
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

		else if(isset($_POST['eventDelete']))
		{}

		else
		{
			?>
				<p class="error-message">Dit event bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_delete_event_form()
	{
		if(isset($_POST['eventDelete']))
		{
			$current_user = wp_get_current_user();
			$eventId = $_POST['eventId'];

			wp_delete_post($eventId, false);

			$users = get_users();
			foreach($users as $user)
			{
				delete_user_meta($user->ID, '_eventCalendar', $eventId);
			} 

			delete_post_meta($eventId, '_thumbnail_id');
			delete_post_meta($eventId, '_eventTime');
			delete_post_meta($eventId, '_eventEndTime');
			delete_post_meta($eventId, '_eventLocation');

			?>
				<img class="center" src="<?php echo get_template_directory_uri() ?>/img/ball.gif" />
	            <script>
	                $('.title').remove();
	            </script>
	            <h2 class="normalize-text center">Uw event wordt verwijderd</h2>
			<?php

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . esc_url(home_url()) . '/mijn-events">'; 
			return;
		}
	}
?>