<?php 

function getMonth($var)
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

?>

<?php get_header(); ?>

	<section class="container">
	<section class="sub-menu">
		<ul>
			<li><a href="<?php echo get_site_url(); ?>/nieuw-event">Nieuw event</a></li>
			<li><a href="<?php echo get_site_url(); ?>/mijn-events">Mijn events</a></li>
		</ul>
		<section class="options">
			<form action="<?php $_SERVER['PHP_SELF']; ?>" method="GET">
				<input type="text" name="zoekveld" class="textbox" placeholder="Zoeken op eventnaam"><input type="submit" class="form-button" value="zoeken" name="zoeken">
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
				<section class="main" data="events;1;<?php echo $keyword; ?>">
			<?php
		}

		else
		{
			?>
				<section class="main" data="events;1">
			<?php
		}

		$orig_query = $my_query;
		$projectevents_id = get_cat_ID('projectevents');
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$my_query = new WP_Query(
			array(
				'post_type' => 'events',
				'posts_per_page' => 9,
				's' => $keyword,
				'paged' => $paged,
				'cat' => '-' . $projectevents_id)
			);

		while($my_query->have_posts()) : $my_query->the_post();
			$meta = get_post_meta($post->ID);
			$eventTime = $meta['_eventTime'][0];
			$eventLocation = $meta['_eventLocation'][0];
			$eventEndTime = $meta['_eventEndTime'][0];

			$day = explode("/", $eventTime)[1];
			$monthNumber = explode("/", $eventTime)[0];
			$month = getMonth($monthNumber);
		?>

		<a href="<?php the_permalink(); ?>">
			<section class="list-item">
				<section class="event-calendar">
					<h1><?php echo $day; ?></h1>
					<h2><?php echo $month; ?></h2>
				</section>
				<section class="event-content">
					<h1><?php the_title(); ?></h1>
					<p><strong>Tijdstip: </strong><?php echo $eventTime . " - " . $eventEndTime; ?></p>
					<p><strong>Location: </strong><?php echo $eventLocation; ?></p>
					<p><strong>Meer info: </strong><?php echo excerpt(50); ?></p>
				</section>
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
					<input type="text" name="zoekveld" class="textbox" placeholder="Zoeken op eventnaam"><input type="submit" class="form-button" value="zoeken" name="zoeken">
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