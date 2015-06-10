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
		$meta_key = "_eventCalendar";

		//ophalen data uit database
		$value = get_user_meta($user_id, $meta_key);

		//alle events ophalen
		 	//Eerst de titel uit de get_post();
			//Dan de meta_data uit de get_meta_data();
		$events = array();
		foreach($value as $val)
		{
			$event = get_post($val, OBJECT);
			$post_meta = get_post_meta($event->ID);
			$event->eventTime = $post_meta['_eventTime'][0];
			$event->eventEndTime = $post_meta['_eventEndTime'][0];
			$event->eventStartHour = $post_meta['_eventStartHour'][0];
			$event->eventEndHour = $post_meta['_eventEndHour'][0];
			$events[] = $event;
		}

		?>
			<link href='<?php echo plugins_url('fullcalendar.css', __FILE__); ?>' rel='stylesheet' />
			<link href='<?php echo plugins_url('fullcalendar.print.css', __FILE__); ?>' rel='stylesheet' media='print' />

			<script src='http://fullcalendar.io/js/fullcalendar-2.1.1/lib/moment.min.js'></script>
			<!-- JQuery already loaded -->
			<!--<script src='http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery.min.js'></script>-->
			<script src="http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery-ui.custom.min.js"></script>
			<script src='http://fullcalendar.io/js/fullcalendar-2.1.1/fullcalendar.min.js'></script>
			
			<script>
			<?php
				if(is_user_logged_in())
				{
					//logged in
			?>
				$(document).ready(function() {
					$('#calendar').fullCalendar({
						defaultDate: new Date(),
						editable: true,
						firstDay: 1,
                		eventLimit: true, // allow "more" link when too many events
						events: [
							<?php 
							if(count($events) > 0)
							{
								foreach($events as $event)
								{
									?>
										{
											title: '<?php echo $event->post_title; ?>',
											<?php
											$start = date_create($event->eventTime);
											$end = date_create($event->eventEndTime);

											//het splitsen van het uur
											$startHour = split(":", $event->eventStartHour);
											$start->setTime($startHour[0],$startHour[0]);

											$endHour = split(":" , $event->eventEndHour);
											$end->setTime($endHour[0],$endHour[0]);

											print "start: '" . date_format($start, 'Y-m-d H:i:s') . "',";
											print "end: '" . date_format($end, 'Y-m-d H:i:s') . "',";
											?>
											url: '<?php echo get_permalink($event->ID); ?>',
										},
									<?php
								}
							}

							?>
						]
					});
					
				});

			<?php
				}
				else
				{ 
					?>
					$(document).ready(function() {
						$('#hidden').show();
					});
					<?php
				}
			?>
			</script>
			<style>
				#calendar {
					max-width: 900px;
					margin: 0 auto;
				}
			</style>
			<div id='calendar'></div>
			<p id='hidden' style="display:none"> Gelieve u aan te melden om deze pagina te bekijken. </p>
		<?php
	}
?>