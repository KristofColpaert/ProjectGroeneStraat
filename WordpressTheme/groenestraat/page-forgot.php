<?php 
/*
Template Name: Forgot
*/
get_header();
?>
<section id="forgot" class="centered-form">
    <h3>Nieuw wachtwoord aanvragen</h3>
<form action="<?php echo wp_lostpassword_url(); ?>" method="post">
    <input class="textbox" name="user_login" type="text" placeholder="email"/>
    <input class="form-button" type="submit" name="submit" value="Doorgaan" />
</form>
</section>

<?php
get_footer();
?>