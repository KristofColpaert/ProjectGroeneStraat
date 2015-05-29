<?php
 /*Template Name: New Template
 */

get_header(); ?>
<div id="primary">
    <div id="content" role="main">
    <?php
    global $post;
    $mypost = array( 'post_type' => 'ads', 'p' => $post->ID,);
    $loop = new WP_Query( $mypost );
    ?>
    <?php while ( $loop->have_posts() ) : $loop->the_post();?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <!-- Display Title and Author Name -->
                <strong>Title: </strong><?php the_title(); ?><br />

            <?php
                $meta = get_post_custom($mypost->ID);
                $addLocation = $meta['_zoekertjeLocatie'][0];
                $addBeschrijving= $meta['_zoekertjeBeschrijving'][0];

                echo "<strong>Locatie: </strong>" . $addLocation . "<br />";
                echo "<strong>Beschrijving: </strong>" . $addBeschrijving . "<br />";
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