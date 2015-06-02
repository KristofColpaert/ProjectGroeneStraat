<?php
	get_header();

	global $post;

	while(have_posts()) : the_post();
		$meta = get_post_meta($post->ID);
		$eventTime = $meta['_eventTime'][0];
		$eventLocation = $meta['_eventLocation'][0];
		$eventMoreInfo = $meta['_eventMoreInfo'][0];

		?>
			<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<p>Tijdstip: <?php echo $eventTime; ?></p>
			<p>Location: <?php echo $eventLocation; ?></p>
			<p>Meer info: <?php echo $eventMoreInfo; ?></p>
			<hr />
		<?php
	endwhile;

	previous_posts_link();
	next_posts_link();
	
	get_footer();
?>