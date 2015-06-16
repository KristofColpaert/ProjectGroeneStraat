<?php
	/*
		Shortcode plugin
	*/

	add_shortcode('edit_profile', 'prowp_edit_profile');

	/*
		Plugin methods
	*/

	function prowp_edit_profile()
	{
		save_edit_profile_form();
		show_edit_profile_form();
	}

	/*
		Plugin methods
	*/

	function show_edit_profile_form()
	{
		if(is_user_logged_in() && !isset($_POST['profileSubmit']))
		{
			$current_user = wp_get_current_user();

			$name = get_user_meta($current_user->ID, 'last_name', true);
			$firstname = get_user_meta($current_user->ID, 'first_name', true);
			$street = get_user_meta($current_user->ID, 'rpr_straat', true);
			$city = get_user_meta($current_user->ID, 'rpr_gemeente', true);
			$zipcode = get_user_meta($current_user->ID, 'rpr_postcode', true);
			$telephone = get_user_meta($current_user->ID, 'rpr_telefoon', true);
			$sex = get_user_meta($current_user->ID, 'rpr_geslacht', true);
			$newsletter = get_user_meta($current_user->ID, 'rpr_nieuwsbrief', true);
			$identity = get_user_meta($current_user->ID, 'rpr_gegevens', true);
			?>
				<form class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
					<h2>Naam</h2>
					<input id="profileFirstname" name="profileFirstname" class="textbox" type="text" placeholder="Voornaam" value="<?php echo $firstname; ?>"/>
					<input id="profileName" name="profileName" class="textbox" type="text" placeholder="Naam" value="<?php echo $name; ?>"/>

					<h2>Wachtwoord</h2>
					<input id="profilePassword1" name="profilePassword1" class="textbox" type="password" placeholder="Wachtwoord" />
					<input id="profilePassword2" name="profilePassword2" class="textbox" type="password" placeholder="Herhaal wachtwoord" />

					<h2>Contactinformatie</h2>
					<input id="profileEmail" name="profileEmail" class="textbox" type="text" placeholder="E-mailadres (loginnaam wijzigt niet)" value="<?php echo $current_user->user_email; ?>"/>
					<input id="profileWebsite" name="profileWebsite" class="textbox" type="text" placeholder="Website" value="<?php echo $current_user->user_url; ?>"/>
					<input id="profileStreet" name="profileStreet" class="textbox" type="text" placeholder="Straat en huisnummer" value="<?php echo $street; ?>"/>
					<input id="profileCity" name="profileCity" class="textbox" type="text" placeholder="Gemeente" value="<?php echo $city; ?>"/>
					<input id="profileZipcode" name="profileZipcode" class="textbox" type="text" placeholder="Postcode" value="<?php echo $zipcode; ?>"/>
					<input id="profileTelephone" name="profileTelephone" class="textbox" type="text" placeholder="Telefoonnummer" value="<?php echo $telephone; ?>"/>

					<h2>Extra informatie</h2>
					<div class="center-radio">
				        <input class="radio" <?php if($sex == 'Man' || $sex != 'Vrouw') echo 'checked'; ?> type="radio" name="profileSex" id="profileSexMan" value="Man"/><label for="profileSexMan">Man</label>
				        <input class="radio" <?php if($sex == 'Vrouw') echo 'checked'; ?> type="radio" name="profileSex" id="profileSexWoman" value="Vrouw" /><label for="profileSexWoman">Vrouw</label><br/>
				    </div>
					<section class="form-line">
						<input id="profileNewsletter" name="profileNewsletter" class="checkbox js-switch" type="checkbox" value="1" <?php if($newsletter == 1) echo 'checked'; ?>/><label class="checkbox-label" for="profileNewsletter">Wenst u een nieuwsbrief te ontvangen?</label>
					</section>
					<section class="form-line">
        				<input id="profileIdentity" name="profileIdentity" class="checkbox js-switch" type="checkbox" value="1" <?php if($identity == 1) echo 'checked'; ?>/><label class="checkbox-label" for="profileIdentity">Ik stel mijn gegevens vrij voor andere gebruikers.</label><br/>
      				</section>

      				<input id="profileSubmit" name="profileSubmit" class="form-button" value="Bewerk" type="submit" />
				</form>

				<script>
					var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
				    elems.forEach(function(html) {
				        var switchery = new Switchery(html, {color:'#00cd00', secondaryColor:'#E74C3C', size:'small'});
				    });

				    var nietLeeg = "Dit veld is verplicht!";

					var lastname = new LiveValidation('profileName', {validMessage:" "});
					lastname.add(Validate.Presence,{failureMessage:nietLeeg});
					lastname.add(Validate.Length, {maximum:25, tooLongMessage: "Maximum 25 tekens lang!"});

					var firstname = new LiveValidation('profileFirstname', {validMessage:" "});
					firstname.add(Validate.Presence,{failureMessage:nietLeeg});
					firstname.add(Validate.Length, {maximum:15, tooLongMessage: "Maximum 15 tekens lang!"});

					var email = new LiveValidation('profileEmail', {validMessage:" "});
					email.add(Validate.Presence,{failureMessage:nietLeeg});
					email.add(Validate.Length, {maximum:50, tooLongMessage: "Maximum 50 tekens lang!"});
					email.add(Validate.Email, {failureMessage: "Moet een geldig e-mailadres zijn!"});
					$("#user_login").focusout(function(){
        				checkValidEmail($("#user_login").val(),true,"user_login");
    				});

   					var street = new LiveValidation('profileStreet', {validMessage:" "});
   					street.add(Validate.Length, {maximum:30, tooLongMessage: "Maximum 30 tekens lang!"});

   					var city = new LiveValidation('profileCity', {validMessage:" "});
   					city.add(Validate.Length, {maximum:20, tooLongMessage: "Maximum 20 tekens lang!"});

   					var zipcode = new LiveValidation('profileZipcode', {validMessage:" "});
   					zipcode.add(Validate.Length, {is:4, wrongLengthMessage: "Een postcode moet 4 cijfers bevatten!"});
   					zipcode.add(Validate.Numericality, {onlyInteger:true});

   					var telephone = new LiveValidation('profileTelephone', {validMessage:" "});
   					telephone.add(Validate.Length, {maximum:13, tooLongMessage: "Maximum 13 tekens lang!"});
   					telephone.add(Validate.Numericality, {onlyInteger:true});

   					var password1 = new LiveValidation('profilePassword1', {validMessage:" "});
   					password1.add(Validate.Length, {minimum:6, tooShortMessage:"Wachtwoord moet minimum 6 tekens bevatten."});
				    password1.add(Validate.Custom, {against: function checkPassword(value){
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

				    var password2 = new LiveValidation('profilePassword2', {validMessage:" "});
				   	$('#profilePassword1').change(function()
				   	{
				   		password2.add(Validate.Presence,{failureMessage:nietLeeg});
    					password2.add(Validate.Confirmation, {match:'profilePassword1', failureMessage:"Wachtwoorden zijn niet gelijk!"});
				   	});

				   	$('#profilePassword2').change(function()
				   	{
				   		password2.add(Validate.Presence,{failureMessage:nietLeeg});
    					password2.add(Validate.Confirmation, {match:'profilePassword1', failureMessage:"Wachtwoorden zijn niet gelijk!"});
				   	});
				</script>
			<?php
		}

		else if(isset($_POST['profileSubmit']))
		{}

		else
		{
			?>
				<p class="error-message">U bent niet aangemeld. Gelieve eerst <a href="<?php echo home_url() . '/login'; ?>">in te loggen of te registreren</a>.</p>
			<?php
		}
	}

	function save_edit_profile_form()
	{
		if(is_user_logged_in())
		{
			if(isset($_POST['profileSubmit']))
			{
				if(!empty($_POST['profileName']) &&
					!empty($_POST['profileFirstname']) &&
					!empty($_POST['profileEmail'])
				)
				{	
					$current_user = wp_get_current_user();
					$firstname = sanitize_text_field($_POST['profileFirstname']);
					$name = sanitize_text_field($_POST['profileName']);
					$email = sanitize_text_field($_POST['profileEmail']);
					$website = sanitize_text_field($_POST['profileWebsite']);
					$street = sanitize_text_field($_POST['profileStreet']);
					$city = sanitize_text_field($_POST['profileCity']);
					$zipcode = sanitize_text_field($_POST['profileZipcode']);
					$telephone = sanitize_text_field($_POST['profileTelephone']);
					$sex = $_POST['profileSex'];
					$newsletter = $_POST['profileNewsletter'];
					$identity = $_POST['profileIdentity'];

					$args = array(
						'ID' => $current_user->ID,
						'user_email' => $email,
						'user_url' => $website,
						'first_name' => $firstname,
						'last_name' => $name
					);

					if(isset($_POST['profilePassword1']) && !empty($_POST['profilePassword1']) && 
						isset($_POST['profilePassword2']) && !empty($_POST['profilePassword2'])
					)
					{
						$password1 = $_POST['profilePassword1'];
						$password2 = $_POST['profilePassword2'];

						if($password1 == '' || $password1 == ' ')
						{
							?>
								<p class="error-message">Gelieve alle vereiste gegevens correct in te voeren.</p>
							<?php
							return;
						}


						if($password1 == $password2)
						{
							$args['user_pass'] = $password1;
						}

						else
						{
							?>
								<p class="error-message">Gelieve alle vereiste gegevens correct in te voeren.</p>
							<?php
							return;
						}
					}				

					wp_update_user($args);

					update_user_meta($current_user->ID, 'rpr_straat', $street);
					update_user_meta($current_user->ID, 'rpr_gemeente', $city);
					update_user_meta($current_user->ID, 'rpr_postcode', $zipcode);
					update_user_meta($current_user->ID, 'rpr_telefoon', $telephone);
					update_user_meta($current_user->ID, 'rpr_nieuwsbrief', $newsletter);
					update_user_meta($current_user->ID, 'rpr_gegevens', $identity);

					?>
						<img class="center" src="<?php echo get_template_directory_uri() ?>/img/ball.gif" />
		                <script>
		                    $('.title').remove();
		                </script>
		                <h2 class="normalize-text center">Uw profiel wordt bewerkt</h2>
					<?php

					echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . esc_url(home_url() . '/profiel/?userid=' . $current_user->ID) . '">'; 
					return;	
				}
				else
				{
					?>
						<p class="error-message">Gelieve alle vereiste gegevens correct in te voeren.</p>
					<?php
				}
			}
		}
	}
?>