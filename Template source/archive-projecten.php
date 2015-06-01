<?php
	get_header();

	global $post;
	global $query_string;

	while(have_posts()) : the_post();
		$meta = get_post_meta($post->ID);
		$projectStreet = $meta['_projectStreet'][0];
		$projectCity = $meta['_projectCity'][0];
		$projectZipcode = $meta['_projectZipcode'][0];
		?>
			<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<p>Hoofdbericht: <?php the_content() ?></p>
			<p>Straat: <?php echo $projectStreet; ?></p>
			<p>Gemeente: <?php echo $projectCity; ?></p>
			<p>Postcode: <?php echo $projectZipcode; ?></p>
			<hr />
		<?php
	endwhile;

	previous_posts_link();
	next_posts_link();
	
	get_footer();
?>