<?php
	/*
		Shortcode plugin
	*/

	add_shortcode('delete_article', 'prowp_delete_article');

	function prowp_delete_article()
	{
		save_delete_article_form();
		show_delete_article_form();
	}

	/*
		Plugin methods
	*/

	function show_delete_article_form()
	{
		if(is_user_logged_in() && isset($_GET['artikel']) && !isset($_POST['articleDelete']))
		{
			$current_user = wp_get_current_user();
			$article = get_post($_GET['artikel'], OBJECT);

			if($article->post_type!= 'post' || get_post_status($article->ID) != 'publish')
			{
				?>
					<p class="error-message">Dit artikel bestaat (nog) niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
				<?php
				return;
			}

			if($article != null && ($current_user->ID == $article->post_author || current_user_can('manage_options')) && current_user_can('delete_published_posts'))
			{
				?>
					<p class="alert-message normalize-text">Bent u zeker dat u het artikel <strong><a href="<?php echo $article->guid; ?>"><?php echo $article->post_title; ?></a></strong> wilt verwijderen?</p>
					<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
						<input id="projectId" name="projectId" type="hidden" value="<?php echo $project->ID; ?>" />
						<input class="confirm-button" id="articleDelete" name="articleDelete" type="submit" value="Verwijder" />
                        <a class="cancel-button" href="<?php echo $article->guid; ?>">Annuleer</a>
                        <div class="clear"></div>
					</form>
				<?php
			}

			else
			{
				?>
					<p class="error-message">Dit project bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
				<?php
			}
		}

		else if(isset($_POST['articleDelete']))
		{}

		else
		{
			?>
				<p class="error-message">Dit project bestaat niet, of u hebt geen toegang tot de gevraagde pagina. Ga terug naar <a href="<?php echo home_url(); ?>">Home</a>.</p>
			<?php
		}
	}

	function save_delete_article_form()
	{
		if(isset($_POST['articleDelete']))
		{
			$articleId = $_POST['articleId'];

			wp_delete_post($articleId, false);

			delete_post_meta($articleId, '_thumbnail_id');
			delete_post_meta($articleId, '_parentProjectId');

			?>
				<img class="center" src="<?php echo get_template_directory_uri() ?>/img/ball.gif" />
	            <script>
	                $('.title').remove();
	            </script>
	            <h2 class="normalize-text center">Uw artikel wordt verwijderd</h2>
			<?php

			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . esc_url(home_url()) . '">'; 
			return;
		}
	}
?>