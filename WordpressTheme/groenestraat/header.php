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
                <li class="basic-menu-item"><a href="<?php echo get_site_url(); ?>/artikels">Artikels</a></li>
                <li class="basic-menu-item"><a href="<?php echo get_site_url(); ?>/projecten">Projecten</a></li>
                    <li class="basic-menu-item"><a href="<?php echo get_site_url(); ?>/Events">Events</a></li>
                     <li class="basic-menu-item"><a href="<?php echo get_site_url(); ?>/Zoekertjes">Zoekertjes</a></li>
                <?php
                    
                if ( is_user_logged_in() ) 
                { 
                    $userstring = $current_user->user_firstname." ".$current_user->user_lastname;
                    get_currentuserinfo();
                     
                     
    echo '<a id="logged_user" href="'.wp_logout_url(get_site_url()).'">'.get_avatar($current_user->user_email, 32).'<p>'.$userstring. '</p><img class="small-arrow" src="'.get_template_directory_uri().'/img/arrow_grey.png"/></a>';
   
                }
                else{
                    echo '<li class="aanmelden"><a href="'.get_site_url().'/login">AANMELDEN</a></li>';
                    
                }?> 
                     </ul>
            </section>
        </nav>
    </header>