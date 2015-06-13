<?php
	/*
		Plugin Name: Groenestraat API Key
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin zorgt ervoor dat u een API key kunt meegeven van Google, Facebook en Twitter 
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	if(isset($_POST["Opslaan"]))
	{
		if(isset($_POST["ApplicationID"]) && !empty($_POST["ApplicationID"]))
		{
			print "test";

			$id = $_POST["ApplicationID"];
			$name = "_applicationID";

			$option = get_option($name);
			
			if(get_option($name)) {
				update_option($name, $id);
				print "update";
			} else {
				add_option($name, $id);
				print "add";
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
		<h2>API Key Google</h2>
		<form class="createForm" method="post" action="<?php echo get_permalink(); ?>" enctype="multipart/form-data">
				<h3 class="hndle ui-sortable-handle">Application Key: </h3>
				<input type="text" name="ApplicationID" placeholder="Application Key" /><br />
				<input type="submit" value="Opslaan" name="Opslaan" class="button button-primary button-large"/>
		</form>
		</div>
		<?php
	}
?>