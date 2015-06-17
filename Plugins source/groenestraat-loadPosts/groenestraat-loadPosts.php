<?php
	/*
		Plugin Name: Groenestraat Load Posts
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin zorgt ervoor dat posts op de projectpage correct ingeladen worden via Ajax.
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert, Koen Van Crombrugge en Vincent De Ridder
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	/*
		Add action
	*/

	add_action('wp_ajax_nopriv_load_new_posts', 'load_new_posts');
	add_action('wp_ajax_load_new_posts', 'load_new_posts');

	/*
		Plugin methods
	*/

	function getMonths($var)
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

	function load_new_posts()
	{
		if(isset($_POST['pageType']) && isset($_POST['page']) && isset($_POST['projectId']))
		{
			$pageType = $_POST['pageType'];

			if($pageType == 'singleProjecten')
			{
				load_single_projectent_posts();
			}

			else if($pageType == 'projecten')
			{
				load_projecten_posts();
			}

			else if($pageType == 'events')
			{
				load_events_posts();
			}

			else if($pageType == 'artikels')
			{
				load_articles_posts();
			}

			else if($pageType == 'zoekertjes')
			{
				load_zoekertjes_posts();
			}

			else if($pageType == 'profiel')
			{
				load_profiel_posts();
			}
		}
		echo 'no';
		die();
	}

	function load_single_projectent_posts()
	{
		global $post;
		$args = array(
			'posts_per_page' => 9,
			'post_type' => array('zoekertjes','events','post'),
			'paged' => $_POST['page'],
			'meta_key' => '_parentProjectId',
			'meta_value'=> $_POST['projectId'],
			'meta_compare'=> '='
		);

		$customQuery = new WP_Query($args);

		while($customQuery->have_posts()) : $customQuery->the_post();
			switch($post->post_type)
			{
				case "events":
				{
					$meta = get_post_meta($post->ID);
					$eventTime = $meta['_eventTime'][0];
					$eventLocation = $meta['_eventLocation'][0];
					$eventEndTime = $meta['_eventEndTime'][0];

					$day = explode("/", $eventTime)[1];
					$monthNumber = explode("/", $eventTime)[0];
					$month = getMonths($monthNumber);

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

				case "zoekertjes":
				{
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
				}
				break;

				case "post":
				{
					?>
					<section class="list-item normalize-text <?php echo $post->post_type; ?>">
						<h1><?php echo the_title();?></h1>
						<?php echo the_excerpt();?>
						<a class="view-item" href="<?php the_permalink(); ?>">Lees meer</a>
					</section>
					<?php
				}
				break;

				default: break;
			}
		endwhile;
		wp_reset_postdata();
		die();
	}

	function load_projecten_posts()
	{
		global $post;

		$keyword = '';

		if(isset($_POST['search']))
		{
			$keyword = $_POST['search'];
		}

		$index = 9 * $_POST['page'] - 8;

		$args = array(
			'post_type' => 'projecten',
			'paged' => $_POST['page'],
			's' => $keyword,
			'posts_per_page' => 9
		);

		$my_query = new WP_Query($args);

		while($my_query->have_posts()) : $my_query->the_post();

			$meta = get_post_meta($post->ID);
			$projectStreet = $meta['_projectStreet'][0];
			$projectCity = $meta['_projectCity'][0];
			$projectZipcode = $meta['_projectZipcode'][0];

			?>
				<a href="<?php the_permalink(); ?>">
			<?php
				if(has_post_thumbnail())
				{
					?>
						<section class="project-item" style="background-image:url('<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>');background-size:cover">
					<?php
				}

				else
				{
					$projectStreetViewThumbnail = $meta['_projectStreetViewThumbnail'][0];
					?>
						<section class="project-item" style="background-image:url('<?php echo $projectStreetViewThumbnail; ?>');background-size:cover">
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
		wp_reset_postdata();
		die();
	}

	function load_events_posts()
	{
		global $post;

		$keyword = '';

		if(isset($_POST['search']))
		{
			$keyword = $_POST['search'];
		}

		$projectevents_id = get_cat_ID('projectevents');
		$my_query = new WP_Query(
			array(
				'post_type' => 'events',
				'posts_per_page' => 9,
				'paged' => $_POST['page'],
				's' => $keyword,
				'cat' => '-' . $projectevents_id)
			);

		while($my_query->have_posts()) : $my_query->the_post();
			$meta = get_post_meta($post->ID);
			$eventTime = $meta['_eventTime'][0];
			$eventLocation = $meta['_eventLocation'][0];
			$eventEndTime = $meta['_eventEndTime'][0];

			$day = explode("/", $eventTime)[1];
			$monthNumber = explode("/", $eventTime)[0];
			$month = getMonths($monthNumber);
		?>

		<a href="<?php the_permalink(); ?>">
			<section class="list-item">
				<section class="event-calendar">
					<h1><?php echo $day; ?></h1>
					<h2><?php echo $month; ?></h2>
				</section>
				<section class="event-content">
					<h1><?php the_title(); ?></h1>
					<p><strong>Organisator: </strong><?php echo the_author_meta('first_name'); ?> <?php echo the_author_meta('last_name'); ?></p>
					<p><strong>Tijdstip: </strong><?php echo $eventTime . " - " . $eventEndTime; ?></p>
					<p><strong>Location: </strong><?php echo $eventLocation; ?></p>
					<p><strong>Meer info: </strong><?php echo excerpt(50); ?></p>
				</section>
			</section>
		</a>

		<?php

		endwhile;
		die();
	}

	function load_articles_posts()
	{
		global $post;

		$keyword = '';
		$categories = array();

		if(isset($_POST['search']))
		{
			$keyword = $_POST['search'];
		}

		if(isset($_POST['cats']))
		{
			$categories = $_POST['cats'];
		}

        $projectartikels_id = get_cat_ID('projectartikels');
        $my_query = new WP_Query(
            array(
                'post_type' => 'post',
                'posts_per_page' => 9,
                'paged' => $_POST['page'],
                's' => $keyword,
                'category__in' => $categories,
                'category__not_in' => '-' . $projectartikels_id
        	)
        );

        while($my_query->have_posts()) : $my_query->the_post();
        ?>
            <section class="list-item">
                <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                <p>
                    Auteur: <a style="color:black;" class="author-name" href="<?php echo home_url(); ?>/profiel/?userid=<?php echo the_author_meta('ID'); ?>"><?php echo the_author_meta('first_name'); ?> <?php echo the_author_meta('last_name'); ?></a> |
                    Gebubliceerd op: <?php echo get_the_date(); ?> | 
                    Categorieën: 
                    <?php 
                        $categories =  get_the_category();

                        foreach($categories as $category)
                        {
                            ?>
                                <a style="color:black;" class="author-name" href="<?php echo home_url(); ?>/artikels?categorie=<?php echo $category->term_id; ?>"><?php echo $category->name; ?></a> 
                            <?php
                        } 
                    ?>
                </p>
                <p><?php the_excerpt(); ?></p>
                <a class="view-item" href="<?php echo get_permalink($post->ID); ?>">Lees meer</a>
            </section>
        <?php
        endwhile;
        die();
	}

	function load_zoekertjes_posts()
	{
		global $post;

		$keyword = '';

		if(isset($_POST['search']))
		{
			$keyword = $_POST['search'];
		}

		$projectzoekertjes_id = get_cat_ID('projectzoekertjes');
		$my_query = new WP_Query(
			array(
				'post_type' => 'zoekertjes',
				'posts_per_page' => 9,
				'paged' => $_POST['page'],
				's' => $keyword,
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
				<p><strong>Beschrijving: </strong><?php the_content(); ?></p>
				<p><strong>Locatie: </strong><?php echo $adLocation; ?></p>
				<p><strong>Prijs: </strong><?php echo $adPrice; ?> euro</p>
			</section>
		</a>

		<?php

		endwhile;
		die();
	}

	function load_profiel_posts()
	{
		global $post;
		$the_query = new WP_Query(
			array(
            	'posts_per_page' => 9,
				'author' => $userid,
                'paged' => $_POST['page'],
				'post_type' => array('events', 'post', 'zoekertjes'),
				'order' => 'ASC',
				'orderby' => 'date'
			)
		);

		if ($the_query->have_posts()) {
			while ($the_query->have_posts()) : $the_query->the_post();
				switch($post->post_type)
                {
                    case "events":{
                        $meta = get_post_meta($post->ID);
                        $eventTime = $meta['_eventTime'][0];
                        $eventLocation = $meta['_eventLocation'][0];
                        $eventEndTime = $meta['_eventEndTime'][0];

                        $day = explode("/", $eventTime)[1];
                        $monthNumber = explode("/", $eventTime)[0];
                        $month = getProfileMonth($monthNumber);
        		                
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
                    }
                    break;

                    case "post":{
                    	?>
                        	<section class="list-item normalize-text <?php echo $post->post_type; ?>">
        						<h1><?php echo the_title();?></h1>
                                <?php echo the_excerpt(); ?>
                    			<a class="view-item" href="<?php the_permalink(); ?>">Lees meer</a>
                    		</section>
                    	<?php
              	    }
                	break;

                    default: break;
                }        
			endwhile;
			die();
		} 
	}
?>