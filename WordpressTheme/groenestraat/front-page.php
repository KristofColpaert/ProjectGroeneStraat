<?php get_header(); ?>

    <section class="header-image"></section>
    <section class="about">
        <h1>Wat is Groene Straat?</h1><br/><br/>
        <a href="<?php echo get_site_url(); ?>/about" class="button">Over ons &#62;</a>
    </section>
	<section class="recent">
        <section class="recent-item">
            <h1>Projecten</h1>
            <section class="wrapper">
                <p class="set-height">Projecten brengen mensen samen die werken naar een speciefiek doel, zoals werken naar een specifiek doel, zoals bijvoorbeeld een gezamelijke moestijn op het einde van hun straat</p>
            </section>
            <a href="<?php echo get_site_url(); ?>/projecten" class="button">Projecten &#62;</a>
        </section>
        <section class="recent-item">
            <h1>Artikels</h1>
            <section class="wrapper">
                <p class="set-height">Lees &amp; schrijf artikels (on)afhankelijk van een bepaald project. Deel eigen ervaringen &amp; help ons naar een groenere toekomst.</p>
            </section>
            <a href="<?php echo get_site_url(); ?>/artikels" class="button">Artikels &#62;</a>
        </section>
        <script>
            for(var i=0;i<document.getElementsByClassName('set-height').length;i++) {
                document.getElementsByClassName('set-height')[i].style['position'] = 'relative';
                document.getElementsByClassName('set-height')[i].style['top'] = '' + (220/2-(document.getElementsByClassName('set-height')[i].offsetHeight/2)-15) +'px';
            }
        </script>
        <section class="recent-item">
            <h1>Events</h1>
            <section class="wrapper">
            <?php
                    $projectevents_id = get_cat_ID('projectevents');
                    $my_query = new WP_Query(array('post_type' => 'events', 'posts_per_page' => 3, 'cat' => '-' . $projectevents_id));
                    while($my_query->have_posts()) : $my_query->the_post();
                        $meta = get_post_meta($post->ID);
                        $eventTime = $meta['_eventTime'][0];
                        $eventEndTime = $meta['_eventEndTime'][0];
                        $day = explode("/", $eventTime)[1];
            ?>
                <a href="<?php the_permalink(); ?>">
                    <section class="calender">
                        <section class="calender-image">
                            <em><?php echo $day; ?></em>
                            <img src="<?php bloginfo('template_directory'); ?>/img/calendar.png" width="65" height="55" alt="" title="" />
                        </section>
                        <section class="calender-content">
                            <p><?php the_title(); ?> <br/><strong><?php echo $eventTime . " - " . $eventEndTime; ?></strong></p>
                        </section>
                    </section>
                </a>
            <?php
                endwhile;
            ?>
            </section>
            <a href="<?php echo get_site_url(); ?>/events" class="button">Events &#62;</a>
        </section>
    </section>
    <section class="clear"></section>
    <?php
    if(is_user_logged_in()) 
    {
        ?>
            <script>
                document.getElementsByClassName('recent')[0].style.display = 'none';
                document.getElementsByClassName('about')[0].style.display = 'none';
                document.getElementsByClassName('header-image')[0].className = document.getElementsByClassName('header-image')[0].className + 'header-image-small';
            </script>
        <?php
    }
    ?>
    <section class="home-title">
        <h1>Artikels</h1>
    </section>
    <section class="recent-artikel">
        <section class="artikel-side">
            <h1 id="link"></h1><p></p>
        </section>
	    <section class="artikel-info">
	    	<section class="artikel-item" id="active">
	    		<h1></h1><p></p>
	    	</section>
	    	<section class="artikel-item">
	    		<h1></h1><p></p>
	    	</section>
	    	<section class="artikel-item">
	    		<h1></h1><p></p>
	    	</section>
	    	<section class="artikel-item">
	    		<h1></h1><p></p>
	    	</section>
	    </section>
	</section>
    <script>
        var data = 
        [
            <?php
                $projectartikels_id = get_cat_ID('projectartikels');
                $my_query = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 4, 'cat' => '-' . $projectartikels_id));
                while($my_query->have_posts()) : $my_query->the_post();
            ?>
            {
                "titel": "<?php the_title(); ?>",
                "info": "<?php echo excerpt(40); ?>",
                "image": "<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>",
                "url": "<?php the_permalink(); ?>"
            },
            <?php
                endwhile;
            ?>
            {"fix": "fix"}
        ];

        parseData(data);

        var index = 0;
        replace(index);

        var timer = setInterval(function () { 
            replace(index);
            index++;
            if(index > 3) { index = 0; }
        }, 3000);

        function parseData(data) 
        {
            for(var i=0;i<(data.length)-1;i++)
            {
                var e = document.getElementsByClassName('artikel-item')[i];
                e.getElementsByTagName('h1')[0].innerHTML = splitter(data[i].titel, 10);
                e.getElementsByTagName('p')[0].innerHTML = splitter(data[i].info, 140);  
            }
        }

        function splitter(content, maxChar)
        {
            var tekens = 0;
            var newContent = zin = '';

            for(var i=0;i<content.length;i++)
            {
                if(i > maxChar)
                {
                    for(var s=maxChar;s<content.length;s++)
                    {
                        if(content.charAt(s) == ' ')
                        {
                            newContent += content.substring(0, s);
                            return newContent + ' ...';
                        }
                    }
                }
            }

            return content;
        }

        function replace(newId)
        {
            index = newId;
            for(var i=0;i<(data.length)-1;i++)
            {
                var currentItem = document.getElementsByClassName('artikel-item')[i];
                var chosen = document.getElementsByClassName('artikel-item')[newId];

                if(currentItem.hasAttribute("id"))
                {
                    currentItem.removeAttribute('id');
                    chosen.setAttribute('id', 'active');
                    var info = document.getElementsByClassName('artikel-side')[0];
                    info.getElementsByTagName('h1')[0].innerHTML = splitter(data[newId].titel, 250);
                    var recentArtikel = document.getElementsByClassName('recent-artikel')[0];
                    recentArtikel.style["background-image"] = "url('" + data[newId].image + "')";
                    recentArtikel.style["background-size"] = "cover";
                    document.getElementById('link').addEventListener('click', function(){ document.location = data[newId].url; });
                }
            }
        }

        document.getElementsByClassName('artikel-item')[0].addEventListener('click', function() { replace(0); });
        document.getElementsByClassName('artikel-item')[1].addEventListener('click', function() { replace(1); });
        document.getElementsByClassName('artikel-item')[2].addEventListener('click', function() { replace(2); });
        document.getElementsByClassName('artikel-item')[3].addEventListener('click', function() { replace(3); });

    </script>
    <section class="home-title">
        <h1>Projecten</h1>
    </section>
    <section class="recent-projects">
        <div id="recent-projects-slider">
            <?php
                $index = 0;
                $my_query = new WP_Query(array('post_type' => 'projecten', 'posts_per_page' => 10));
                while($my_query->have_posts()) : $my_query->the_post();
            ?>
                <a href="<?php the_permalink(); ?>">
                    <div class="item fade">
                        <div class="overlay">
                            <h1 class="projectname"></h1>
                            <script>
                                document.getElementsByClassName('projectname')[<?php echo $index ?>].innerHTML = splitter('<?php the_title(); ?>', 30);
                            </script>
                        </div>
                        <?php 
                            if(has_post_thumbnail())
                            {
                                ?>
                                    <div style="background-image:url('<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>');background-size:cover;width:auto;height:200px"></div>
                                <?php
                            }
                            else
                            {
                                $projectStreetViewThumbnail = get_post_meta($post->ID, '_projectStreetViewThumbnail')[0];
                                ?>
                                    <div style="background-image:url('<?php echo $projectStreetViewThumbnail; ?>');background-size:cover;width:auto;height:200px"></div>
                                <?php
                            }
                        ?>
                    </div>
                </a>
            <?php
                $index++;
                endwhile;
            ?>
        </div>        
    </section>
    <section class="home-title">
        <h1>Zoekertjes</h1>
    </section>
    <section class="recent-zoekertjes">
        <?php
            $projectzoekertjes_id = get_cat_ID('projectzoekertjes');
            $my_query = new WP_Query(array('post_type' => 'zoekertjes', 'posts_per_page' => 3,'cat' => '-' . $projectzoekertjes_id));

            while($my_query->have_posts()) : $my_query->the_post();
                $meta = get_post_meta($post->ID);
                $adLocation = $meta['_adLocation'][0];
                $adPrice = $meta['_adPrice'][0];
        ?>
            <section class="zoekertje-item">
                <h3><?php the_title(); ?></h3><br/><br/>
                <p><?php echo excerpt(20); ?></p>
                <div>
                    <p>Locatie: <?php echo $adLocation; ?></p>
                    <a href="<?php the_permalink(); ?>">Bekijk</a>
                </div>
            </section>
        <?php
            endwhile;
        ?>
        <section class="clear"></section>
    </section>
    <script>
        $(document).ready(function() 
        { 
          $("#recent-projects-slider").owlCarousel({
                items : 5,
                itemsDesktop : [1199,3],
                itemsDesktopSmall : [979,3],
                navigation : false,
                responsive: true,
                autoHeight : false,
                pagination : false,
                paginationNumbers: false,
                stopOnHover: true,
                mouseDrag: true,
                touchDrag: true
          });
        });
            
        $('#recent-projects-slider .item').hover(
                function () { $(this).find('.overlay').fadeIn(300); },
                function () { $(this).find('.overlay').fadeOut(200); }
        );  
    </script>
<?php get_footer(); ?>