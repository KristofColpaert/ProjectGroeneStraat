<?php global $current_user; ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Groene Straat - Home</title>
    <link rel="icon" href="<?php bloginfo('template_directory'); ?>/img/favicon.ico" type="image/x-icon" />
    <?php wp_head(); ?>
</head>
<body>

    <header>
        <nav>
            <section id="mobile-nav"></section>
            <div class="wrapper">
                <img src="<?php bloginfo('template_directory'); ?>/img/mobile.png" class="mnav" width="65" height="65" alt="" title="" />
                <a href="#"><img src="<?php bloginfo('template_directory'); ?>/img/logo.png" class="logo" width="390" height="65" alt="" title="" /></a>
            </div>
            <section class="menu">
                <?php if ( is_user_logged_in() ) { 
                    $userstring = $current_user->user_firstname." ".$current_user->user_lastname;
                    get_currentuserinfo();
                     echo '<a id="logged_user" href="'.wp_logout_url(get_site_url()).'">'.get_avatar($current_user->user_email, 32).'<p>'.$userstring. '</p><img class="small-arrow" src="'.get_template_directory_uri().'/img/arrow_grey.png"/></a>';
                     wp_nav_menu(array('theme_location'=>'secondary'));
                    
                     
                }
                else{
                    wp_nav_menu(array('theme_location'=>'primary'));
                }?> 
                
                
            </section>
        </nav>
    </header>