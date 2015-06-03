<?php
	include_once('helper.php');

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
		if(isset($_GET['project']) && !isset($_POST['projectDelete']))
		{
			$current_user = wp_get_current_user();
			$project = get_post($_GET['project'], OBJECT);

			if($project != null && $current_user->ID == $project->post_author && current_user_can('delete_published_posts'))
			{
				?>
					<p>Bent u zeker dat u het project <strong><a href="<?php echo $project->guid; ?>"><?php echo $project->post_title; ?></a></strong> wilt verwijderen?</p>
					<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
						<input id="projectId" name="projectId" type="hidden" value="<?php echo $project->ID; ?>" />
						<input id="projectDelete" name="projectDelete" type="submit" value="Verwijder" />
					</form>
				<?php
			}

			else
			{
				?>
					<p>Dit project bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
				<?php
			}
		}

		else
		{
			?>
				<p>Dit project bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_delete_project_form()
	{
		if(isset($_POST['projectDelete']))
		{
			$projectId = $_POST['projectId'];

			wp_delete_post($projectId, false);

			?>
				<p>Het project werd succesvol verwijderd. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}
?>