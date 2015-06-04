    <footer>
        <section class="footer-partners">
            <div id="partners-slider">
                <div class="item"><img src="<?php bloginfo('template_directory'); ?>/img/partner-example.jpg" alt="partner"></div>
                <div class="item"><img src="<?php bloginfo('template_directory'); ?>/img/partner-example.jpg" alt="partner"></div>
                <div class="item"><img src="<?php bloginfo('template_directory'); ?>/img/partner-example.jpg" alt="partner"></div>
                <div class="item"><img src="<?php bloginfo('template_directory'); ?>/img/partner-example.jpg" alt="partner"></div>
                <div class="item"><img src="<?php bloginfo('template_directory'); ?>/img/partner-example.jpg" alt="partner"></div>
                <div class="item"><img src="<?php bloginfo('template_directory'); ?>/img/partner-example.jpg" alt="partner"></div>
                <div class="item"><img src="<?php bloginfo('template_directory'); ?>/img/partner-example.jpg" alt="partner"></div>
                <div class="item"><img src="<?php bloginfo('template_directory'); ?>/img/partner-example.jpg" alt="partner"></div>
                <div class="item"><img src="<?php bloginfo('template_directory'); ?>/img/partner-example.jpg" alt="partner"></div>
                <div class="item"><img src="<?php bloginfo('template_directory'); ?>/img/partner-example.jpg" alt="partner"></div>
                <div class="item"><img src="<?php bloginfo('template_directory'); ?>/img/partner-example.jpg" alt="partner"></div>
                <div class="item"><img src="<?php bloginfo('template_directory'); ?>/img/partner-example.jpg" alt="partner"></div>
                <div class="item"><img src="<?php bloginfo('template_directory'); ?>/img/partner-example.jpg" alt="partner"></div>
            </div>       
        </section>
        <section class="footer-content">
            <section class="footer-sitemap">
                <ul>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                    <li><a href="#">sitemap item link</a></li>
                </ul>
            </section>
            <section class="footer-logo">
                <img src="<?php bloginfo('template_directory'); ?>/img/logo_large.png" width="500" height="500" alt="" title="" />
            </section>
            <section class="footer-links">
                <ul>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                    <li><a href="#">gebruikersvoorwaarden</a></li>
                </ul>
            </section>
            <section class="clear"></section>
        </section>
    </footer>
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
        
    $('#recent-projects-slider .item').hover(
            function () { $(this).find('.overlay').fadeIn(300); },
            function () { $(this).find('.overlay').fadeOut(200); }
    );
        
    </script>
<?php wp_footer();?>
</body>
</html>