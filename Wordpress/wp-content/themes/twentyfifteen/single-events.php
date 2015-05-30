<?php
	get_header();
	
	global $post;

	while(have_posts()) : the_post();
		$meta = get_post_meta($post->ID);
		$eventTime = $meta['_eventTime'][0];
		$eventLocation = $meta['_eventLocation'][0];
		$eventMoreInfo = $meta['_eventMoreInfo'][0];
		?>

		<h1><?php the_title(); ?></h1>
		<p>Tijdstip: <?php echo $eventTime; ?></p>
		<p>Location: <?php echo $eventLocation; ?></p>
		<p>Meer info: <?php echo $eventMoreInfo; ?></p>

		<?php
	endwhile;
	
	get_footer();
?>