<?php get_header();
	
	global $post;
	?>

	<section class="container">
		<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<div class="contentwrapper">
	<?php

	while(have_posts()) : the_post();
		?>
			<?php if(has_post_thumbnail($post->ID)) { ?>
				<div class="center"><?php echo get_the_post_thumbnail(); ?></div>
			<?php } ?>
			<h1><?php the_title(); ?></h1><br/>
			<p>
				Auteur: <a style="color:black;" class="author-name" href="<?php echo home_url(); ?>/profiel/?userid=<?php echo the_author_meta('ID'); ?>"><?php echo the_author_meta('first_name'); ?> <?php echo the_author_meta('last_name'); ?></a> |
				Gebubliceerd op: <?php echo get_the_date(); ?> | 
				Categorieën: 
				<?php 
					$categories =  get_the_category();

					foreach($categories as $category)
					{
						?>
							<a style="color:black;" class="author-name" href="<?php echo home_url(); ?>/artikels?categorie=<?php echo $category->term_id; ?>"><?php echo $category->name; ?></a> 
						<?php
					} 
				?>
			</p>
			<p><br/><?php echo the_content(); ?></p>
			<br/><br/><br/>
			<p>
				Tags: 
				<?php 
					$tags = get_the_tags();
					if($tags)
					{
						foreach($tags as $tag)
						{
							echo $tag->name . ' ';
						}
					}
				?>
			</p>
			<br/><br/><br/>
			<?php comments_template(); ?>
			<?php endwhile; ?>
				</div>
			</main>
		</div>
	</section>
	<?php
		if (!is_user_logged_in())
		{
			?> 
				<script>
					document.getElementById('respond').style['display'] = 'none';
					$('.comment-reply-link').css({'display':'none'});
				</script>
			<?php
		}
	?>
	<script>

		//$('.vcard').prepend('<p style="float:right;">' + '#datum#' + '<p>');
		$('.comment-reply-link').removeClass('comment-reply-link').addClass('form-button');
		$('.form-button').css({'float:':'left', 'width':'150px', 'margin':'15px 0 0 0', 'font-size' : '1.5em', 'text-align': 'center'});

		var form = document.getElementById('commentform');
		form.getElementsByTagName('p')[0].style.display = 'none';

		var respond = document.getElementById('respond');
		respond.getElementsByTagName('h3')[0].innerHTML = 'plaats een reactie';

		document.getElementById('comment').setAttribute('class', 'input');
		document.getElementById('comment').setAttribute('placeholder', 'Comment');
		document.getElementById('submit').setAttribute('class', 'form-button');
		document.getElementById('submit').setAttribute('style', 'float:left');
		document.getElementById('submit').setAttribute('value', 'plaats reactie');
	</script>

	<?php get_footer() ?>