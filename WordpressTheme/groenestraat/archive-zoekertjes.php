<?php get_header(); ?>

	<section class="container normalize-text">
	<section class="sub-menu">
		<ul>
			<li><a href="<?php echo get_site_url(); ?>/nieuw-zoekertje">Nieuw zoekertje</a></li>
			<li><a href="<?php echo get_site_url(); ?>/mijn-zoekertjes">Mijn zoekertjes</a></li>
		</ul>
		<section class="options">
			<form action="<?php $_SERVER['PHP_SELF']; ?>" method="GET">
				<input type="text" name="zoekveld" class="textbox" placeholder="Zoeken op zoekertje"><input type="submit" class="form-button" value="zoeken" name="zoeken">
			</form>
		</section>
	</section>

	<?php

	global $post;
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
				<section class="main" data="zoekertjes;1;<?php echo $keyword; ?>">
			<?php
		}

		else
		{
			?>
				<section class="main" data="zoekertjes;1">
			<?php
		}

		$orig_query = $my_query;
		$projectzoekertjes_id = get_cat_ID('projectzoekertjes');
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$my_query = new WP_Query(
			array(
				'post_type' => 'zoekertjes',
				'posts_per_page' => 9,
				's' => $keyword,
				'paged' => $paged,
				'cat' => '-' . $projectzoekertjes_id)
			);

		while($my_query->have_posts()) : $my_query->the_post();

			$meta = get_post_meta($post->ID);
			$adLocation = $meta['_adLocation'][0];
			$adPrice = $meta['_adPrice'][0];
		?>

		<a href="<?php the_permalink(); ?>">
			<section class="list-item">
				<h1><?php the_title(); ?></h1>
				<p><strong>Aanbieder: </strong><?php echo the_author_meta('first_name'); ?> <?php echo the_author_meta('last_name'); ?></p>
				<p><strong>Beschrijving: </strong><?php the_excerpt(); ?></p>
				<p><strong>Location: </strong><?php echo $adLocation; ?></p>
				<p><strong>Prijs: </strong>
				<?php 

				if($adPrice == 0)
				{
					echo "gratis";
				}
				else
				{
					echo $adPrice . " euro"; 
				}

				?></p>
			</section>
		</a>

		<?php

		endwhile;

		if (!$my_query->have_posts())
		{
			?>
				<section class="list-item">
					<h2>Geen zoekresultaten op: <?php echo $keyword; ?></h2>
				</section>
			<?php
		}
		
		?>

	</section>

	<section class="sidebar">
		<section class="search">
			<h1>Zoeken</h1>
			<form action="<?php $_SERVER['PHP_SELF']; ?>" method="GET">
				<p>
					<input type="text" name="zoekveld" class="textbox" placeholder="Zoeken op zoekertje"><input type="submit" class="form-button" value="zoeken" name="zoeken">
				</p>
			</form>
		</section>
	</section>

	</section>
	<section class="clear"></section>

	<section class="navigate-menu">
		<?php

		$my_query = $orig_query;

		?>		
	</section>
	
<?php get_footer(); ?>