<?php
	/*
		Plugin Name: Groenestraat API Key
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin zorgt ervoor dat u een API key kunt meegeven van Google, Facebook en Twitter 
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert, Koen Van Crombrugge en Vincent De Ridder
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	if(isset($_POST["Opslaan"]))
	{
		if(isset($_POST["ApplicationID"]) && !empty($_POST["ApplicationID"]))
		{
			$id = $_POST["ApplicationID"];
			$name = "_applicationID";
			
			if(get_option($name)) {
				update_option($name, $id);
			} else {
				add_option($name, $id);
			}
		}
	}

	global $post;

	add_action('admin_menu', 'register_keys');

	function register_keys() 
	{
		add_menu_page( 'API Key', 'API Key', 'manage_options', 'API Keys', 'add_keys_metaboxes', 'dashicons-feedback', 83);
	}

	function add_keys_metaboxes(){
		?>
		<div class="wrap" id="wp-media-grid">
			<h2>Nieuwe API key</h2>
				<form class="createForm" method="post" action="<?php echo get_permalink(); ?>" enctype="multipart/form-data">
					<p><b>Application Key: </b></p>
					<input type="text" name="ApplicationID" placeholder="Application key" /><br />
					<input type="submit" value="Opslaan" name="Opslaan" style="margin-top: 10px" class="button button-primary"/>
				</form>
		</div>
		<div class="wrap" id="wp-media-grid">
			<h2>Huidige API key</h2>
			<p>
				<?php 
					$name = "_applicationID";
					if(get_option($name)) {
						?>
							<p><b>Application Key: </b><?php echo get_option($name); ?></p>
						<?php
					} else {
						?>
							<p style="color: red">Er werd nog geen API Key meegegeven.</p>
						<?php
					}
				?>
			</p>
		</div>
		<?php
	}
?>