
<?php
/*
Template Name: Register
*/
get_header();
?>
<div class="centered-form">
<?php
if (!is_user_logged_in()) { 
       do_action( 'wordpress_social_login' );
        ?><hr align=center/>
    
<form name="registerform" id="registerform" action="http://groenestraat.azurewebsites.net/abcdefghij/wp-login.php?action=register" method="post" novalidate="novalidate">
	
        <input class="textbox" type="text" name="user_login" id="user_login" class="input" value="" size="20" placeholder="Gebruikersnaam"/><br/>
        <input class="textbox" type="email" name="user_email" id="user_email" class="input" value="" size="25" placeholder="Email"/><br/>
        <input class="textbox" type="text" name="first_name" id="first_name" class="input" value="" placeholder="Voornaam"/><br/>
        <input class="textbox" type="text" name="last_name" id="last_name" class="input" value="" placeholder="Achternaam"/><br/>
        <div class="center-radio">
        <input class="radio" type="radio" name="rpr_geslacht" id="rpr_geslacht-man" value="Man" /><label for="rpr_geslacht-man">Man</label>
        <input class="radio" type="radio" name="rpr_geslacht" id="rpr_geslacht-vrouw" value="Vrouw" /><label for="rpr_geslacht--vrouw">Vrouw</label><br/>
            </div>
        <input class="textbox" type="text" name="rpr_straat" id="rpr_straat" class="input" value="" placeholder="Straat"/><br/>
        <input class="textbox" type="text" name="rpr_gemeente" id="rpr_gemeente" class="input" value="" placeholder="Gemeente" /><br/>
        <input class="textbox" type="text" name="rpr_postcode" id="rpr_postcode" class="input" value="" placeholder="Postcode" /><br/>
        <input class="textbox" type="text" name="rpr_telefoon" id="rpr_telefoon" class="input" value="" placeholder="Telefoon"/><br/>
        <input class="textbox" type="password" autocomplete="off" name="pass1" id="pass1" placeholder="Wachtwoord"/><br/>
        <input class="textbox" type="password" autocomplete="off" name="pass2" id="pass2" placeholder="Herhaal Wachtwoord"/><br/>
        <input class="checkbox" type="checkbox" name="accept_privacy_policy" id="accept_privacy_policy" value="1"/>&nbsp;Ik accepteer de <a href="#">gebruiksvoorwaarden</a><br/>
</p>	
	<br class="clear" />
	<input type="hidden" name="redirect_to" value="http://groenestraat.azurewebsites.net/abcdefghij/wp-admin/" />
	<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Registreren" />
</form>


    
    <?php
    
}
?>
</div>
<?php
get_footer();
?>