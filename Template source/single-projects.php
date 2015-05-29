<?php
/* 
 * Template to display a single project.
 *
 * @package Groenestraat
 * @subpackage groenestraat
 * @since Groenestraat 1.0
*/

get_header(); ?>
<article>
    <?php 
        global $post;
        $mypost = array('post_type' => 'projects', 'p' => $post->ID);
        $loop = new WP_Query($mypost);

        while($loop->have_posts()) : $loop->the_post();
            echo the_title() . '<br />';
            echo the_content() . '<br />';

            $meta = get_post_custom($mypost->ID);
            $locationStreet = $meta['_locationStreet'][0];
            $locationZipcode = $meta['_locationZipcode'][0];
            $locationCity = $meta['_locationCity'][0];

            echo "<strong>Straat: </strong>" . $locationStreet . "<br />";
            echo "<strong>Postcode: </strong>" . $locationZipcode . "<br />";
            echo "<strong>Gemeente: </strong>" . $locationCity . "<br />";
        endwhile;
    ?>
</article>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>