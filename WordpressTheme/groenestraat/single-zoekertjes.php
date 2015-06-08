<?php

	if(isset($_POST["Verzenden"]))
	{
		if(isset($_POST["Name"]) && isset($_POST["Email"]) && isset($_POST["projectId"]) && isset($_POST["Reactie"]) && isset($_POST["Title"]))
		{
			if(!empty($_POST["Name"]) && !empty($_POST["Email"]) && !empty($_POST["projectId"]) && !empty($_POST["Reactie"]) && !empty($_POST["Title"]))
			{

				$projectId = $_POST["projectId"];
				$adminId = get_post($projectId)->post_author;
				$adminData = get_userdata($adminId);
				$adminEmail = $adminData->user_email;

				$name = $_POST["Name"];
				$userEmail = $_POST["Email"];
				$reactie = $_POST["Reactie"];
				$title = $_POST["Title"];
				$onderwerp = "Reactie " . $name . " op zoekertje: '" . $title . "'";

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

				if(wp_mail($adminEmail, $onderwerp, $reactie, $headers))
				{
					//echo "Verstuurd";

				}
				else
				{		
					//echo "Niet verstuurd";
				}
			}	
		}		
	}

?>

<?php
	get_header();
	
	global $post;

	?>
	<section class="container">
		<section class="single-item">
	<?php

	while(have_posts()) : the_post();
			$meta = get_post_meta($post->ID);
			$adLocation = $meta['_adLocation'][0];
			$adPrice = $meta['_adPrice'][0];
		?>
			<h1><?php the_title(); ?></h1><br/>
			<p><strong>Beschrijving:</strong><br/><?php echo the_content(); ?></p>
			<br/>
			<p><strong>Location:</strong><br/><?php echo $adLocation; ?></p>
			<br/>
			<p><strong>Prijs:</strong><br/><?php echo $adPrice; ?></p>
			<br/>
			<?php if(has_post_thumbnail($post->ID)) { ?>
			<p><strong>Foto:</strong><br/><br/>
			<section class="image-wrapper">
				<?php echo get_the_post_thumbnail(); ?>
			</section>
			<?php } ?>
			

			<!--naam, email, reactie 
			<parentprojectId (meta) - enkel gebruikers die lid zijn van da project mogen et zoekertje zien -->
			<!-- als zoekertje geen project heeft mag iedereen het zien !-->
			
			<?php 
			global $post;
			$meta_key = "_parentProjectId";
			$current_user = get_current_user_id();

			//kijken of user project heeft.
			$results = $wpdb->get_results($wpdb->prepare( "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $post->ID, $meta_key), ARRAY_A);
			$projectId = $results[0]["meta_value"];

			$subscriber = "_subscriberId";
			$subscribers = $wpdb->get_results($wpdb->prepare( "SELECT meta_value AS SubscriberId FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $projectId, $subscriber), ARRAY_A);

			foreach($subscribers as $subscriber)
			{
				$users[] = $subscriber["SubscriberId"];
			}

			//count($results) als project nergens behoort kan iedereen reageren.
			if($current_user > 0 && $post->post_author != $current_user)
			{
				if(in_array($current_user, $users) || count($results) == 0 )
				{
					$userdata = get_userdata($current_user);
					$name = $userdata->first_name;
					$email = $userdata->user_email;
					?>
						<form method="POST" class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
							
							<label for="Name">Naam</label>
							<input type="text" readonly name="Name" value="<?php echo $name; ?>"/><br />
							
							<label for="Email">E-mail</label>
							<input type="text" readonly name="Email" value="<?php echo $email; ?>"/><br />

							<label for="Reactie">Reactie</label>
							<textarea name="Reactie">

							</textarea><br />

							<input type="submit" value="Verzenden" name="Verzenden" />
							<input type="hidden" value="<?php the_title(); ?>" name="Title" />
							<input type="hidden" value="<?php echo $projectId; ?>" name="projectId" />
						</form>
					<?php
				}

				}
			?>
		<?php
	endwhile;

	?>

	<br/><br/><br/><hr/><br/><br/>
	
	<form method="POST" class="createForm" style="width:50%"; action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
							
		<label for="Name">Naam</label>
							<input class="textbox" type="text" readonly name="Name" value="<?php echo $name; ?>"/><br />
							
							<label for="Email">E-mail</label>
							<input class="textbox" type="text" readonly name="Email" value="<?php echo $email; ?>"/><br />

							<label for="Reactie">Reactie</label>
							<textarea class="textbox" name="Reactie" style="height:100px">

							</textarea><br />

							<input type="submit" value="Verzenden" name="Verzenden" class="form-button" />
							<input type="hidden" value="<?php the_title(); ?>" name="Title" />
							<input type="hidden" value="<?php echo $projectId; ?>" name="projectId" />
						</form>



		</section>
	</section>

	<?php
	
	get_footer();
?>