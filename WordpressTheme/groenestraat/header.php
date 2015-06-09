<?php global $current_user; ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Groene Straat</title>
    <link rel="icon" href="<?php bloginfo('template_directory'); ?>/img/favicon.ico" type="image/x-icon" />
    <?php wp_head(); ?>
</head>
<body>
    <header>
        <nav>
            <a href="<?php echo get_site_url(); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/logo.png" class="logo" width="400" height="65" alt="" title="" /></a>
            <section class="menu">
              <ul>
                <!--
                <li class="basic-menu-item normalize-text"><a href="<?php echo get_site_url(); ?>/artikels">Artikels</a></li>
                <li class="basic-menu-item normalize-text"><a href="<?php echo get_site_url(); ?>/projecten">Projecten</a></li>
                <li class="basic-menu-item normalize-text"><a href="<?php echo get_site_url(); ?>/events">Events</a></li>
                <li class="basic-menu-item normalize-text"><a href="<?php echo get_site_url(); ?>/zoekertjes">Zoekertjes</a></li>
                -->
                <?php
                   
                if ( is_user_logged_in() ) 
                { 
                    $userstring = $current_user->user_firstname." ".$current_user->user_lastname;
                    get_currentuserinfo();     
                ?>
                     
                    <div id="logged-user" class="click-nav">
                      <ul class="no-js">
                        <li>
                          <a href="#" class="clicker">
                              <?php echo get_avatar($current_user->user_email, 32); echo '<p>'.$userstring.'</p>'; ?>
                                <img class="small-arrow" src="<?php echo get_template_directory_uri();?>/img/arrow_grey.png"/>
                            </a>
                          <ul>
                            <li><a href="#">Profiel</a></li>
                            <li><a href="#">Beheren</a></li>
                            <li><a href="#">Privacy</a></li>
                            <li><a href="#">Help</a></li>
                            <li><a href="<?php echo wp_logout_url(get_site_url()); ?>">Afmelden</a></li>
                          </ul>
                        </li>
                      </ul>
                    </div>    
                     <script>
                        $(function () {
                          $('.click-nav > ul').toggleClass('no-js js');
                          $('.click-nav .js ul').hide();
                          $('.click-nav .js').click(function(e) {
                            $('.click-nav .js ul').slideToggle(100);
                            $('.clicker').toggleClass('active');
                            e.stopPropagation();
                          });
                          $(document).click(function() {
                            if ($('.click-nav .js ul').is(':visible')) {
                              $('.click-nav .js ul', this).slideUp();
                              $('.clicker').removeClass('active');
                            }
                          });
                        });
                     </script>
                <?php
   
                }
                else{
                    echo '<li class="aanmelden"><a href="'.get_site_url().'/login">AANMELDEN</a></li>';
                }?> 
                      <?php wp_nav_menu(array('Main menu' => 'header-menu')); ?>
                     </ul>
            </section>
        </nav>
    </header>