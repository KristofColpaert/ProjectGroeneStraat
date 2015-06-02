<?php 
/*
Template Name: Login
*/
get_header();
 

?>
<div class="centered-form">
<?php
if (!is_user_logged_in()) { 
   
    do_action( 'wordpress_social_login' );
     // Display WordPress login form:
    ?> <hr><?php
$args = array(
        'echo'           => true,
        'redirect' => get_home_url(),
        'form_id'        => 'loginform',
        'label_username' => __( '' ),
        'label_password' => __( '' ),
        'label_remember' => __( 'onthouden' ),
        'label_log_in'   => __( 'AANMELDEN' ),
        'id_username'    => 'user_login',
        'id_password'    => 'user_pass',
        'id_remember'    => 'rememberme',
        'id_submit'      => 'wp-submit',
        'remember'       => true,
        'value_username' => '',
        'value_remember' => true
); 
    wp_login_form($args);
} else { // If logged in:
    header('Location: '.get_home_url());
}

?>
    <a href="<?php echo wp_lostpassword_url( get_bloginfo('url') ); ?>" title="Wachtwoord vergeten">Wachtwoord vergeten</a>
    <a href="<?php  ?>" title="Lost Password">Lost Password</a>
    </div>
<script>
    $("#user_login").attr('placeholder','Gebruikersnaam');
     $("#user_pass").attr('placeholder','Wachtwoord');
   

</script>


<?php 
get_footer();

?>