<?php get_header();
	
	global $post;

	?>

	<section class="container">
		<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<div class="contentwrapper">

		<style>
			
		</style>

	<?php

	while(have_posts()) : the_post();
		?>
			<h1><?php the_title(); ?></h1><br/>
			<p><strong>Beschrijving:</strong><br/><?php echo the_content(); ?></p>
			<br/>
			<?php if(has_post_thumbnail($post->ID)) { ?>
			
			<?php comments_template(); ?>
			<!--<p><strong>Foto:</strong><br/><br/>-->
			<section class="image-wrapper">
				<?php //echo get_the_post_thumbnail(); ?>
			</section>
			<?php } ?>
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

		$('.comment-reply-link').removeClass('comment-reply-link').addClass('form-button');
		$('.form-button').css({'float:':'left', 'width':'30%', 'margin':'15px 0 0 0', 'font-size' : '1.5em', 'text-align': 'center'});

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