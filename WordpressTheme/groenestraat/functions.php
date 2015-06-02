<?php 
//LOAD CSS AND SCRIPTS//
function script_enqueue(){
    wp_enqueue_style('groenestraat',get_template_directory_uri().'/css/groenestraat.css', array(), '1.0.0', 'all');
    wp_enqueue_style('groenestraat_edit',get_template_directory_uri().'/css/edit.css', array(), '1.0.0', 'all');
    wp_enqueue_style('owl-carousel-css',get_template_directory_uri().'/css/owl.carousel.css', array(), '1.0.0', 'all');
    wp_enqueue_style('switch-css',get_template_directory_uri().'/css/switchery.min.css', array(), '1.0.0', 'all');
    wp_enqueue_script('jquery-min', get_stylesheet_directory_uri() . '/js/jquery-1.9.1.min.js', array( 'jquery' ));
    wp_enqueue_script('owl-carousel-js', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js', array( 'jquery' ));
    wp_enqueue_script('switchery-js', get_stylesheet_directory_uri() . '/js/switchery.min.js', array( 'jquery' ));
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

        $items .= '<li class="aanmelden"><a href="'.get_home_url().'/login">AANMELDEN</a></li>';
    }

    return $items;

}



//////////////////////////////////////////////////////////////////

function wsl_use_fontawesome_icons( $provider_id, $provider_name, $authenticate_url )
{
    ?>
        <a 
           rel           = "nofollow"
           href          = "<?php echo $authenticate_url; ?>"
           data-provider = "<?php echo $provider_id ?>"
           class         = "wp-social-login-provider wp-social-login-provider-<?php echo strtolower( $provider_id ); ?>" 
         >
            <span>
                <i class="fa fa-<?php echo strtolower( $provider_id ); ?>"></i> Log in met <?php echo $provider_name; ?>
            </span>
        </a>
    <?php
}
  
add_filter( 'wsl_render_auth_widget_alter_provider_icon_markup', 'wsl_use_fontawesome_icons', 10, 3 );

function custom_theme_setup() {
	add_theme_support( 'post-thumbnails');
}
add_action( 'after_setup_theme', 'custom_theme_setup' );