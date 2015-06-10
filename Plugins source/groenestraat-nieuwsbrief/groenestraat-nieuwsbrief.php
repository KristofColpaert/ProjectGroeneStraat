<?php
	/*
		Plugin Name: Groenestraat Nieuwsbrief
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin verstuurt een nieuwsbrief naar iedere user. 
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	//Verzenden mail
	if(isset($_POST["Verzenden"]))
	{
		add_action('plugins_loaded', 'register_sendmail');
	}

	function register_sendmail()
	{
		//mail verzenden
		$onderwerp = $_POST["Onderwerp"];
		$bijlage = $_POST["Bijlage"];

		$users = get_users();
		$ontvangers = array();

		if(empty($onderwerp) || empty($bijlage))
		{
			return;
		}

		$completeBijlage = "<body><h1>Nieuwsbrief</h1>" . $bijlage . "<br />" . "<p>Met vriendelijke groeten</p><br /><p>Groenestraat.be</p>";

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

		foreach($users as $user)
		{
			if(get_user_meta($user->ID, "rpr_nieuwsbrief", true) == 1)
			{
				$to = $user->user_email;
				$ontvangers[] = $to;
			}
		}

		if(wp_mail($ontvangers, $onderwerp, $completeBijlage, $headers))
		{
			//
			echo "Verstuurd";

		}
		else
		{
			//
			echo "Niet verstuurd";
		}
	}
?>
<?php
	add_action('admin_menu', 'register_nieuwsbrief');


	function register_nieuwsbrief() 
	{
		add_menu_page( 'Nieuwsbrief', 'Nieuwsbrief', 'manage_options', 'Nieuwsbrief', 'add_nieuwsbrief_metaboxes', 'dashicons-feedback', 81);
	}

	function add_nieuwsbrief_metaboxes(){
		?>

		<form method="post" action="<?php echo get_permalink(); ?>" >
					<h1>Nieuwsbrief</h1>
					<strong>Onderwerp: </strong><br />
					<input type="text" name="Onderwerp" placeholder="Vul een onderwerp in" /><br />
					<strong>Bijlage: </strong><br />
					<textarea name="Bijlage" style="width: 500px; height: 300px; resize: none">
							
					</textarea><br />
					<input type="submit" value="Verzenden" name="Verzenden"/><br />
		</form>

		<?php
	}
?>