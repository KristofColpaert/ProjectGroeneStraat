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

	//Verzenden mail
	if(isset($_POST["Verzenden"]))
	{
		add_action('plugins_loaded', 'register_sendprojectmail');
	}

	function register_sendprojectmail()
	{
		//mail verzenden
		$onderwerp = $_POST["Onderwerp"];
		$bijlage = $_POST["Bijlage"];
		$SelectedProject = $_POST["_parentProjectId"];
		$users = get_users();
		$ontvangers = array();

		if(empty($onderwerp) || empty($bijlage) || empty($ontvangers))
		{
			return;
		}

		$completeBijlage = "<body><h1>Nieuwsbrief</h1>" . $bijlage . "<br />" . "<p>Met vriendelijke groeten</p><br /><p>Groenestraat.be</p>";
		$headers = array("From: admin@groenestraat.be",
		    "Reply-To: admin@groenestraat.be", "Content-Type: text/html; charset=UTF-8");
		$headers = implode("\r\n", $headers);

		foreach($users as $user)
		{

		}
	}
?>
<?php

	add_action('admin_menu', 'register_projectmail');

	function register_projectmail() 
	{
		add_menu_page( 'Mail', 'Mail', "delete_posts", 'Mail', 'add_mail_metaboxes', 'dashicons-email', 4);
	}

	function add_mail_metaboxes(){
		?>

		<form method="post" action="<?php echo get_permalink(); ?>" >
					<h1>Verzend e-mail</h1>
					<strong>Onderwerp: </strong><br />
					<input type="text" name="Onderwerp" placeholder="Vul een onderwerp in" /><br />

					<strong>Bijlage: </strong><br />
					<textarea name="Bijlage" style="width: 500px; height: 300px; resize: none">
							
					</textarea><br />

					<strong>Project: </strong><br />
						<?php
								$projecten = get_posts(
									array(
										'post_type' => 'projecten',
										'orderby' => 'title',
										'order' => 'ASC',
										'numberposts' => -1
									)
								);

								if(!empty($projecten))
								{
									global $post; 

									$postParentId = get_post_meta($post->ID, '_parentProjectId', true);
									echo '<select name="_parentProjectId">';
									echo '<option value="0">Geen Project</option>';

									foreach($projecten as $project)
									{
										printf('<option value="%s"%s>%s</option>', esc_attr($project->ID), selected($project->ID, $postParentId, false), esc_html($project->post_title));
									}
									echo '</select>';
								}
						?>
					<br />
					<input type="submit" value="Verzenden" name="Verzenden"/><br />	
		</form>

		<?php
	}
?>