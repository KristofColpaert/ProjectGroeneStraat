<?php 
/*
Template Name: Forgot
*/
get_header();
if(!is_user_logged_in()){
?>
<section class="header-image header-image-small"></section>
<section id="forgot" class="centered-form">
    <h3>Wachtwoord vergeten</h3>
<form action="<?php echo wp_lostpassword_url(); ?>" method="post">
    <input class="textbox" name="user_login" type="text" placeholder="Email"/>
    <input class="form-button button-primary" type="submit" name="submit" value="Doorgaan" />
</form>
</section>

<?php
}else { // If logged in:
    header('Location: '.site_url());
}
get_footer();
?>