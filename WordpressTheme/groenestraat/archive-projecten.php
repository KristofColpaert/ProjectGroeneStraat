<?php get_header(); ?>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDC5KoyRfBkse2foN9chLRWQuo0kC61qXI"></script>
	<script>
		var globalIndexes = [];
		var globalCoordinates = [];

		//Google StreetView Coordinates
        //Get coordinates of a location.
        geocoder = new google.maps.Geocoder();
        window.getCoordinates = function(address, callback)
        {
            var coordinates;
            geocoder.geocode({ address: address }, function (results, status)
            {
            	if(results[0])
            	{
            		coords_obj = results[0].geometry.location;
                	coordinates = [coords_obj.A, coords_obj.F];
                	callback(coordinates);
            	}
                else
                {
                	callback('none');
                }
            })
        }
	</script>
	<section class="container">
	<section class="sub-menu">
		<ul>
			<li><a href="<?php echo get_site_url(); ?>/nieuw-project">Nieuw project</a></li>
			<li><a href="#">Mijn projecten</a></li>
		</ul>
		<section class="options">
			<form action="<?php $_SERVER['PHP_SELF']; ?>" method="GET">
				<input type="text" name="zoekveld" class="textbox" placeholder="Zoeken op projectnaam"><input type="submit" class="form-button" value="zoeken" name="zoeken">
			</form>
		</section>
	</section>

	<?php

	global $post;
	$index = 0;
	$keyword = '';

	if(isset($_GET['zoeken']))
	{
		if(isset($_GET['zoekveld']))
		{
			$keyword = $_GET['zoekveld'];
		}
		else
		{
			$keyword = 'phrase';
		}
	}

	$orig_query = $my_query;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$my_query = new WP_Query(
		array(
			'post_type' => 'projecten',
			'paged' => $paged,
			's' => $keyword,
			'posts_per_page' => 9
			)
		);

	while($my_query->have_posts()) : $my_query->the_post();

		$meta = get_post_meta($post->ID);
		$projectStreet = $meta['_projectStreet'][0];
		$projectCity = $meta['_projectCity'][0];
		$projectZipcode = $meta['_projectZipcode'][0];
		$projectLocation = $projectStreet . ' ' . $projectCity;

		?>
			<a href="<?php the_permalink(); ?>">
				<section class="project-item">				
					<?php 
						if (has_post_thumbnail()) 
						{ 
							?>
								<script> 
									var e = document.getElementsByClassName("project-item")['<?php echo $index; ?>'];
									e.style["background-image"] = "url('<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>')";
									e.style["background-size"] = "cover";
								</script>
							<?php
						} 
						else
						{
							?>
							<script> 
								var e = document.getElementsByClassName("project-item")['<?php echo $index; ?>'];
								globalIndexes.push(<?php echo $index; ?>);
								
								getCoordinates('<?php echo $projectLocation; ?>', function(coords)
								{
									globalCoordinates.push([]);
									globalCoordinates[globalCoordinates.length - 1].push(coords);
									if(globalCoordinates.length == globalIndexes.length)
									{
										test();
									}
								});
							</script>
							<?php
						}
						?>
						<section class="info">
							<h1><?php the_title(); ?></h1>
							<p><?php echo $projectCity . " " . $projectZipcode; ?></p>
						</section>
				</section>
					<?php
						$index++;
					?>
			</a>
	<?php
	
	endwhile;

	if (!$my_query->have_posts())
	{
		?>
			<section class="no-post">
				<h2>Geen zoekresultaten op: <?php echo $keyword; ?></h2>
			</section>
		<?php
	}

	?>

	</section>
	<section class="clear"></section>
	
	<section class="navigate-menu">
		<?php

		previous_posts_link();
		next_posts_link();
		$my_query = $orig_query;

		?>
	</section>
	<script>
		
		function test()
		{
			for(i = 0; i < globalIndexes.length; i++)
			{
				var index = globalIndexes[i];
				var coords = globalCoordinates[i];

				if(coords[0] != 'none')
				{
					var coord1 = coords[0][0];
					var coord2 = coords[0][1];
					var url = 'https://maps.googleapis.com/maps/api/streetview?key=AIzaSyChwJePvaLHTx1xlGAFUHrmjkPWKpVyGVA&size=800x800&location=' + coord1 + ',' + coord2 + '&fov=90&heading=235&pitch=10';
					var e = document.getElementsByClassName("project-item")[index];
					e.style["background-image"] = "url('" + url + "')";
					e.style["background-size"] = "cover";
				}
			}
		}
	</script>	
<?php get_footer(); ?>