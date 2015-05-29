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
        $mypost = array('post_type' => 'ads', 'p' => $post->ID);
        $loop = new WP_Query($mypost);

        while($loop->have_posts()) : $loop->the_post;
            echo the_titel() . '<br />';

            $meta = get_post_custom($mypost->ID);
            $adName = $meta['_adName'][0];
            $adLocation = $meta['_adLocation'][0];
            $adDescription = $meta['_adDescription'][0];

            echo "<strong>Straat: </strong>" . $adName . "<br />";
            echo "<strong>Postcode: </strong>" . $adLocation . "<br />";
            echo "<strong>Gemeente: </strong>" . $adDescription . "<br />";
        endwhile;
    ?>
</article>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>