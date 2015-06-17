<?php
	/*
		Shortcode plugin
	*/

	add_shortcode('delete_project', 'prowp_delete_project');

	function prowp_delete_project()
	{
		save_delete_project_form();
		show_delete_project_form();
	}

	function show_delete_project_form()
	{
		if(is_user_logged_in() && isset($_GET['project']) && !isset($_POST['projectDelete']))
		{
			$current_user = wp_get_current_user();
			$project = get_post($_GET['project'], OBJECT);

			if($project->post_type != 'projecten')
			{
				?>
					<p class="error-message">Dit project bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
				<?php
				return;
			}

			if($project != null && ($current_user->ID == $project->post_author || current_user_can('manage_options')) && current_user_can('delete_published_projecten'))
			{
				?>
					<p class="alert-message normalize-text">Bent u zeker dat u het project <strong><a href="<?php echo $project->guid; ?>"><?php echo $project->post_title; ?></a></strong> wilt verwijderen?</p>
					<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
						<input id="projectId" name="projectId" type="hidden" value="<?php echo $project->ID; ?>" />
						<input class="confirm-button" id="projectDelete" name="projectDelete" type="submit" value="Verwijder" />
                        <a class="cancel-button" href="<?php echo $project->guid; ?>">Annuleer</a>
                        <div class="clear"></div>
					</form>
				<?php
			}

			else
			{
				?>
					<p class="error-message">Dit project bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga <a href="javascript:history.back()-1">terug.</a></p>
				<?php
			}
		}

		else if(isset($_POST['projectDelete']))
		{}

		else
		{
			?>
				<p class="error-message">U moet zich aanmelden om deze pagina te bekijken. <a class="normalize-text" href="<?php echo home_url(); ?>/login">Aanmelden</a></p>
			<?php
		}
	}

	function save_delete_project_form()
	{
		if(isset($_POST['projectDelete']))
		{
			$projectId = $_POST['projectId'];

			wp_delete_post($projectId, false);

			delete_post_meta($projectId, '_thumbnail_id');
			delete_post_meta($projectId, '_projectCity');
			delete_post_meta($projectId, '_projectZipcode');
			delete_post_meta($projectId, '_projectStreet');

			?>
				<img class="center" src="<?php echo get_template_directory_uri() ?>/img/ball.gif" />
	            <script>
	                $('.title').remove();
	            </script>
	            <h2 class="normalize-text center">Uw project wordt verwijderd</h2>
			<?php

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . esc_url(home_url()) . '/mijn-projecten">'; 
			return;
		}
	}
?>