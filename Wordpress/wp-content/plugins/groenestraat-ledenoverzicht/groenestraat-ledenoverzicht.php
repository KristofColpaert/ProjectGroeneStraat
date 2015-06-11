<?php
	/*
		Plugin Name: Groenestraat Ledenoverzicht
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin voegt een overzicht de leden in Groenestraat.be 
		Version: 1.0
		Author: Rodric Degroote
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/
	
	/*
		Add actions
	*/

add_shortcode('leden_overzicht','prowpt_ledenoverzicht');

register_activation_hook(__FILE__, 'prowp_ledenOverzicht_install');

	function prowp_ledenOverzicht_install()
	{
		makeLedenOverzichtShortcode('Leden overzicht','[leden_overzicht]','leden overzicht','publish','page','closed');
	}

	function makeLedenOverzichtShortcode($title,$content,$post_name,$post_status,$post_type,$ping_status)
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


function prowpt_ledenoverzicht()
{
	$letters = array();
	
	if(is_user_logged_in())
	{
		$args = array(
			'orderby'      => 'display_name',
			'order'        => 'ASC'
		 ); 

		$users = get_users($args);
		foreach ($users as $user) 
		{
			//als user gelijk is als de ingelogde user;
			if($user->ID == get_current_user_id())
			{
				continue;
			}

			$eersteLetter = str_split($user->display_name, 1)[0];

			if(!in_array($eersteLetter, $letters))
			{
				$letters[] = $eersteLetter;
				echo '<h1>' . $eersteLetter . '</h1>';
			}

			?>
				<a href="/member-informatie?userid=<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></a>
			<?php
		}
	}
	else
	{
		?>
			<p>Gelieve u aan te melden om deze pagina te bekijken.</p>
		<?php
	}
}


?>