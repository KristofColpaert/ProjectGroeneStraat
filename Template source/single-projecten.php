<?php
	get_header();
	
	global $post;

	while(have_posts()) : the_post();
		$meta = get_post_meta($post->ID);
		$projectStreet = $meta['_projectStreet'][0];
		$projectCity = $meta['_projectCity'][0];
		$projectZipcode = $meta['_projectZipcode'][0];
		?>

		<h1><?php the_title(); ?></h1>
		<p>Hoofdbericht: <?php the_content() ?></p>
		<p>Straat: <?php echo $projectStreet; ?></p>
		<p>Gemeente: <?php echo $projectCity; ?></p>
		<p>Postcode: <?php echo $projectZipcode; ?></p>

		<?php
	endwhile;
	
	get_footer();
?>