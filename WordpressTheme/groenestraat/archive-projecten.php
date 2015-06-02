<?php get_header(); ?>

	<section class="container">
	<section class="projecten-menu">
		<ul>
			<li><a href="#">Nieuw project</a></li>
			<li><a href="#">Mijn projecten</a></li>
		</ul>
	</section>

	<?php

	global $post;
	global $query_string;
	$index = 0;

	$orig_query = $wp_query;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$wp_query = new WP_Query(array('post_type' => 'projecten', 'posts_per_page' => 9, 'paged' => $paged));

	while($wp_query->have_posts()) : $wp_query->the_post();

		$meta = get_post_meta($post->ID);
		$projectStreet = $meta['_projectStreet'][0];
		$projectCity = $meta['_projectCity'][0];
		$projectZipcode = $meta['_projectZipcode'][0];

		?>
			<a href="<?php the_permalink(); ?>">
				<section class="project-item">				
					<?php 
						if (has_post_thumbnail()) 
						{ 
							?>
								<script> 
									var e = document.getElementsByClassName("project-item")['<?php echo $index; ?>'];
									e.style["background-image"] = "url('<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>')";
									e.style["background-size"] = "cover";
								</script>
							<?php
						} 
						else
						{
							?>
							<script> 
									var e = document.getElementsByClassName("project-item")['<?php echo $index; ?>'];
									e.style["background-image"] = "url('<?php echo bloginfo('template_directory'); ?>/img/project-example.png')";
									e.style["background-size"] = "cover";
							</script>
							<?php
						}
						?>
						<section class="info">
							<h1><?php the_title(); ?></h1>
							<p><?php echo $projectCity . " " . $projectZipcode; ?></p>
						</section>
				</section>
					<?php
						$index++;
					?>
			</a>
	<?php
	
	endwhile;
	$wp_query = $orig_query;

	?>

	</section>
	<section class="clear"></section>
	
	<section class="navigate-menu">
	<?php

	previous_posts_link();
	next_posts_link();

	?>

	
	</section>
	

<?php
	get_footer();
?>