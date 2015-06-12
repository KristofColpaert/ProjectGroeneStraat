<?php
	/*
		Plugin Name: Groenestraat ProjectMail
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin zorgt ervoor dat een projectbeheerder een e-mail kan versturen naar leden van zijn project. 
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	if(isset($_POST["Verzenden"]))
	{
		add_action('plugins_loaded', 'register_sendMail_project_members');
	}

	function register_sendMail_project_members()
	{
		if(isset($_POST["Projecten"]) && isset($_POST["Onderwerp"]) && isset($_POST["Bericht"]))
		{
			if(!empty($_POST["Projecten"]) && !empty($_POST["Onderwerp"]) && !empty($_POST["Bericht"]))
			{
				$selectedProjectId = $_POST["Projecten"];
				$projectTitle = get_post($selectedProjectId)->post_title;
				$onderwerp = $_POST["Onderwerp"];
				$bericht = $_POST["Bericht"];
				$bijlage = '<!doctype html>

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
     <h1>' . $projectTitle . ': ' . $onderwerp . '</h1>
     <p>' . $bericht .'</p>
     <p>Veel leesplezier,<br>
     het groenestraat.be team</p>
</div>
</body>
</html>';

				global $wpdb;

				$subscriber = "_subscriberId";
				//ophalen alle gebruikers die gesubscribed hebben op het geselecteerde project
				$results = $wpdb->get_results($wpdb->prepare( "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $selectedProjectId, $subscriber), ARRAY_A);


				foreach($results as $result)
				{

					$id = $result['post_id'];
					$postNaam = get_post($id, ARRAY_A)['post_title'];

					$userId= $result['meta_value'];
					if($userId != get_current_user_id())
					{
						$userEmail = get_userdata($userId)->user_email;
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
						$headers .= 'From: Admin Project' . "\r\n";
						$headers .= 'Reply-To: Admin Project' . "\r\n";

						//mogelijkheid tot feedback
						if(wp_mail($userEmail, $onderwerp, $bijlage, $headers))
						{
								//

						}
						else
						{		
								//
						}
					}
				}
			}	
		}		
	}

	add_shortcode('mail_projectmembers', 'prowp_mail');
	register_activation_hook(__FILE__, 'prowp_mailProjectmembers_install');

	function prowp_mailProjectmembers_install()
	{
		//mail pagina aanmaken users
		makeMailUserPage('Project members mailen','[mail_projectmembers]','project-mail-members','publish','page','closed');
	}

	function makeMailUserPage($title,$content,$post_name,$post_status,$post_type,$ping_status)
	{
		$args = array(
			'post_title' => $title,
			'post_content' => $content,
			'post_name' => $post_name,
			'post_status' => $post_status, 
			'post_type' => $post_type,
			'ping_status' => $ping_status
		);
		wp_insert_post($args);
	}

	function prowp_mail()
	{
		show_mail_projectmembers_form();
	}

	function show_mail_projectmembers_form()
	{
		//alle projecten ophalen en overlopen. Dan kijken of author_id = userId
		if(is_user_logged_in())
		{
			?>
				<form method="POST" class="createForm" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
					<input class="textbox" type="text" name="Onderwerp" placeholder="Onderwerp" /><br />
					<select class="input combobox normalize-text" name="Projecten">
						<?php
							global $wpdb;
							$postType = "projecten";
							$postAuthor = get_current_user_id();

							$projecten = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $wpdb->posts 
								WHERE post_author = %d AND post_type = %s", $postAuthor, $postType), ARRAY_A);
				
							foreach($projecten as $project)
							{
                                
                                if($project["ID"] == $_GET["project"]){
                                    print "<option class='normalize-text' value='".$project["ID"]."' selected>". $project["post_title"]. "</option>";
                                }
                                else{
                                    print "<option class='normalize-text' value='".$project["ID"]."'>". $project["post_title"]. "</option>";
                                }
								
							}
						?>
					</select><br />
					<textarea class="input textarea-large" name="Bericht">
					</textarea><br />

					<input type="submit" value="Verzenden" class="form-button" name="Verzenden" />
				</form>
			<?php
		}
	}
?>