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
<form action="<?php echo wp_lostpassword_url(site_url()); ?>" method="post">
    <input class="textbox"  id="user_login" name="user_login" type="text" placeholder="Email"/>
    <input class="form-button button-primary" type="submit" name="submit" value="Doorgaan" />
</form>
</section>
<script>
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
</script>
<?php
}else { // If logged in:
    header('Location: '.site_url());
}
get_footer();
?>