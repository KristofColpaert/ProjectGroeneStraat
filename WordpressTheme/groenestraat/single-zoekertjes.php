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

	$apiKey = get_option('_applicationId');
	
	global $post;
	$users = array();
	?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey; ?>"></script>
	<script>
		geocoder = new google.maps.Geocoder();
		window.getCoordinates = function(address, callback)
		{
			var coordinates;
			geocoder.geocode({ address: address }, function (results, status)
			{
				coords_obj = results[0].geometry.location;
				console.log(coords_obj);
				coordinates = [coords_obj.A, coords_obj.F];
				callback(coordinates);
			})
		}
	</script>

        <section class="zoekertjewrapper">
       
		
	<?php

	while(have_posts()) : the_post();
			$meta = get_post_meta($post->ID);
			$adLocation = $meta['_adLocation'][0];
			$adPrice = $meta['_adPrice'][0];
 if($current_user->ID > 0 && $post->post_author == $current_user->ID)
			{
		?>
           
            <a class="edit-button" href="<?php echo get_site_url();?>/bewerk-zoekertje?zoekertje=<?php echo $post->ID ?>">Bewerken</a><?php } ?>
			<h1><?php the_title(); ?></h1><br/>
            <section class="stack-3">
                <p><img src="<?php echo get_template_directory_uri();?>/img/description.png"/><br/><?php echo the_content(); ?></p><br/><p class="green-text">
                <?php 
                    if($adPrice == 0){
                        echo "gratis";
                    }
else{
    echo $adPrice." euro";
}
                ?>
                </p>
            </section>
			
			<section class="stack-3" id="location-canvas">
            </section>
			
			<br/>
			<?php if(has_post_thumbnail($post->ID)) { ?>
			
			<section class="stack-3" id="zoekertje-image">
				
			</section>
			<?php } ?>
			

			<!--naam, email, reactie 
			<parentprojectId (meta) - enkel gebruikers die lid zijn van da project mogen et zoekertje zien -->
			<!-- als zoekertje geen project heeft mag iedereen het zien !-->
			
			<?php 
			global $post;
			$meta_key = "_parentProjectId";
			$current_user = wp_get_current_user();

			//kijken of user project heeft.
			$results = $wpdb->get_results($wpdb->prepare( "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $post->ID, $meta_key), ARRAY_A);
			$projectId = $results[0]["meta_value"];

			//kijken of er subscribers zijn op dat project.
			$subscriber = "_subscriberId";
			$subscribers = $wpdb->get_results($wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $projectId, $subscriber), ARRAY_A);

			foreach($subscribers as $subscriber)
			{
				$users[] = $subscriber["meta_value"];
			}

			if($current_user->ID > 0 && $post->post_author != $current_user->ID)
			{
				if(in_array($current_user->ID, $users) || count($results) == 0 )
				{
					$name = $current_user->display_name;
					$email = $current_user->user_email;
					?>
    <section class="stack-3">
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
        </form></section>
					<?php
				}

			}
			?>
		<?php
	endwhile;

	?>			
		

</section>

	<script type="text/javascript">
        
       
		var map;
		var myLatlng;

	    function initialize() {
	    	getCoordinates('<?php echo $adLocation; ?>', function(coords) 
	    	{
	    		var mapOptions = {
	    			zoom: 12,
		          	center: new google.maps.LatLng(coords[0], coords[1]),
		          	disableDefaultUI: true, 
                    scrollwheel: false,
		        };

		        myLatlng = new google.maps.LatLng(coords[0], coords[1]);

		        map = new google.maps.Map(document.getElementById('location-canvas'), mapOptions);

		        var contentString = '<div id="content">'+
			      					'<div id="bodyContent" style="font-size:0.7em;text-align:center;padding:4px">'+
			      					'<p style="font-size:2.4em"><b><?php echo $adLocation; ?></b></p>' +
								    '</div>'+
								    '</div>';

				var infowindow = new google.maps.InfoWindow({
				    content: contentString,
      				maxWidth: 250,
      				maxHeight: 50
				});

				var marker = new google.maps.Marker({
			    	position: myLatlng,
			    	map: map,
			    	title: 'Meer info'
  				});


				infowindow.open(map, marker);
	    	})
	    }


	    google.maps.event.addDomListener(window, 'load', initialize);
        
        $("#zoekertje-image").css({"background-image": "url(<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) );?>)"});
    </script>
	<?php
	
	get_footer();
?>