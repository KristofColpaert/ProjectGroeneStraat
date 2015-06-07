<?php get_header(); ?>
    
    <section class="header-image"></section>
	<section class="recent">
        <section class="recent-item">
            <h1>Projecten</h1>
            <p>Projecten brengen mensen samen die werken naar een speciefiek doel, zoals werken naar een specifiek doel, zoals bijvoorbeeld een gezamelijke moestijn op het einde van hun straat</p>
            <a href="<?php echo get_site_url(); ?>/projecten" class="button">Projecten &#62;</a>
        </section>
        <section class="recent-item">
            <h1>Artikels</h1>
            <p>Lees &amp; schrijf artikels (on)afhankelijk van een bepaald project. Deel eigen ervaringen &amp; help ons naar een groenere toekomst.</p>
            <a href="<?php echo get_site_url(); ?>/artikels" class="button">Artikels &#62;</a>
            </section>
        <section class="recent-item">
            <h1>Events</h1>
            <?php
                    $my_query = new WP_Query(array('post_type' => 'events', 'posts_per_page' => 3));
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
            <br/><br/>
            <a href="<?php echo get_site_url(); ?>/events" class="button">Events &#62;</a>
        </section>
    </section>
    <section class="clear"></section>
    <section class="home-title">
        <h1>Artikels</h1>
    </section>
    <section class="recent-artikel">
        <section class="artikel-side">
            <h1></h1><p></p>
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
        var length = document.getElementsByClassName('artikel-item').length;
        var data = 
        [
            <?php
                $my_query = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 4));
                while($my_query->have_posts()) : $my_query->the_post();
            ?>
            {
                "titel": "<?php the_title(); ?>",
                "info": "<?php echo excerpt(40); ?>",
                "image": "/img/artikel-example1.png",
                "url": "<?php the_permalink(); ?>"
            },
            <?php
                endwhile;
            ?>
            {"fix": "fix"}
        ];

        parseData(data);

        var index = 0;
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
                e.getElementsByTagName('h1')[0].innerHTML = data[i].titel;
                e.getElementsByTagName('p')[0].innerHTML = data[i].info;   
            }
        }
 
        function replace(newId)
        {
            index = newId;
            for(var i=0;i<length;i++)
            {
                var currentItem = document.getElementsByClassName('artikel-item')[i];
                var chosen = document.getElementsByClassName('artikel-item')[newId];

                if(currentItem.hasAttribute("id"))
                {
                    currentItem.removeAttribute('id');
                    chosen.setAttribute('id', 'active');

                    var info = document.getElementsByClassName('artikel-side')[0];
                    info.getElementsByTagName('h1')[0].innerHTML = data[newId].titel;
                    info.getElementsByTagName('p')[0].innerHTML = data[newId].info;
                    document.getElementsByClassName('recent-artikel')[0].style["background-image"] = "url('<?php echo bloginfo('template_directory'); ?>" + data[newId].image + "')";
                }
            }
        }

        document.getElementsByClassName('artikel-item')[0].addEventListener('click', function() { replace(0); });
        document.getElementsByClassName('artikel-item')[1].addEventListener('click', function() { replace(1); });
        document.getElementsByClassName('artikel-item')[2].addEventListener('click', function() { replace(2); });
        document.getElementsByClassName('artikel-item')[3].addEventListener('click', function() { replace(3); });
        
        //fancy slider by koen van crombrugge

    </script>
    <section class="home-title">
        <h1>Projecten</h1>
    </section>
    <section class="recent-projects">
        <div id="recent-projects-slider">
            <?php
                $my_query = new WP_Query(array('post_type' => 'projecten', 'posts_per_page' => 10));
                while($my_query->have_posts()) : $my_query->the_post();
            ?>
                <a href="<?php the_permalink(); ?>">
                    <div class="item fade">
                        <div class="overlay">
                            <h1><?php the_title(); ?></h1>
                        </div>
                        <img src="<?php bloginfo('template_directory'); ?>/img/owl1.jpg" alt="recent-project">
                    </div>
                </a>
            <?php
                endwhile;
            ?>
        </div>        
    </section>
    <section class="home-title">
        <h1>Zoekertjes</h1>
    </section>
    <section class="recent-zoekertjes">
        <?php
            $my_query = new WP_Query(array('post_type' => 'zoekertjes', 'posts_per_page' => 3));

            while($my_query->have_posts()) : $my_query->the_post();
                $meta = get_post_meta($post->ID);
                $adLocation = $meta['_adLocation'][0];
                $adPrice = $meta['_adPrice'][0];
        ?>
            <section class="zoekertje-item">
                <h3><?php the_title(); ?></h3>
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
<?php get_footer(); ?>