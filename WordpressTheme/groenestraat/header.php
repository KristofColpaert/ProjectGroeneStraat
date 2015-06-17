<?php global $current_user; ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
    <meta name="viewport" content="width=device-width">
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/favicon-16x16.png">
    <link rel="manifest" href="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo get_stylesheet_directory_uri(); ?>/img/fav/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <?php wp_head(); ?>
    <script>
        function addMenuLink(linkSrc, linkName)
        {
         	var menu = document.getElementById('menu-main-menu');
            var a = document.createElement('a');
            a.setAttribute('href', linkSrc);
            a.innerHTML = linkName;
            var li = document.createElement('li');
            li.setAttribute('class', 'basic-menu-item normalize-text');
            li.appendChild(a);
            menu.appendChild(li);
        }
    </script>
</head>
<body>
    <!-- Toevoegingen AddThis -->
    <div class="addthis_sharing_toolbox" data-url="<?php the_permalink(); ?>" data-title="<?php the_title_attribute(); ?>"></div>
    
    <header>
        <nav>
           	<img src="<?php bloginfo('template_directory'); ?>/img/logo.png" class="logo" width="400" height="65" alt="" title="" />
            <section class="mobile-menu" style="display:none">
                <?php wp_nav_menu(array('menu' => 'Main menu')); ?>              
                <?php 
                  if ( is_user_logged_in() ) 
                  {
                  ?>
                  <script>
                        addMenuLink('<?php echo get_site_url(); ?>/profiel?userid=<?php echo $current_user->ID ?>', 'mijn profiel (<?php echo $current_user->user_firstname ?>)');
                        addMenuLink('<?php echo wp_logout_url(get_site_url()); ?>', 'afmelden');
                  </script>
                <?php
                  }
                  else{ 
                ?>
                    <script>
                        addMenuLink('<?php echo get_site_url(); ?>/login', 'aanmelden');
                    </script>
                    <?php
                }?> 
            </section>
            <section class="menu">
              <ul>
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
                            <?php   wp_nav_menu(array('menu' => 'Sub menu')); ?>
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
                     <?php wp_nav_menu(array('Main menu' => 'header-menu')); ?>
                <?php
                }
                else{
                  echo '<li class="aanmelden" style="float:right"><a href="'.get_site_url().'/login">AANMELDEN</a></li>';
                  wp_nav_menu(array('menu' => 'Main menu'));
                }?>  
                    </ul>
            </section>
            <script>

              var isOpen = false;
              var isResize = false;
              var menu = document.getElementsByClassName('mobile-menu')[0];
              var logo = document.getElementsByClassName('logo')[0];
              var a = document.getElementsByClassName('a')[0];

              function posMenu()
              {
            		document.getElementById('wpadminbar') != null ? menu.style['top'] = document.getElementsByTagName('nav')[0].offsetHeight + document.getElementById('wpadminbar').offsetHeight + 'px': menu.style['top'] = document.getElementsByTagName('nav')[0].offsetHeight + 'px';
              }

              function toggleNavigation()
              {   
                if(window.innerWidth < 1150)
                  {
                      if(isOpen){
                        menu.style.display = 'none';
                        isOpen = false;
                      }
                      else { 
                        menu.style['position'] = 'top';
                        posMenu();
                        menu.style.display = 'block';
                        isOpen = true;
                      }
                  }
              }

            	function setLogo()
              {
                  if(window.innerWidth < 1150)
                  {
                  	 logo.addEventListener('click', function() { window.stop(); });
                      logo.setAttribute('width', '500px');
                      logo.setAttribute('src', '<?php bloginfo("template_directory"); ?>/img/logo-mobile.png');
                      
                  }
                  if(window.innerWidth > 1150)
                  {
                      logo.setAttribute('width', '400px');
                      logo.setAttribute('src', '<?php bloginfo("template_directory"); ?>/img/logo.png');
                      logo.addEventListener('click', function() { document.location = '<?php echo get_site_url(); ?>'; });
                  }
              }        

              function toggleHeight()
              {
              		setLogo();

                  if(isOpen){
                      if(window.innerWidth < 1150)
                      {
                          posMenu();
                      }
                      if(window.innerWidth > 1150)
                      {
                          menu.style.display = 'none';
                          isOpen = false;
                      }
                  }
              }

              document.getElementsByTagName('nav')[0].addEventListener('click', function() { toggleNavigation(); });
              window.addEventListener('resize', function() { toggleHeight(); });
              window.addEventListener('load', function() { toggleHeight(); });

            </script>
        </nav>
    </header>