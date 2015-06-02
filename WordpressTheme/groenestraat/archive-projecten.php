<?php get_header(); ?>

	<section class="projecten-overzicht">

	<?php

	global $post;
	global $query_string;
	$index = 0;

	while(have_posts()) : the_post();

		$meta = get_post_meta($post->ID);
		$projectStreet = $meta['_projectStreet'][0];
		$projectCity = $meta['_projectCity'][0];
		$projectZipcode = $meta['_projectZipcode'][0];

		?>
			<a href="<?php the_permalink(); ?>">
				<section class="project-item" id="project">				
					<h2><?php the_title(); ?></h2>
					<?php 
						if ( has_post_thumbnail() ) { 
							the_post_thumbnail();
						} 
						?>
						<?php the_content(); ?>
				</section>

					<?php 
						//echo "<script> document.getElementsByClassName('project-item')[" . $index . "].backgroundImage = url('img/')";
						$index++;
					?>
			</a>
	<?php
	
	endwhile;

	//previous_posts_link();
	//next_posts_link();

	?>

	</section>
	<section class="clear"></section>
	<!--<section class="fix"></section>-->

<?php
	get_footer();
?>