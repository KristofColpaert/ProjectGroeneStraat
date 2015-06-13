<?php 
	get_header();
	
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

	if($keyword != '')
	{	
		?>
			<section class="container" data="projecten;1;<?php echo $keyword; ?>">
		<?php
	}

	else
	{
		?>
			<section class="container" data="projecten;1">
		<?php
	}

	?>
		<section class="sub-menu">
			<ul>
				<li><a href="<?php echo get_site_url(); ?>/nieuw-project">Nieuw project</a></li>
				<li><a href="<?php echo get_site_url(); ?>/mijn-projecten">Mijn projecten</a></li>
			</ul>
			<section class="options">
				<form action="<?php $_SERVER['PHP_SELF']; ?>" method="GET">
					<input type="text" name="zoekveld" class="textbox" placeholder="Zoeken op projectnaam"><input type="submit" class="form-button" value="zoeken" name="zoeken">
				</form>
			</section>
		</section>
	<?php

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

		?>
			<a href="<?php the_permalink(); ?>">
				<section class="project-item">				
					<?php 
						if(has_post_thumbnail()) 
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
							$projectStreetViewThumbnail = $meta['_projectStreetViewThumbnail'][0];
							?>
								<script> 
									var e = document.getElementsByClassName("project-item")['<?php echo $index; ?>'];
									e.style["background-image"] = "url('<?php echo $projectStreetViewThumbnail; ?>')";
									e.style["background-size"] = "cover";
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
	<script>
	</script>
	<section class="clear"></section>
	
	<section class="navigate-menu">
		<?php

		$my_query = $orig_query;

		?>
	</section>	
<?php get_footer(); ?>