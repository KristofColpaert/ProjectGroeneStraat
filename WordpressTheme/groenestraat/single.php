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
			<h1><?php the_title(); ?></h1><br/>
			<p><strong>Beschrijving:</strong><br/><?php echo the_content(); ?></p>
			<br/>
			<?php if(has_post_thumbnail($post->ID)) { ?>
			<p><strong>Foto:</strong><br/><br/>
			<section class="image-wrapper">
				<?php echo get_the_post_thumbnail(); ?>
			</section>
			<?php } ?>
			
		<?php endwhile; ?>
		
				</div>
			</main>
		</div>
	</section>

	<?php
	
	get_footer();
?>