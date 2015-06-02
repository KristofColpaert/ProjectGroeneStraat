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
            <a href="<?php home_url(); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/logo.svg" class="logo" width="400" height="65" alt="" title="" /></a>
            <section class="menu">
                <?php if ( is_user_logged_in() ) 
                { 
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