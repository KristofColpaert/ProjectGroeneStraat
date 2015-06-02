<?php
	/*
		Plugin Name: Groenestraat Nieuwsbrief
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin verstuurd een nieuwsbrief naar iedere user. 
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

		if(empty($onderwerp) || empty($bijlage) || empty($ontvangers))
		{
			return;
		}

		$completeBijlage = "<body><h1>Nieuwsbrief</h1>" . $bijlage . "<br />" . "<p>Met vriendelijke groeten</p><br /><p>Groenestraat.be</p>";

		// To send HTML mail, the Content-type header must be set
		$headers = array("From: admin@groenestraat.be",
		    "Reply-To: admin@groenestraat.be", "Content-Type: text/html; charset=UTF-8");
		$headers = implode("\r\n", $headers);

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

		}
		else
		{
			//
		}
	}
?>
<?php
	add_action('admin_menu', 'register_nieuwsbrief');


	function register_nieuwsbrief() 
	{
		add_menu_page( 'Nieuwsbrief', 'Nieuwsbrief', 'manage_options', 'Nieuwsbrief', 'add_nieuwsbrief_metaboxes', 'dashicons-search', 80);
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
		$paged = get_query_var( 'status', 1 );
	}
?>