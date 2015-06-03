<?php
	/*
		Shortcode plugin
	*/

	add_shortcode('delete_zoekertje', 'prowp_delete_zoekertje');

	function prowp_delete_zoekertje()
	{
		show_delete_zoekertje_form();
		save_delete_zoekertje_form();
	}

	function show_delete_zoekertje_form()
	{
		if(isset($_GET['zoekertje']) && !isset($_POST['zoekertjeDelete']))
		{
			$current_user = wp_get_current_user();
			$zoekertje = get_post($_GET['zoekertje'], OBJECT);

			if($zoekertje != null && ($current_user->ID == $zoekertje->zoekertje_author || current_user_can('manage_options')) && current_user_can('delete_published_posts'))
			{
				?>
					<p>Bent u zeker dat u het zoekertje <strong><a href="<?php echo $event->guid; ?>"><?php echo $zoekertje->post_title; ?></a></strong> wilt verwijderen?</p>
					<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
						<input id="zoekertjeId" name="zoekertjeId" type="hidden" value="<?php echo $zoekertje->ID; ?>" />
						<input id="zoekertjeDelete" name="zoekertjeDelete" type="submit" value="Verwijder" />
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
				<p>Dit zoekertje bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_delete_zoekertje_form()
	{
		if(isset($_POST['zoekertjeDelete']))
		{
			$current_user = wp_get_current_user();
			$zoekertjeId = $_POST['zoekertjeId'];

			wp_delete_post($zoekertjeId, false);

			$users = get_users();
			foreach($users as $user)
			{
				delete_user_meta($user->ID, '_eventCalendar', $eventId);
			} 

			?>
				<p>Het zoekertje werd succesvol verwijderd. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
				exit;
		}
	}
?>