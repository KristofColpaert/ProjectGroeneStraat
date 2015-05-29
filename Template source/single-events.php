<?php
 /*Template Name: New Template
 */

get_header(); ?>
<div id="primary">
    <div id="content" role="main">
    <?php
    $mypost = array( 'post_type' => 'events',);
    $loop = new WP_Query( $mypost );
    ?>
    <?php while ( $loop->have_posts() ) : $loop->the_post();?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <!-- Display Title and Author Name -->
                <strong>Title: </strong><?php the_title(); ?><br />

			<?php
				$meta = get_post_custom($mypost->ID);

				//$eventName = $meta['_eventName'][0];
				$eventTime = $meta['_eventTime'][0];
				$eventLocation = $meta['_eventLocation'][0];
				$eventMoreInfo= $meta['_eventMoreInfo'][0];

				//echo "<strong>Name: </strong>" . $eventName . "<br />";
				echo "<strong>Datum: </strong>" . $eventTime . "<br />";
				echo "<strong>Locatie: </strong>" . $eventLocation . "<br />";
				echo "<strong>Meer info: </strong>" . $eventMoreInfo . "<br />";
			?>

            </header>
            <!-- Display movie review contents -->
            <div class="entry-content"><?php the_content(); ?></div>
        </article>
 
    <?php endwhile; ?>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>