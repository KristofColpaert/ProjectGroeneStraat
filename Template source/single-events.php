<?php
/* 
 * Template to display a single event.
 *
 * @package Groenestraat
 * @subpackage groenestraat
 * @since Groenestraat 1.0
*/

get_header(); ?>
<article>
    <?php
        global $post;

        $mypost = array('post_type' => 'events', 'p' => $post->ID);
        $loop = new WP_Query($mypost);

        while($loop->have_posts()) : $loop->the_post();
            echo the_title() . '<br />';

            $meta = get_post_custom($mypost->ID);
            $eventTime = $meta['_eventTime'][0];
            $eventLocation = $meta['_eventLocation'][0];
            $eventMoreInfo= $meta['_eventMoreInfo'][0];

            echo "<strong>Datum: </strong>" . $eventTime . "<br />";
            echo "<strong>Locatie: </strong>" . $eventLocation . "<br />";
            echo "<strong>Meer info: </strong>" . $eventMoreInfo . "<br />";
        endwhile;
    ?>
</article>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>