<?php
	/*
		Plugin Name: Groenestraat Projectartikel Management
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin zorgt ervoor dat projectbeheerders artikels in hun project kunnen toelaten.
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	/*
		Shortcode plugin
	*/

	register_activation_hook(__FILE__, 'prowp_project_articles_install');

	add_shortcode('pending_articles', 'prowp_initialize_pending_articles');

	add_action('wp_ajax_nopriv_add_project_article', 'add_project_article');
	add_action('wp_ajax_add_project_article', 'add_project_article');

	function prowp_project_articles_install()
	{
		makeProjectArticlesShortcodePage('Projectartikels','[pending_articles]','projectartikels','publish','page','closed');
	}

	function makeProjectArticlesShortcodePage($title,$content,$post_name,$post_status,$post_type,$ping_status)
	{
		$args = array(
			'post_title' => $title,
			'post_content' => $content,
			'post_name' => $post_name,
			'post_status' => $post_status, 
			'post_type' => $post_type,
			'ping_status' => $ping_status
		);
		wp_insert_post($args);
	}

	/*
		Plugin methods
	*/

	function prowp_initialize_pending_articles()
	{
		show_pending_articles();
	}

	function show_pending_articles()
	{
		if(is_user_logged_int())
		{
			$current_user = wp_get_current_user();
			$args = array(
				'orderby' => 'title',
				'order' => 'ASC',
				'numberposts' => -1,
				'post_type' => 'projecten',
				'author' => $current_user->ID
			);
			$projects = get_posts($args);

			?>
				<section id="projectArticleMainContainer">
			<?php

			foreach($projects as $project)
			{
				$args = array(
					'orderby' => 'title',
					'order' => 'ASC',
					'numberposts' => -1,
					'post_type' => 'post',
					'post_status' => 'pending',
					'meta_key' => '_parentProjectId',
					'meta_value' => $project->ID
				);

				$posts = get_posts($args);
				$counter2 = 1;

				foreach($posts as $post)
				{
					?>
						<article class="box" id="projectArticleContainer<?php echo $post->ID; ?>">
	                        <section >
	                            <h2 class="normalize-text"><?php echo $post->post_title; ?></h2>
							    <p class="normalize-text"><?php echo $post->post_content; ?></p>
	                        </section>
							
							<form action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST">
								<input type="button" id="projectArticleSubmit<?php echo $post->ID; ?>" data="<?php echo $post->ID; ?>" name="projectArticleSubmit" class="projectArticleSubmit confirm-button-green" value="Accepteren" />
								<input type="button" id="projectArticleDelete<?php echo $post->ID; ?>" data="<?php echo $post->ID; ?>" name="projectArticleDelete" class="projectArticleDelete confirm-button" value="Verwijderen" />
							</form>
	                        
						</article>
	               
					<?php
				}
			}
			?>
	                <section class="clear"></section>
				</section>
			<?php
		}
	}

	function add_project_article()
	{
		if(isset($_POST['articleId']) && isset($_POST['articleAction']))
		{
			$articleId = $_POST['articleId'];
			$articleAction = $_POST['articleAction'];

			if($articleAction == 'add')
			{
				$args = array(
					'ID' => $articleId,
					'post_status' => 'publish'
				);
				wp_update_post($args);
				echo 'added';
				die();
			}

			else if($articleAction == 'delete')
			{
				wp_delete_post($articleId, true);
				echo 'deleted';
				die();
			}
		}

		echo 'none';
		die();
	}
?>