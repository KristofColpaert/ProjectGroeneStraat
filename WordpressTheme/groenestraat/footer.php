    <footer>
        <section class="footer-partners">
            <div id="partners-slider">
                <?php
                    global $wpdb;
                    $results = $wpdb->get_results( "SELECT * FROM wp_partners", ARRAY_A);
                    foreach($results as $result)
                    {
                        ?>
                            <div class="item">
                                <a target="_blank" href="<?php echo $result["URL"]; ?>">
                                    <div style="background-image:url('<?php echo $result["URLImage"]; ?>');background-size:cover;width:auto;height:185px;-webkit-filter: grayscale(1);filter: grayscale(1);"></div>
                                </a>
                            </div>
                        <?php
                    }
                ?>
            </div>       
        </section>
        <section class="footer-content">
            <section class="footer-sitemap">
                <h1>Sitemap</h1><br/><br/>
                <ul>
                    <?php
                        $pages = get_pages(); 
                        foreach($pages as $page) 
                        {
                            echo '<li><a href="' . get_page_link( $page->ID ) . '">' . $page->post_title . '</a></li>';
                        }
                    ?>
                </ul>
            </section>
            <section class="footer-logo">
                <img src="<?php bloginfo('template_directory'); ?>/img/logo_large.png" width="500" height="500" alt="" title="" />
                <p>© Copyright 2015. Meer info komt hier terecht.</p>
            </section>
            <section class="footer-links">
            <h1>Social</h1><br/><br/>
                <a class="twitter-timeline"  href="https://twitter.com/hashtag/groenestraat" data-widget-id="608932741124648960">#groenestraat Tweets</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </section>
            <section class="clear"></section>
        </section>
    </footer>
    <script>
        $(document).ready(function() 
        { 
          $("#partners-slider").owlCarousel({
                autoPlay: 5000,
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
    </script>
<?php wp_footer();?>
</body>
</html>