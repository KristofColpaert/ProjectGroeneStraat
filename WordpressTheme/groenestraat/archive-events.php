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
			<li><a href="#">Nieuw event</a></li>
			<li><a href="#">Mijn events</a></li>
		</ul>
	</section>
	<section class="main">

	<?php
	global $post;
	$keyword = '';

		if(isset($_GET['submit']))
		{
			if(isset($_GET['search']))
			{
				$keyword = $_GET['search'];
			}
			else
			{
				$keyword = 'phrase';
			}
		}

		$args = array(
			'posts_per_page' => 6,
			'post_type' => 'events',
			's' => $keyword
		);

		$my_query = new WP_Query($args);
		//print_r($my_query);

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
			<section class="event-item">
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

		if ($my_query->have_posts()){}
			else
			{
				?> 
				<section class="event-item">
					<h2>Geen zoekresultaten op: <?php echo $keyword; ?></h2>
				</section>
				<?php
			}

	previous_posts_link();
	next_posts_link();

	?>
		
	</section>
	<section class="sidebar">
		<section class="search">
			<h1>Zoeken</h1>
			<form action="<?php $_SERVER['PHP_SELF']; ?>" method="GET">
				<p>
					<input type="text" name="search" value=""><input type="submit" value="submit" name="submit">
				</p>
			</form>
		</section>
	</section>
	<section class="clear"></section>
	

<?php get_footer(); ?>