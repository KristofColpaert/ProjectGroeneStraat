<?php 
/*
Template Name: Artikels
*/
get_header();
 
?>
    <section class="container normalize-text">
    <section class="sub-menu">
        <ul>
            <li><a href="<?php echo get_site_url(); ?>/nieuw-artikel">Nieuw artikel</a></li>
            <li><a href="<?php echo get_site_url(); ?>/mijn-artikels">Mijn artikels</a></li>
        </ul>
        <section class="options">
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="GET">
                <input type="text" name="zoekveld" class="textbox" placeholder="Zoeken op artikel">
                <input type="submit" class="form-button" value="zoeken" name="zoeken">
            </form>
        </section>
    </section>

    <?php

    global $post;
    $keyword = '';
    $categories = array(); 

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

            if(isset($_GET['categorie']))
            {
                $categories = $_GET['categorie'];
            }
        }

        if($keyword != '')
        {
            if(count($categories) > 0)
            {
                ?>
                    <section class="main" data="artikels;1;<?php echo $keyword;?>;<?php for($i = 0; $i < (count($categories)); $i++) { if($i == count($categories) - 1) echo $categories[$i]; else echo $categories[$i] . ';'; }?>">
                <?php
            }

            else
            {
                ?>
                    <section class="main" data="artikels;1;<?php echo $keyword; ?>">
                <?php
            }
        }

        else if(count($categories) > 0)
        {
            ?>
                <section class="main" data="artikels;1;none;<?php for($i = 0; $i < (count($categories)); $i++) { if($i == count($categories) - 1) echo $categories[$i]; else echo $categories[$i] . ';'; }?>">
            <?php
        }

        else
        {
            ?>
                <section class="main" data="artikels;1">
            <?php
        }

        $projectartikels_id = get_cat_ID('projectartikels');
        $orig_query = $my_query;
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $my_query = new WP_Query(
            array(
                'post_type' => 'post',
                'posts_per_page' => 9,
                's' => $keyword,
                'paged' => $paged,
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
                <br />
                <p><?php the_excerpt(); ?></p>
                <a class="view-item" href="<?php echo get_permalink($post->ID); ?>">Lees meer</a>
            </section>
        <?php

        endwhile;

        if (!$my_query->have_posts())
        {
            if($keyword != "")
            {
                ?>
                    <section class="list-item">
                        <h2>Geen zoekresultaten op: <?php echo $keyword; ?></h2>
                    </section>
                <?php
            }
            else
            {
                ?>
                    <section class="list-item">
                        <h2>Er werden geen artikels gevonden.</h2>
                    </section>
                <?php
            }
        }
        ?>

    </section>

    <section class="sidebar">
        <section class="search">
            <h1>Zoeken</h1>
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="GET">
                <p>
                    <input type="text" name="zoekveld" class="textbox" placeholder="Zoeken op artikel">
                    <select class="textbox combobox" id="articleCategories" name="categorie[]" multiple size="5">
                        <?php
                            $categories = get_categories(
                                array(
                                    'type' => 'post',
                                    'orderby' => 'name',
                                    'order' => 'ASC',
                                    'hide_empty' => 0
                                )
                            );

                            if(!empty($categories))
                            {
                                foreach($categories as $category)
                                {
                                    if($category->name != 'Projectartikels' && $category->name != 'Projectzoekertjes' && $category->name != 'Projectevents')
                                    {
                                        ?>
                                            <option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
                                        <?php
                                    }
                                }
                            }
                        ?>
                    </select>
                    <input type="submit" class="form-button" value="zoeken" name="zoeken">
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