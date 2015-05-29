<?php 
//LOAD CSS AND SCRIPTS//
function script_enqueue(){
    wp_enqueue_style('groenestraat',get_template_directory_uri().'/css/groenestraat.css', array(), '1.0.0', 'all');
    wp_enqueue_style('groenestraat_edit',get_template_directory_uri().'/css/edit.css', array(), '1.0.0', 'all');
}
add_action('wp_enqueue_scripts', 'script_enqueue');     

//REGISTER MENUS//
function theme_setup(){
    add_theme_support('menus');
    
    register_nav_menu('primary','User not logged in');
    register_nav_menu('secondary','User logged in');
    register_nav_menu('usersub','User Drop down');
}
add_action('init','theme_setup');

//ADD LOGIN BUTTON// 
add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);

function add_login_logout_link($items, $args) { 

	if ( $args->theme_location == 'primary' ) {	

        ob_start();

        $loginoutlink = ob_get_contents();

        ob_end_clean(); 

        $items .= '<li class="aanmelden"><a href="'.wp_login_url(get_site_url()).'">AANMELDEN</a></li>';
    }

    return $items;

}
//REDIRECT TO CUSTOM LOGIN PAGE//
function redirect_login_page() {
    $login_page  = home_url( '/login/' );
    $page_viewed = basename($_SERVER['REQUEST_URI']);
 
    if( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
        wp_redirect($login_page);
        exit;
    }
}
add_action('init','redirect_login_page');
?>