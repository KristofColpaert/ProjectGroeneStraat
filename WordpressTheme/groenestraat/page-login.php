<?php 
/*
Template Name: Login
*/
get_header();
 

?>
<section class="header-image header-image-small"></section>
<div class="centered-form">
<?php
if (!is_user_logged_in()) { 
   ?>
    <h3>Aanmelden</h3>
    <?php
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
    if($_GET['login']=="failed"){?>
        <p class="LV_validation_message LV_invalid">Het emailadres of wachtwoord was fout.</p>
    <?php
    }
    wp_login_form($args);

?>
     <section class="form-bottom-links">
         <a href="<?php echo get_site_url(); ?>/forgot" title="Wachtwoord vergeten">Wachtwoord vergeten</a><br>
    <a href="<?php echo site_url()."/registreren"; ?>" title="Registreren">Nog geen account? Registreer!</a>
    </section>
   
    </div>


<script>
    var correct;
    $("#user_login").attr('placeholder','Emailadres');
     $("#user_pass").attr('placeholder','Wachtwoord');
    $("#rememberme").addClass('js-switch');
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function(html) {
        var switchery = new Switchery(html, {color:'#00cd00', size:'small'});
    });
    var nietLeeg = "Dit veld is verplicht!";
    var email = new LiveValidation('user_login', {validMessage:" "});
    email.add(Validate.Presence,{failureMessage:nietLeeg});
    email.add(Validate.Length, {maximum:50, tooLongMessage: "Maximum 50 tekens lang!"});
    email.add(Validate.Email, {failureMessage: "Moet een geldig emailadres zijn!"});
    var pass = new LiveValidation('user_pass', {validMessage:" "});
    pass.add(Validate.Presence,{failureMessage:nietLeeg});
    $("#user_login").focusout(function(){
        checkValidEmail($("#user_login").val(),true,"user_login");
    });
    
</script>
    
<?php
}
else { // If logged in:
    header('Location: '.site_url());
}

?>
   

<?php 
get_footer();

?>