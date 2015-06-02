<?php 
	/*
		Check if user has completed the form to add Event to his personal calendar
	*/
	global $post;
	$user_id = get_current_user_id();
	$meta_key = "_eventCalendar ";

	//ophalen data uit database
	$value = get_user_meta($user_id, $meta_key);
	$meta_val = $post->ID;

	//Juiste value instellen
	if(in_array($meta_val, $value))
	{
		$waarde = "Verwijderen uit kalender";
	}
	else
	{
		$waarde = "Toevoegen aan kalender";
	}


	if(isset($_POST["eventID"])){
		$meta_value = $_POST["eventID"];

		//controle of aangeklikt event al in database (of in kalender) zit
		if (!in_array($meta_value, $value)) 
		{
	    	add_user_meta($user_id, $meta_key, $meta_value);
	    	$waarde = "Verwijderen uit kalender";
		}
		else
		{
			delete_user_meta($user_id, $meta_key, $meta_value );
			$waarde = "Toevoegen aan kalender";
		}
		
	}
?>

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
		$value = get_user_meta($user_id, $meta_key);

		/*
			Form to add event to personal calendar.
		*/

		?>

		<form method="post" action="<?php echo get_permalink(); ?>" >
			<h1><?php the_title(); ?></h1>
			<p><?php the_content(); ?></p>
			<p name="_eventTime">Startdatum: <?php echo $eventTime; ?></p>
			<p name="_eventEndTime">Einddatum: <?php echo $eventEndTime; ?></p>
			<p>Location: <?php echo $eventLocation; ?></p>
			<input type="submit" value="<?php echo $waarde; ?>" />

			<input type="hidden" name="eventID" value="<?php echo $eventID; ?>" />
		</form>


		<?php
	endwhile;
	
	get_footer();
?>