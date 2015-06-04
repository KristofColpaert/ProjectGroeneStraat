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
		if(isset($_GET['event']) && !isset($_POST['eventDelete']))
		{
			$current_user = wp_get_current_user();
			$event = get_post($_GET['event'], OBJECT);

			if($event != null && ($current_user->ID == $event->event_author || current_user_can('manage_options')) && current_user_can('delete_published_posts'))
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
					<p>Dit event bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
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

			?>
				<p>Het event werd succesvol verwijderd. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
				exit;
		}
	}
?>