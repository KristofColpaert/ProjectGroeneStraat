<?php
	get_header();
	
	global $post;

	while(have_posts()) : the_post();
		$meta = get_post_meta($post->ID);
		$adName = $meta['_adName'][0];
		$adLocation = $meta['_adLocation'][0];
		$adDescription = $meta['_adDescription'][0];
		?>
			<h1><?php the_title(); ?></h1>
			<p>Name: <?php echo $adName; ?></p>
			<p>Location: <?php echo $adLocation; ?></p>
			<p>Meer info: <?php echo $adDescription; ?></p>
			<hr />
		<?php
	endwhile;
	
	get_footer();
?>