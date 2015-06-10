
<?php
if(is_user_logged_in()){header('Location: '.site_url());}
/*
Template Name: Register
*/
get_header();
?>
<section class="header-image header-image-small"></section>


<div class="centered-form">
    <h3>Registreren</h3>
    <?php
       do_action( 'wordpress_social_login' );
        ?><hr align=center/>
    
<form name="registerform" id="registerform" action="<?php echo get_site_url(); ?>/wp-login.php?action=register" method="post" novalidate="novalidate">
	   <input class="textbox" type="text" name="first_name" id="first_name" class="input" value="" placeholder="Voornaam"/><br/>
        <input class="textbox" type="text" name="last_name" id="last_name" class="input" value="" placeholder="Achternaam"/><br/>
        <input class="textbox" type="text" name="user_email" id="user_email" class="input" value="" size="25" placeholder="Email"/><br/>
        <input class="textbox" type="password" autocomplete="off" name="pass1" id="pass1" placeholder="Wachtwoord"/><br/>
        <input class="textbox" type="password" autocomplete="off" name="pass2" id="pass2" placeholder="Herhaal Wachtwoord"/><br/>
        <div class="center-radio">
        <input class="radio" type="radio" name="rpr_geslacht" id="rpr_geslacht-man" value="Man" checked/><label for="rpr_geslacht-man">Man</label>
        <input class="radio" type="radio" name="rpr_geslacht" id="rpr_geslacht-vrouw" value="Vrouw" /><label for="rpr_geslacht--vrouw">Vrouw</label><br/>
            </div>
        <input class="textbox" type="text" name="rpr_straat" id="rpr_straat" class="input" value="" placeholder="Straat"/><br/>
        <input class="textbox" type="text" name="rpr_gemeente" id="rpr_gemeente" class="input" value="" placeholder="Gemeente" /><br/>
        <input class="textbox" type="text" name="rpr_postcode" id="rpr_postcode" class="input" value="" placeholder="Postcode" /><br/>
        <input class="textbox" type="text" name="rpr_telefoon" id="rpr_telefoon" class="input" value="" placeholder="Telefoon"/><br/>
        
    <section class="form-line">
        <input class="checkbox js-switch" type="checkbox" name="rpr_gegevens[]" value="1" checked/><label class="checkbox-label">Ik stel mijn gegevens vrij voor andere gebruikers.</label><br/>
        </section>
    <section class="form-line">
    <input class="checkbox js-switch" type="checkbox" name="rpr_nieuwsbrief[]" value="1" checked/><label class="checkbox-label">Ik wens een nieuwsbrief te ontvangen.</label><br/></section>
    <section id="voorwaarden" class="form-line">
        <label class="checkbox-label">Ik accepteer de <a href="#">gebruiksvoorwaarden</a></label>
        <input class="checkbox js-switch" type="checkbox" name="accept_privacy_policy" id="accept_privacy_policy" value="1"/><br/></section>
</p>	
	<br class="clear" />
	<input type="hidden" name="redirect_to" value="http://groenestraat.azurewebsites.net/abcdefghij/wp-admin/" />
	<input type="submit" name="wp-submit" id="wp-submit" class="button" value="Registreren" />
</form>
 <section class="form-bottom-links">      
    <a href="<?php echo site_url()."/login"; ?>" title="Aanmelden">Al een account? meld je aan.</a>
    </section>
</div>
<!----switches--->
<script>
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function(html) {
        var switchery = new Switchery(html, {color:'#00cd00', secondaryColor:'#E74C3C', size:'small'});
    });
</script>
<!---validation---->
<script>
    var nietLeeg = "Dit veld is verplicht!";
    var firstName = new LiveValidation('first_name', {validMessage:" "});
    firstName.add(Validate.Presence,{failureMessage:nietLeeg});
    firstName.add(Validate.Length, {maximum:15, tooLongMessage: "Maximum 15 tekens lang!"});
    
    var lastName = new LiveValidation('last_name', {validMessage:" "});
    lastName.add(Validate.Presence,{failureMessage:nietLeeg});
    lastName.add(Validate.Length, {maximum:25, tooLongMessage: "Maximum 25 tekens lang!"});
    
    var email = new LiveValidation('user_email', {validMessage:" "});
    email.add(Validate.Presence,{failureMessage:nietLeeg});
    email.add(Validate.Length, {maximum:50, tooLongMessage: "Maximum 50 tekens lang!"});
    email.add(Validate.Email, {failureMessage: "Moet een geldig emailadres zijn!"});
    
    
    var straat = new LiveValidation('rpr_straat', {validMessage:" "});
    straat.add(Validate.Length, {maximum:30, tooLongMessage: "Maximum 30 tekens lang!"});
    
    var gemeente = new LiveValidation('rpr_gemeente', {validMessage:" "});
    gemeente.add(Validate.Length, {maximum:20, tooLongMessage: "Maximum 20 tekens lang!"});
    
    var postcode = new LiveValidation('rpr_postcode', {validMessage:" "});
    postcode.add(Validate.Length, {is:4, wrongLengthMessage:"Een postcode moet 4 cijfers bevatten!"});
    postcode.add(Validate.Numericality, {onlyInteger:true});
    
    var telefoon = new LiveValidation('rpr_telefoon', {validMessage:" "});
    telefoon.add(Validate.Length, {maximum:13, tooLongMessage: "Maximum 13 tekens lang!"});
    telefoon.add(Validate.Numericality, {onlyInteger:true});
    
    var w1 = new LiveValidation('pass1', {validMessage:" "});
    w1.add(Validate.Presence,{failureMessage:nietLeeg});
    w1.add(Validate.Length, {minimum:6, tooShortMessage:"Wachtwoord moet minimum 6 tekens bevatten."});
    w1.add(Validate.Custom, {against: function checkPassword(value){
      re = /[0-9]/;
      if(!re.test(value)) {
        return false;
      }
      re = /[a-z]/;
      if(!re.test(value)) {
        return false;
      }
      re = /[A-Z]/;
      if(!re.test(value)) {
        return false;
      }
       else return true;
   }, failureMessage:"Een wachtwoord moet kleine letters, hoofdletters & cijfers bevatten!"});
    
    var w2 = new LiveValidation('pass2', {validMessage:" "});
    w2.add(Validate.Presence,{failureMessage:nietLeeg});
    w2.add(Validate.Confirmation, {match:'pass1', failureMessage:"Wachtwoorden zijn niet gelijk!"});
    
    var voorwaarden = new LiveValidation('accept_privacy_policy', {validMessage:" "});
    voorwaarden.add(Validate.Acceptance, {failureMessage:"Accepteer de gebruiksvoorwaarden!"});
    
    $("#user_email").focusout(function(){
        checkValidEmail($("#user_email").val(),false,"user_email");
    });
  
  
</script>

    

<?php
get_footer();
?>