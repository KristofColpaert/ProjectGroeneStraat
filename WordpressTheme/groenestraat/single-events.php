<?php
	/*
		Code to view Event
	*/

	get_header();
	
	global $post;

	while(have_posts()) : the_post();
		$meta = get_post_meta($post->ID);
		$eventTime = $meta['_eventTime'][0];
		$eventEndTime = $meta['_eventEndTime'][0];
		$eventLocation = $meta['_eventLocation'][0];
		$eventID = $post->ID;

		/*
			Form to add event to personal calendar.
		*/

		?>
		<h1><?php the_title(); ?></h1>
		<p><?php the_content(); ?></p>
		<p name="_eventTime">Startdatum: <?php echo $eventTime; ?></p>
		<p name="_eventEndTime">Einddatum: <?php echo $eventEndTime; ?></p>
		<p>Location: <?php echo $eventLocation; ?></p>

		<!-- Kristof zijn toevoegingen -->
		<?php 
			if($post->post_author != get_current_user_id() && is_user_logged_in())
			{
				?>
					<form method="POST" id="eventMemberForm">
						<input id="eventMemberId" name="eventMemberId" type="hidden" value="<?php echo get_current_user_id(); ?>" />
						<input id="eventMemberProjectId" name="eventMemberProjectId" type="hidden" value="<?php echo $post->ID; ?>" />
						<input id="eventMemberSubmit" name="eventMemberSubmit" type="submit" value="Toevoegen aan persoonlijke kalender" />
					</form>
				<?php
			}
	endwhile;
	
	get_footer();
?>