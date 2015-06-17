<?php 
	/*
		Plugin Name: Groenestraat Profiel
		Plugin URI: http://www.groenestraat.be
		Description: Deze plugin toont het profiel van een gebruiker. 
		Version: 1.0
        Author: Rodric Degroote, Kristof Colpaert, Koen Van Crombrugge en Vincent De Ridder
		Author URI: http://www.groenestraat.be
		Text Domain: prowp-plugin
		License: GPLv2		
	*/

    function getProfileMonth($var)
    {
        switch ($var) 
        {
        case '01':
            $string = 'JANUARI';
            break;
        case '02':
            $string = 'FEBRUARI';
            break;
        case '03':
            $string = 'MAART';
            break;
        case '04':
            $string = 'APRIL';
            break;
        case '05':
            $string = 'MEI';
            break;
        case '06':
            $string = 'JUNI';
            break;
        case '07':
            $string = 'JULI';
            break;
        case '08':
            $string = 'AUGUSTUS';
            break;
        case '09':
            $string = 'SEPTEMBER';
            break;
        case '10':
            $string = 'OKTOBER';
            break;
        case '11':
            $string = 'NOVEMBER';
            break;
        case '12':
            $string = 'DECEMBER';
            break;
        default:
            # code...
            break;
        }
        return $string;
    }
	
	/*
		Add Shortcodes
	*/

	add_shortcode('profiel','prowpt_memberinformatie');

	register_activation_hook(__FILE__, 'prowp_memberinformatie_install');

	function prowp_memberinformatie_install()
	{
		makeMemberInformationShortcode('Profiel','[profiel]','profiel','publish','page','closed');
	}

	function makeMemberInformationShortcode($title,$content,$post_name,$post_status,$post_type,$ping_status)
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

	function prowpt_memberinformatie()
	{
		if(is_user_logged_in())
		{
            ?>
                <script>
                            $(".contentwrapper").addClass("container");
                            $(".container").removeClass("contentwrapper");
                            $("#main").unwrap();
                            $(".container").unwrap();
                            $(".title").remove();
                </script>
            <?php
                $userid = '';

                if(isset($_GET['userid']))
                {
                    $userid = $_GET['userid'];
                }

                else
                {
                    $userid = get_current_user_id();
                }

    			if($userid != '');
    			{
                    ?>
                    <section class="sub-menu">
                    <?php
        				$user = get_userdata($userid);
        				$usermeta = get_user_meta($userid);
                        $firstname = $usermeta["first_name"][0];
        				$name =  $usermeta["last_name"][0];

        				echo get_avatar($userid, 60); 
                        echo "<h1 class='name'>".$firstname." ".$name;
    				?>
    				
    				<?php
        				global $post;
        				$the_query = new WP_Query(
        				array(
                            'posts_per_page' => 9,
        					'author' => $userid,
                            'paged' => 1,
        					'post_type' => array('events', 'post', 'zoekertjes'),
        					'order' => 'ASC',
        					'orderby' => 'date')
        				);
                    ?> 
                    </section>
                    <section class="main" data="profiel;1">
                    <?php
    				if ($the_query->have_posts()) {
    					while ($the_query->have_posts()) : $the_query->the_post();
    						switch($post->post_type)
                            {
                                case "events":{
                                    $meta = get_post_meta($post->ID);
                                    $eventTime = $meta['_eventTime'][0];
                                    $eventLocation = $meta['_eventLocation'][0];
                                    $eventEndTime = $meta['_eventEndTime'][0];

                                    $day = explode("/", $eventTime)[1];
                                    $monthNumber = explode("/", $eventTime)[0];
                                    $month = getProfileMonth($monthNumber);
            		                
                                    ?>
                                        <section class="list-item normalize-text <?php echo $post->post_type;?>">
                                            <section class="event-calendar">
                                                <h1><?php echo $day; ?></h1>
                                                <h2><?php echo $month; ?></h2>
                                            </section>
                                            <section class="event-content">
                                                <h1><?php the_title(); ?></h1>
                                                <p><strong>Tijdstip: </strong><?php echo $eventTime . " - " . $eventEndTime; ?></p>
                                                <p><strong>Locatie: </strong><?php echo $eventLocation; ?></p>
                                                <p><strong>Meer info: </strong><?php echo excerpt(50); ?></p>
                                                 
                                            </section>
                                            <a class="view-item" href="<?php the_permalink(); ?>">Bekijk event</a>
                                        </section>
                            		<?php
                                }
                                break;

                                case "zoekertjes":{
                                    $meta = get_post_meta($post->ID);
                                    $adLocation = $meta['_adLocation'][0];
                                    $adPrice = $meta['_adPrice'][0];
                                    ?>
                                        <section class="list-item normalize-text <?php echo $post->post_type;?>">
                                            <h1><?php the_title(); ?></h1>
                                            <p><strong>Beschrijving: </strong><?php the_content(); ?></p>
                                            <p><strong>Locatie: </strong><?php echo $adLocation; ?></p>
                                            <p><strong>Prijs: </strong><?php echo $adPrice; ?></p>
                                             <a class="view-item" href="<?php the_permalink(); ?>">Bekijk zoekertje</a>
                                        </section>
            		                <?php
                                
                                }
                                break;

                                case "post":{
                                    ?>
                                        <section class="list-item normalize-text <?php echo $post->post_type; ?>">
            					        <h1><?php echo the_title();?></h1>
                                        <?php echo the_excerpt(); ?>
                        				<a class="view-item" href="<?php the_permalink(); ?>">Lees meer</a></section><?php
                                }
                                break;

                                default: break;
                            }
                        
    					endwhile;
    				} 
                    else 
                    {
    					?> 
                            <section class="list-item">
                                <h2 class="normalize-text">Er werden geen activiteiten gevonden.</h2>s
                            </section>
    					<?php
    				}    
    			?>
        </section>
            <section class="sidebar">
                <section class="search normalize-text">
                    <a class="wide-button" href="<?php echo site_url().'/leden-overzicht'; ?>">Terug naar ledenoverzicht</a>
                    <?php
                        if($userid == get_current_user_id()){
                        ?>
                            <a class="wide-button" href="<?php echo site_url().'/bewerk-profiel'; ?>">Bewerk profiel</a>
                        <?php
                        }
                    ?>
                    
                    <hr />
                    <section class="form-line">
                        <input class="checkbox js-switch" id="zoekertjes" onchange="apply_filter('z')" type="checkbox" name="zoekertjes" value="1" checked/>
                        <label class="checkbox-label">Toon zoekertjes</label>
                    </section>
                    <section class="form-line">
                        <input class="checkbox js-switch" id="events" onchange="apply_filter('e')" type="checkbox" name="events" value="1" checked/>
                        <label class="checkbox-label">Toon events</label> 
                    </section>
                    <section class="form-line">
                        <input class="checkbox js-switch" id="post" onchange="apply_filter('a')" type="checkbox" name="artikels" value="1" checked/>
                        <label class="checkbox-label">Toon artikels</label> 
                        <hr />
                    </section>
                    <?php
                        if(get_user_meta($user->ID, "rpr_gegevens", true) == 1)
                        {
                            ?>

                                <strong>E-mail: </strong><p><?php echo $user->user_email; ?></p>
                            <?php
                            if($usermeta['rpr_straat'][0] != "" || $usermeta['rpr_postcode'][0] != "" || $usermeta['rpr_gemeente'][0] != "")
                            {
                            ?>
                                <strong>Adres: </strong><p><?php echo $usermeta['rpr_straat'][0] . ', ' . $usermeta['rpr_postcode'][0] . ' ' . $usermeta['rpr_gemeente'][0]; ?></p>
                            <?php
                            }
                            if($usermeta['rpr_telefoon'][0] != "")
                            {
                            ?>
                                <strong>Telefoon: </strong><p><?php echo $usermeta['rpr_telefoon'][0]; ?></p>
                            <?php
                            }
                        }
                    ?>
                </section>
               
                
                
                    
            </section>
					
				<?php
			}
		}
		else
		{
			?>
				<p>U moet zich aanmelden om deze pagina te bekijken.</p>
			<?php
		}
        ?>
        <script>
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html, {color:'#00cd00', size:'small'});
        });
        
        function apply_filter(type){
            console.log(type);
            var el;
            if(type=='z'){
                el ="zoekertjes";
            }
            if(type=='e'){
                el="events";
            }
            if(type=='a'){
                el="post";
            }
            if($("#"+el).prop("checked")){
                    
                    $('.'+el).animate(
    	    		{
    				    height: 'toggle',
                        padding: '4%',
                        margin:'5% 3%',
    				}, 500);
                }
                else {
                    $('.'+el).animate(
    	    		{
                        padding:'0% 4%',
    				    height: 'toggle',
                        margin:'0% 3%',
    				}, 500);
                }
        }  
    </script>

    <?php
	}

?>