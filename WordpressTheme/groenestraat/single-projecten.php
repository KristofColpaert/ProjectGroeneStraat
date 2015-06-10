<?php function getMonth($var)
	{
		switch ($var) 
		{
		case '01':
			$string = 'JANUARI';
			break;
		case '02':
			$string = 'FEBRUARI';
			break;
		case '03':
			$string = 'MAART';
			break;
		case '04':
			$string = 'APRIL';
			break;
		case '05':
			$string = 'MEI';
			break;
		case '06':
			$string = 'JUNI';
			break;
		case '07':
			$string = 'JULI';
			break;
		case '08':
			$string = 'AUGUSTUS';
			break;
		case '09':
			$string = 'SEPTEMBER';
			break;
		case '10':
			$string = 'OKTOBER';
			break;
		case '11':
			$string = 'NOVEMBER';
			break;
		case '12':
			$string = 'DECEMBER';
			break;
		default:
			# code...
			break;
		}
		return $string;
	}
	get_header(); 
	global $post;
?>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDC5KoyRfBkse2foN9chLRWQuo0kC61qXI"></script>
    <script>
        //Get coordinates of a location.
        geocoder = new google.maps.Geocoder();
        window.getCoordinates = function(address, callback)
        {
            var coordinates;
            geocoder.geocode({ address: address }, function (results, status)
            {
                coords_obj = results[0].geometry.location;
                coordinates = [coords_obj.A, coords_obj.F];
                callback(coordinates);
            })
        }
    </script>

	<section class="project-image header-image" id="project-image">
		
	</section>
       
	<section class="container" style="background-color:#ccc">
         <section class="sub-menu">
             <section class="project-info">
			
		      </section>
            <ul>
                <li>
                    <a href="<?php echo get_site_url();?>/nieuw-artikel?project=<?php echo $post->ID; ?>">nieuw artikel</a>
                </li>
                <li>
                    <a href="<?php echo get_site_url();?>/nieuw-zoekertje?project=<?php echo $post->ID; ?>">nieuw zoekertje</a>
                </li>
                <li>
                    <a href="<?php echo get_site_url();?>/nieuw-event?project=<?php echo $post->ID; ?>">nieuw event</a>
                </li>
            </ul>
             <section class="clear"></section>
        </section>
		<section class="main">
		
	<?php

	while(have_posts()) : the_post();
		$meta = get_post_meta($post->ID);
		$projectStreet = $meta['_projectStreet'][0];
		$projectCity = $meta['_projectCity'][0];
		$projectZipcode = $meta['_projectZipcode'][0];
        $projectLocation = $projectStreet . " " . $projectCity;
		$adresInfo = $projectStreet . " " . $projectCity . " " . $projectZipcode;
		?>

		<script>
        // If image is set.
		var header = document.getElementsByClassName("project-image")[0];
        header.style["background-image"] = "url('<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>')";

        // If no image is set, show Street View.
        function initialize()
        {
            getCoordinates('<?php echo $projectLocation; ?>', function(coords)
            {
                var myLatLng = new google.maps.LatLng(coords[0], coords[1]);

                var panoramaOptions = 
                {
                    position : myLatLng,
                    pov : 
                    {
                        heading : 165,
                        pitch : 0
                    },
                    zoom : 1
                };

                var myPano = new google.maps.StreetViewPanorama(
                    document.getElementById('project-image'),
                    panoramaOptions
                );

                myPano.setVisible(true);
            });           
        }
        
        <?php
            if(!get_post_thumbnail_id($post->ID))
            {
                ?>
                    google.maps.event.addDomListener(window, 'load', initialize);
                <?php
            }
        ?>
		var info = document.getElementsByClassName("project-info")[0];
		var h1 = document.createElement("h1");
		h1.innerHTML = "<?php the_title(); ?>";
           // h1.className = "vertical-align";
		info.appendChild(h1);


		</script>

		<!--
		<?php the_post_thumbnail(); ?>
		<h1><?php the_title(); ?></h1>
		<p>Hoofdbericht: <?php the_content() ?></p>
		<p>Straat: <?php echo $projectStreet; ?></p>
		<p>Gemeente: <?php echo $projectCity; ?></p>
		<p>Postcode: <?php echo $projectZipcode; ?></p>
		-->

		<?php 
            $args = array(
				'posts_per_page' => 9,
				'post_type' => array('zoekertjes','events','post'),
                'meta_key' => '_parentProjectId',
                'meta_value'=>$id,
                'meta_compare'=>'='
                
			     );
            $my_query = new WP_Query($args);

			while($my_query->have_posts()) : $my_query->the_post();
                switch($post->post_type){
                    case "events":{
                        $meta = get_post_meta($post->ID);
                        $eventTime = $meta['_eventTime'][0];
                        $eventLocation = $meta['_eventLocation'][0];
                        $eventEndTime = $meta['_eventEndTime'][0];

                        $day = explode("/", $eventTime)[1];
                        $monthNumber = explode("/", $eventTime)[0];
                        $month = getMonth($monthNumber);
		?>

               
                            <section class="list-item normalize-text <?php echo $post->post_type;?>">
                                <section class="event-calendar">
                                    <h1><?php echo $day; ?></h1>
                                    <h2><?php echo $month; ?></h2>
                                </section>
                                <section class="event-content">
                                    <h1><?php the_title(); ?></h1>
                                    <p><strong>Tijdstip: </strong><?php echo $eventTime . " - " . $eventEndTime; ?></p>
                                    <p><strong>Locatie: </strong><?php echo $eventLocation; ?></p>
                                    <p><strong>Meer info: </strong><?php echo excerpt(50); ?></p>
                                     
                                </section>
                                <a class="view-item" href="<?php the_permalink(); ?>">Bekijk event</a>
                            </section>
                        

		<?php
                    }
                    break;
                    case "zoekertjes":{
                        $meta = get_post_meta($post->ID);
                        $adLocation = $meta['_adLocation'][0];
                        $adPrice = $meta['_adPrice'][0];
                    ?>

                   
                        <section class="list-item normalize-text <?php echo $post->post_type;?>">
                            <h1><?php the_title(); ?></h1>
                            <p><strong>Beschrijving: </strong><?php the_content(); ?></p>
                            <p><strong>Locatie: </strong><?php echo $adLocation; ?></p>
                            <p><strong>Prijs: </strong><?php echo $adPrice; ?></p>
                             <a class="view-item" href="<?php the_permalink(); ?>">Bekijk zoekertje</a>
                        </section>
                    

		<?php
                    
                    }break;
                    case "post":{
                        ?>
                        <section class="list-item normalize-text <?php echo $post->post_type; ?>">
					<h1><?php echo the_title();?></h1>
                    <?php echo the_excerpt();
?>				 <a class="view-item" href="<?php the_permalink(); ?>">Lees meer</a></section><?php
                    }break;
                    default: break;
                }
			?>

				

			<?php
			endwhile;
            wp_reset_postdata();
            ?>
		</section>
		<section class="sidebar">
            <section class="search normalize-text">
                <?php
				if(is_user_logged_in() && $post->post_author != get_current_user_id())
				{
					?>
						<form method="POST" id="projectMemberForm">
							<input id="projectMemberId" name="projectMemberId" type="hidden" value="<?php echo get_current_user_id(); ?>" />
							<input id="projectMemberProjectId" name="projectMemberProjectId" type="hidden" value="<?php echo $post->ID; ?>" />
							<input id="projectMemberSubmit" name="projectMemberSubmit" type="submit" value="Inschrijven" class="form-button" />
						</form>
                    <hr/>
					<?php
				}
                else if(is_user_logged_in()){
                    ?>
                    <a  class="wide-button" href="<?php echo get_site_url() ?>/projectleden?project=<?php echo $post->ID; ?>">Leden</a>
                    <a  class="wide-button" href="<?php echo get_site_url() ?>/bewerk-project?project=<?php echo $post->ID; ?>">Beheren</a>
                <hr />
                <?php
                }

			?>
                
                <section class="form-line">
                    <input class="checkbox js-switch" id="zoekertjes" onchange="apply_filter('z')" type="checkbox" name="zoekertjes" value="1" checked/>
                    <label class="checkbox-label">Toon zoekertjes</label>
                </section>
                <section class="form-line">
                    <input class="checkbox js-switch" id="events" onchange="apply_filter('e')" type="checkbox" name="events" value="1" checked/>
                    <label class="checkbox-label">Toon events</label> 
                </section>
                <section class="form-line">
                    <input class="checkbox js-switch" id="post" onchange="apply_filter('a')" type="checkbox" name="artikels" value="1" checked/>
                    <label class="checkbox-label">Toon artikels</label> 
                </section>
                
                <hr />
                
                <section class="form-line">
                    <h3>Beschrijving</h3>
                        <?php the_content(); ?>
                </section>
                
                <section class="form-line">
        
                   <
                </section>
            </section>
            
        </section>

		<?php
	endwhile;
	?>

	</section>
	<section class="clear"></section>
	<!----switches--->
<script>
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function(html) {
        var switchery = new Switchery(html, {color:'#00cd00', size:'small'});
    });
    $('.events').css('height', $('.events').height());
    function apply_filter(type){
        console.log(type);
        var el;
        if(type=='z'){
            el ="zoekertjes";
        }
        if(type=='e'){
            el="events";
        }
        if(type=='a'){
            el="post";
        }
        if($("#"+el).prop("checked")){
                
                $('.'+el).animate(
	    		{
				    height: 'toggle',
                    padding: '4%',
                    margin:'5% 3%',
				}, 500);
            }
            else {
                $('.'+el).animate(
	    		{
                    padding:'0% 4%',
				    height: 'toggle',
                    margin:'0% 3%',
				}, 500);
            }
    }
    
    
    
        
</script>
	<?php
	get_footer();
?>