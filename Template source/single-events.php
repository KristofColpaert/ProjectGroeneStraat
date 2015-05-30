<?php 
$user_id = get_current_user_id();
$meta_key = "_eventCalendar ";

if(isset($_POST["eventID"])){
	$meta_value = $_POST["eventID"];


	//ophalen data uit database
	$value = get_user_meta($user_id, $meta_key);

	//controle of aangeklikt event al in database (of in kalender) zit
	if (!in_array($meta_value, $value)) 
	{
    	add_user_meta($user_id, $meta_key, $meta_value);
	}
}

?>

<?php
	get_header();
	
	global $post;

	while(have_posts()) : the_post();
		$meta = get_post_meta($post->ID);
		$eventTime = $meta['_eventTime'][0];
		$eventLocation = $meta['_eventLocation'][0];
		$eventMoreInfo = $meta['_eventMoreInfo'][0];
		$eventID = $post->ID;
		$value = get_user_meta($user_id, $meta_key);

		if(in_array($eventID, $value))
		{
			$hide = "hidden=hidden";
		}
		else
		{
			$hide = "";
		}
		?>

	<form method="post" action="<?php echo get_permalink(); ?>" >
		<h1><?php the_title(); ?></h1>
		<p name="_eventTime">Tijdstip: <?php echo $eventTime; ?></p>
		<p>Location: <?php echo $eventLocation; ?></p>
		<p>Meer info: <?php echo $eventMoreInfo; ?></p>
		<input type="submit" value="Toevoegen aan calender" <?php echo $hide ?> />

		<input type="hidden" name="eventID" value="<?php echo $eventID; ?>" />
	</form>


		<?php
	endwhile;
	
	get_footer();
?>