<?php 
/*
Template Name: Login
*/
get_header();
 

?>
<?php
if (!is_user_logged_in()) { // Display WordPress login form:
$args = array(
        'echo'           => true,
        'redirect' => get_home_url(),
        'form_id'        => 'loginform',
        'label_username' => __( 'Username' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in'   => __( 'Aanmelden' ),
        'id_username'    => 'user_login',
        'id_password'    => 'user_pass',
        'id_remember'    => 'rememberme',
        'id_submit'      => 'wp-submit',
        'remember'       => true,
        'value_username' => '',
        'value_remember' => true
); 
    wp_login_form( $args );
} else { // If logged in:
    header('Location: '.get_home_url());
}

?>
<script>
    $("#id_username").attr('placeholder','Gebruikersnaam');
     $("#id_password").attr('placeholder','Wachtwoord');
</script>


<?php 
get_footer();

?>