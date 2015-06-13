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

	?>
		<style>


		</style>

	<?php

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


		$completeBijlage = '<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>The HTML5 Herald</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">


  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>
    <style>
        *{padding: 0; margin: 0;}
        body{background-color: #a2a1a9; padding: 0vw 10vw; font-family: "verdana";}
        #wrapper{
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            background-color: #f8f7f7;
            min-height: 100vh;
        }
        img{
            display: block;
            margin: 0 auto;
            max-width: 70vw;
        }
        h1{
            color: #69686e;
            font-weight: 100;
            text-align: center;
            text-transform: uppercase;
            font-size: 1.5em;
        }
        p{
            color: #69686e;
            text-align: center;
            padding: 10px;
        }
        a{
            text-align: center;
            color: #69686e;
        }
        #reset{
            display: block;
            margin: 0 auto;
            text-decoration: none;
            border: 1px solid #00cd00;
            width: 60%;
            padding: 5px;
            border-radius: 3px;
        }
        #reset:hover{
            background-color: #00cd00;
            color: #f8f7f7;
        }
    </style>
 <div id="wrapper">
     <img src="http://groenestraat.azurewebsites.net/abcdefghij/wp-content/themes/groenestraat/img/logo_large.png" />
     <h1>Nieuwsbrief, '. $onderwerp . '</h1>
     <p>'. $bijlage . '</p>
     <p>Veel leesplezier,<br>
     het groenestraat.be team</p>
</div>
</body>
</html>';

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$headers .= 'From: Het groenestraat.be team' . "\r\n";
		$headers .= 'Reply-To: wordpress@groenestraat.be' . "\r\n";

		foreach($users as $user)
		{
			if(get_user_meta($user->ID, "rpr_nieuwsbrief", true) == 1)
			{
				$to = $user->user_email;
				$ontvangers[] = $to;
			}
		}

		//eventueel feedback geven
		if(wp_mail($ontvangers, $onderwerp, $completeBijlage, $headers))
		{
			//
		}
		else
		{
			//
		}
	}

	add_action('admin_menu', 'register_nieuwsbrief');


	function register_nieuwsbrief() 
	{
		add_menu_page( 'Nieuwsbrief', 'Nieuwsbrief', 'manage_options', 'Nieuwsbrief', 'add_nieuwsbrief_metaboxes', 'dashicons-feedback', 81);
	}

	function add_nieuwsbrief_metaboxes(){
		?>
	<div class="wrap" id="wp-media-grid">
		<h2>Nieuwsbrief</h2>
		<form method="post" action="<?php echo get_permalink(); ?>" >
					<h3 class="hndle ui-sortable-handle">Onderwerp: </h3>
					<input type="text" name="Onderwerp" placeholder="Vul een onderwerp in" /><br />
					<h3 class="hndle ui-sortable-handle">Bijlage: </h3>
					<textarea id="content" class="mceEditor" autocomplete="off" name="Bijlage" style="width: 500px; height: 300px; resize: none">
							
					</textarea><br />
					<input type="submit" value="Verzenden" name="Verzenden" class="button button-primary button-large"/><br />
		</form>
	</div>
		<?php
	}
?>