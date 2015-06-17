<?php
	/*
		Plugin Name: Groenestraat Partners
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin zorgt ervoor dat u een partner kunt toevoegen of verwijderen. 
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert, Koen Van Crombrugge en Vincent De Ridder
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	global $post;

	if(isset($_POST["Verwijderen"]))
	{
		if(isset($_POST["PartnerId"]) && !empty($_POST["PartnerId"]))
		{
			if(is_admin())
			{
				$id = $_POST["PartnerId"];

				$wpdb->delete("wp_partners", array(
						'ID' => $id
						));			
			}
			else
			{
				print "<p class='error-message'>Gelieve zich aan te melden als administrator</p>";
			}
		}
	}

	if(isset($_POST["Opslaan"]))
	{
		if(isset($_POST["Bedrijfsnaam"]) && isset($_POST["URL"]))
		{
			if(!empty($_POST["Bedrijfsnaam"]) && !empty($_POST["URL"]))
			{
				$bedrijfsnaam = $_POST["Bedrijfsnaam"];
				$url = $_POST["URL"];

				if(is_uploaded_file($_FILES["Logo"]["tmp_name"]))
				{
					require_once( ABSPATH . 'wp-admin/includes/admin.php' );
					require_once(ABSPATH . 'wp-includes/pluggable.php');

					$tmpImage = $_FILES["Logo"];
					$moveFile = wp_handle_upload($tmpImage, array('test_form' => FALSE));
					$urlImage = $moveFile["url"];
					$newURLImage = parse_url($urlImage)["path"];

					$wpdb->insert("wp_partners", array(
					        'Bedrijfsnaam' => $bedrijfsnaam,
					        'URL' => $url,
					        'URLImage' => $newURLImage
					    ));

				}
				else
				{
					echo "<p class='error-message'>Gelieve een logo up-te-loaden.</p>";
				}

			}
			else
			{
				echo "<p class='error-message'>Gelieve alle velden in te vullen.</p>";
			}
		}
	}

	//aanmaken van een table voor het toevoegen van partners
	register_activation_hook(__FILE__, 'jal_install');

	function jal_install () 
	{
		global $wpdb;
   		$table_name = $wpdb->prefix . "partners"; 

   		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS `wp_partners` (
  			`ID` int(11) NOT NULL AUTO_INCREMENT,
  			`Bedrijfsnaam` text NOT NULL,
  			`URL` text NOT NULL,
  			`URLImage` text NOT NULL,
  			PRIMARY KEY (`ID`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	add_action('admin_menu', 'register_partners');

	function register_partners() 
	{
		add_menu_page( 'Partners', 'Partners', 'manage_options', 'Partners', 'add_partners_metaboxes', 'dashicons-feedback', 86);
	}

	function add_partners_metaboxes(){
		?>
		<div class="wrap" id="wp-media-grid">
		<h2>Toevoegen partner</h2>
		<form method="post" action="<?php echo get_permalink(); ?>" enctype="multipart/form-data">
					<h3 class="hndle ui-sortable-handle">Bedrijfsnaam: </h3>
					<input type="text" name="Bedrijfsnaam" placeholder="Vul een bedrijfsnaam in" /><br />
					<h3 class="hndle ui-sortable-handle">URL Website: </h3>
					<input type="text" name="URL" placeholder="Vul een URL in" />
					<h3 class="hndle ui-sortable-handle">Afbeelding Logo</h3>
					<input type='file' name="Logo"/>
					<input type="submit" value="Opslaan" name="Opslaan" class="button button-primary button-large"/>
		</form>
	    </div>

		<div class="wrap" id="wp-media-grid">
		<h2>Partners</h2>

		<?php
		
		global $wpdb;

		//ophalen van alle partners geregistreerd op wordpress
		$results = $wpdb->get_results( "SELECT * FROM wp_partners", ARRAY_A);

		foreach($results as $result)
		{
			?>			
			<form method="post" action="<?php echo get_permalink(); ?>" enctype="multipart/form-data">
				<h3 class="hndle ui-sortable-handle">Bedrijfsnaam: </h3>
					<p class="about-description"><?php echo $result["Bedrijfsnaam"]; ?></p>
				<h3 class="hndle ui-sortable-handle">URL Bedrijf: </h3>
					<a href="<?php echo $result["URL"]; ?>" target="_blank"><?php echo $result["URL"]; ?></a>
				<br />
				<img src="<?php echo $result["URLImage"]; ?>" alt="Logo" title="Logo"/><br />
				<input type="submit" name="Verwijderen" value="Verwijderen" class="button button-primary button-large"/>
				<input type="hidden" value="<?php echo $result["ID"]; ?>" name="PartnerId"/>
			</form>
			<hr />
			<?php
		}
	}
?>