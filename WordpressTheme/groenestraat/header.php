<?php global $current_user; ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Groene Straat</title>
    <link rel="icon" href="<?php bloginfo('template_directory'); ?>/img/favicon.ico" type="image/x-icon" />
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
    <header>
        <nav>
            <img src="<?php bloginfo('template_directory'); ?>/img/logo.png" class="logo" width="400" height="65" alt="" title="" />
            <section class="mobile-menu" style="display:none">
                <?php wp_nav_menu(array('Main menu' => 'header-menu')); ?>              
                <?php 
                  if ( is_user_logged_in() ) 
                  {
                  ?>
                  <script>
                        addMenuLink('<?php echo get_site_url(); ?>/member-informatie?userid=<?php echo $current_user->ID ?>', 'mijn profiel (<?php echo $current_user->user_firstname ?>)');
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
                     <?php wp_nav_menu(array('Main menu' => 'header-menu')); ?>
                <?php
                }
                else{
                  
                  echo '<li class="aanmelden" style="float:right"><a href="'.get_site_url().'/login">AANMELDEN</a></li>';
                  wp_nav_menu(array('Main menu' => 'header-menu'));
                }?> 
                        
                     </ul>
            </section>
            <script>

              var isOpen = false;
              var isResize = false;
              var menu = document.getElementsByClassName('mobile-menu')[0];
              var logo = document.getElementsByClassName('logo')[0];

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
                        if(document.getElementById('wpadminbar') != null)
                        {
                            menu.style['top'] = document.getElementsByTagName('nav')[0].offsetHeight + document.getElementById('wpadminbar').offsetHeight + 'px'; 
                        }else
                        {
                            menu.style['top'] = document.getElementsByTagName('nav')[0].offsetHeight + 'px'; 
                        }
                        menu.style.display = 'block';
                        isOpen = true;
                      }
                  }
              }

              function setLogo()
              {
                  if(window.innerWidth < 1150)
                  {
                      logo.setAttribute('width', '500px');
                      logo.setAttribute('src', '<?php bloginfo('template_directory'); ?>/img/logo-mobile.png');
                  }
                  if(window.innerWidth > 1150)
                  {
                      logo.setAttribute('width', '400px');
                      logo.setAttribute('src', '<?php bloginfo('template_directory'); ?>/img/logo.png');
                      logo.addEventListener('click', function() { document.location = '<?php echo get_site_url(); ?>'; });
                  }
              }

              function toggleHeight()
              {
                  setLogo();

                  if(isOpen){
                      if(window.innerWidth < 1150)
                      {
                          if(document.getElementById('wpadminbar') != null)
                          {
                              menu.style['top'] = document.getElementsByTagName('nav')[0].offsetHeight + document.getElementById('wpadminbar').offsetHeight + 'px'; 
                          }
                          else
                          {
                              menu.style['top'] = document.getElementsByTagName('nav')[0].offsetHeight + 'px'; 
                          }
                      }
                      else
                      {
                          menu.style.display = 'none';
                          isOpen = false;
                      }
                  }
              }

              document.getElementsByTagName('nav')[0].addEventListener('click', function() { toggleNavigation(); });
              window.addEventListener('resize', function() { toggleHeight(); });
              window.addEventListener('load', function() { setLogo(); });
            </script>
        </nav>
    </header>