<?php
	/*
		Plugin Name: Groenestraat Persoonlijke Kalender
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin voegt een persoonlijke kalender page toe.
		Version: 1.0
		Author: Rodric Degroote, Kristof Colpaert
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

	/*
		Install plugin
	*/

	register_activation_hook(__FILE__, 'prowp_personal_calendar_install');

	function prowp_personal_calendar_install()
	{
		$content = '[personal_calendar]';
		$args = array(
			'post_title' => 'Kalender',
			'post_content' => $content,
			'post_name' => 'kalender',
			'post_status' => 'publish', 
			'post_type' => 'page',
			'ping_status' => 'closed'
		);
		wp_insert_post($args);
	}

	/*
		Add shortcode
	*/

	add_shortcode('personal_calendar','prowpt_kalender');
		
	$events = array();
	function prowpt_kalender()
	{

		$user_id = get_current_user_id();
		$meta_key = "_eventCalendar ";

		//ophalen data uit database
		$value = get_user_meta($user_id, $meta_key);

		//alle events ophalen
		 	//Eerst de titel uit de get_post();
			//Dan de meta_data uit de get_meta_data();
		foreach($value as $val)
		{
			$event= get_post($val, OBJECT);
			$event->eventTime = get_post_meta($event->ID, '_eventTime')[0];
			$event->eventEndTime = get_post_meta($event->ID, '_eventEndTime')[0];
			$events[] = $event;
		}

		?>
			<link href='<?php echo plugins_url('fullcalendar.css', __FILE__); ?>' rel='stylesheet' />
			<link href='<?php echo plugins_url('fullcalendar.print.css', __FILE__); ?>' rel='stylesheet' media='print' />

			<script src='http://fullcalendar.io/js/fullcalendar-2.1.1/lib/moment.min.js'></script>
			<script src='http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery.min.js'></script>
			<script src="http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery-ui.custom.min.js"></script>
			<script src='http://fullcalendar.io/js/fullcalendar-2.1.1/fullcalendar.min.js'></script>
			<script>

				$(document).ready(function() {
					console.log("hier");
					$('#calendar').fullCalendar({
						defaultDate: new Date(),
						editable: true,
						eventLimit: true,
						events: [
							<?php 
								foreach($events as $event)
								{
									?>
										{
											title: '<?php echo $event->post_title; ?>',
											start: '<?php echo $event->eventTime; ?>',
											end: '<?php echo $event->eventEndTime; ?>',
											url: '<?php echo get_permalink($event->ID); ?>'
										},
									<?php
								}
							?>
						]
					});
					
				});

			</script>
			<style>

				body {
					margin: 40px 10px;
					padding: 0;
					font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
					font-size: 14px;
				}

				#calendar {
					max-width: 900px;
					margin: 0 auto;
				}

			</style>
			<div id='calendar'></div>
		<?php
	}
?>