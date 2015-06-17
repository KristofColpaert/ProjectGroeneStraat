<?php
	/*
		Shortcode plugin
	*/

	add_shortcode('delete_zoekertje', 'prowp_delete_zoekertje');

	function prowp_delete_zoekertje()
	{
		save_delete_zoekertje_form();
		show_delete_zoekertje_form();
	}

	function show_delete_zoekertje_form()
	{
		if(is_user_logged_in() && isset($_GET['zoekertje']) && !isset($_POST['zoekertjeDelete']))
		{
			$current_user = wp_get_current_user();
			$zoekertje = get_post($_GET['zoekertje'], OBJECT);


			if($zoekertje->post_type != 'zoekertjes')
			{
				?>
					<p class="error-message">Dit zoekertje bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga <a href="javascript:history.back()-1">terug.</a></p>
				<?php
				return;
			}

			if($zoekertje != null && ($current_user->ID == $zoekertje->post_author || current_user_can('manage_options')) && current_user_can('delete_published_zoekertjes'))
			{
				?>
					<p class="alert-message normalize-text">Bent u zeker dat u het zoekertje <strong><a href="<?php echo $zoekertje->guid; ?>"><?php echo $zoekertje->post_title; ?></a></strong> wilt verwijderen?</p>
					<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
						<input id="zoekertjeId" name="zoekertjeId" type="hidden" value="<?php echo $zoekertje->ID; ?>" />
						<input id="zoekertjeDelete" class="confirm-button" name="zoekertjeDelete" type="submit" value="Verwijder" />
                        <a class="cancel-button" href="<?php echo $zoekertje->guid; ?>">Annuleer</a>
                        <div class="clear"></div>
					</form>
				<?php
			}
			else
			{
				?>
					<p class="error-message">Dit zoekertje bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga <a href="javascript:history.back()-1">terug.</a></p>
				<?php
			}
		}

		else if(isset($_POST['zoekertjeDelete']))
		{}

		else
		{
			?>
				<p class="error-message">U bent niet ingelogd of u hebt de rechten niet om deze pagina te bekijken. Ga <a href="javascript:history.back()-1">terug.</a></p>
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

			delete_post_meta($zoekertjeId, '_parentProjectId');
			delete_post_meta($zoekertjeId, '_adPrice');
			delete_post_meta($zoekertjeId, '_adLocation');
			delete_post_meta($zoekertjeId, '_thumbnail_id');

			?>
				<img class="center" src="<?php echo get_template_directory_uri() ?>/img/ball.gif" />
	            <script>
	                $('.title').remove();
	            </script>
	            <h2 class="normalize-text center">Uw zoekertje wordt verwijderd</h2>
			<?php

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . esc_url(home_url()) . '/mijn-zoekertjes">'; 
			return;
		}
	}
?>