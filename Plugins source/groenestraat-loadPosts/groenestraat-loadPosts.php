<?php
	/*
		Plugin Name: Groenestraat Load Posts
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin zorgt ervoor dat posts op de projectpage correct ingeladen worden via Ajax.
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert
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
		global $post;
		if(isset($_POST['page']) && isset($_POST['projectId']))
		{
			$args = array(
				'posts_per_page' => 9,
				'post_type' => array('zoekertjes','events','post'),
				'paged' => $_POST['page'],
                'meta_key' => '_parentProjectId',
                'meta_value'=> $_POST['projectId'],
                'meta_compare'=>'='
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
		echo 'no';
		die();
	}
?>