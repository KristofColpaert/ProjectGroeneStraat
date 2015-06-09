<?php get_header(); ?>

	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDC5KoyRfBkse2foN9chLRWQuo0kC61qXI"></script>
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
    <?php
	
	global $post;

	?>

	<section class="container">
		
		

	<?php

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
            <?php if(has_post_thumbnail($post->ID)) { 
			 $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );?>
			<section class="image-wrapper">
				
			</section>
        <script>
             $('.image-wrapper').css({"background-image":"url(<?php echo $url; ?>)"});
        </script>
		<?php } ?>
            <div class="eventwrapper normalize-text">
		<h1><?php the_title(); ?></h1><br/>
                <section class="column-3">
                    <p><strong>Beschrijving:</strong><br/><?php echo the_content(); ?></p>
                </section>
			
                <section class="column-3">
                    <p><strong>Tijdstip:</strong><br/><?php echo $eventTime . " - " . $eventEndTime; ?></p>
	
                </section >
                <section class="column-3">
                    <p><strong>Locatie:</strong><br/><?php echo $eventLocation; ?></p>
                </section>
			<section class="clear"></section>
			
			
			<?php

			if($post->post_author != get_current_user_id() && is_user_logged_in())
			{
				?>
					<p><strong>Plaatsen op kalender?</strong></p><br/>
					<form method="POST" id="eventMemberForm">
						<input id="eventMemberId" name="eventMemberId" type="hidden" value="<?php echo get_current_user_id(); ?>" />
						<input id="eventMemberProjectId" name="eventMemberProjectId" type="hidden" value="<?php echo $post->ID; ?>" />
						<input id="eventMemberSubmit" name="eventMemberSubmit" type="submit" value="Toevoegen aan persoonlijke kalender" class="form-button" style="margin:0" />
					</form>
				<?php
			}

			?>

			
			<br/><br/>
                </div>
			<div id="map-canvas"></div>
		<!-- Kristof zijn toevoegingen -->
		<?php endwhile; ?>

				
			
	</section>
	<script type="text/javascript">
        
       
		var map;
		var myLatlng;

	    function initialize() {
	    	getCoordinates('<?php echo $eventLocation; ?>', function(coords) 
	    	{
	    		var mapOptions = {
	    			zoom: 12,
		          	center: new google.maps.LatLng(coords[0], coords[1]),
		          	disableDefaultUI: true, 
                    scrollwheel: false,
		        };

		        myLatlng = new google.maps.LatLng(coords[0], coords[1]);

		        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

		        var contentString = '<div id="content">'+
			      					'<div id="bodyContent" style="font-size:0.7em;text-align:center;padding:4px">'+
			      					'<p style="font-size:2.4em"><b><?php echo the_title(); ?></b></p>' +
								    '<p><?php echo $eventTime . " - " . $eventEndTime;?></p>'+
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
    </script>
	<?php
	
	get_footer();
?>