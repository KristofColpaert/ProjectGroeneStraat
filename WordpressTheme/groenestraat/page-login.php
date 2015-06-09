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
    wp_login_form($args);

?>
     <section class="form-bottom-links">
         <a href="<?php echo get_site_url(); ?>/forgot" title="Wachtwoord vergeten">Wachtwoord vergeten</a><br>
    <a href="<?php echo site_url()."/registreren"; ?>" title="Registreren">Nog geen account? Registreer!</a>
    </section>
   
    </div>


<script>
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
    email.add(Validate.Custom, {against: function checkEmail(value){
        $("#user_login").css({border:'1px solid #00CD00'});
            checkValidEmail(value, function(val){
                console.log(val);
                if(val != "true"){
                     $("#user_login").next().show();
                    $("#user_login").css({border:'1px solid #E74C3C'});
                    $("#user_login").next().text("Dit emailadres is nog niet geregistreerd!");
                    $("#user_login").next().css({color:'#E74C3C'})
                    return false;
                     
                }
                else {
                    $("#user_login").css({border:'1px solid #00CD00'});
                    $("#user_login").next().hide();
                    return true;
                }
            });
        return false;        
      
    }, failureMessage:" "});
    
    var pass = new LiveValidation('user_pass', {validMessage:" "});
    pass.add(Validate.Presence,{failureMessage:nietLeeg});

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