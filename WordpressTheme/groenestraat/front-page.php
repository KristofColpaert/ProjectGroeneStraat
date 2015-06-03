<?php get_header(); ?>
    
    <section class="header-image"></section>
	<section class="recent">
        <section class="recent-item">
            <h1>Projecten</h1>
            <p>Projecten brengen mensen samen die werken naar een speciefiek doel, zoals werken naar een specifiek doel, zoals bijvoorbeeld een gezamelijke moestijn op het einde van hun straat</p>
            <a href="<?php echo get_site_url(); ?>/projecten" class="button">Projecten &#62;</a>
        </section>
        <section class="recent-item">
            <h1>Artikels</h1>
            <p>Lees &amp; schrijf artikels (on)afhankelijk van een bepaald project. Deel eigen ervaringen &amp; help ons naar een groenere toekomst.</p>
            <a href="<?php echo get_site_url(); ?>/artikels" class="button">Artikels &#62;</a>
            </section>
        <section class="recent-item">
            <h1>Events</h1>
            <section class="calender">
                <section class="calender-image">
                    <em>15</em>
                    <img src="<?php bloginfo('template_directory'); ?>/img/calendar.png" width="65" height="55" alt="" title="" />
                </section>
                <section class="calender-content">
                    <p>Initiatie bomen planten <br/><strong>25 mei om 14:00</strong></p>
                </section>
            </section>
            <section class="calender">
                <section class="calender-image">
                    <em>15</em>
                    <img src="<?php bloginfo('template_directory'); ?>/img/calendar.png" width="65" height="55" alt="" title="" />
                </section>
                <section class="calender-content">
                    <p>Initiatie bomen planten <br/><strong>25 mei om 14:00</strong></p>
                </section>
            </section>
            <section class="calender">
                <section class="calender-image">
                    <em>15</em>
                    <img src="<?php bloginfo('template_directory'); ?>/img/calendar.png" width="65" height="55" alt="" title="" />
                </section>
                <section class="calender-content">
                    <p>Initiatie bomen planten <br/><strong>25 mei om 14:00</strong></p>
                </section>
            </section><br/><br/>
            <a href="<?php echo get_site_url(); ?>/events" class="button">Events &#62;</a>
        </section>
    </section>

    <section class="clear"></section>

    <section class="home-title">
        <h1>Artikels</h1>
    </section>

    <section class="recent-artikel">
        <section class="artikel-side">
            <h1></h1><p></p>
        </section>
	    <section class="artikel-info">
	    	<section class="artikel-item" id="active">
	    		<h1></h1><p></p>
	    	</section>
	    	<section class="artikel-item">
	    		<h1></h1><p></p>
	    	</section>
	    	<section class="artikel-item">
	    		<h1></h1><p></p>
	    	</section>
	    	<section class="artikel-item">
	    		<h1></h1><p></p>
	    	</section>
	    </section>
	</section>

    <script>

        var length = document.getElementsByClassName('artikel-item').length;
        var data = 
        [
            {
                "titel": "Titel 1",
                "info": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
                "image": "/img/artikel-example1.png",
                "url": "http//www.google.com"
            },
            {
                "titel": "Titel 2",
                "info": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore",
                "image": "/img/artikel-example2.png",
                "url": "http//www.google.com"
            },
            {
                "titel": "Titel 3",
                "info": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut",
                "image": "/img/artikel-example3.png",
                "url": "http//www.google.com"
            },
            {
                "titel": "Titel 4",
                "info": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor",
                "image": "/img/artikel-example4.png",
                "url": "http//www.google.com"
            }
        ]

        parseData(data);

        var index = 0;
        var timer = setInterval(function () { 

            replace(index);

            index++;
            if(index > 3) { index = 0; }

        }, 3000);

        function parseData(data) 
        {
            for(var i=0;i<data.length;i++) 
            {
                var e = document.getElementsByClassName('artikel-item')[i];
                e.getElementsByTagName('h1')[0].innerHTML = data[i].titel;
                e.getElementsByTagName('p')[0].innerHTML = data[i].info;   
            }
        }
 
        function replace(newId)
        {
            index = newId;
            for(var i=0;i<length;i++)
            {
                var currentItem = document.getElementsByClassName('artikel-item')[i];
                var chosen = document.getElementsByClassName('artikel-item')[newId];

                if(currentItem.hasAttribute("id"))
                {
                    currentItem.removeAttribute('id');
                    chosen.setAttribute('id', 'active');

                    var info = document.getElementsByClassName('artikel-side')[0];
                    info.getElementsByTagName('h1')[0].innerHTML = data[newId].titel;
                    info.getElementsByTagName('p')[0].innerHTML = data[newId].info;
                    document.getElementsByClassName('recent-artikel')[0].style["background-image"] = "url('<?php echo bloginfo('template_directory'); ?>" + data[newId].image + "')";
                }
            }
        }

        document.getElementsByClassName('artikel-item')[0].addEventListener('click', function() { replace(0); });
        document.getElementsByClassName('artikel-item')[1].addEventListener('click', function() { replace(1); });
        document.getElementsByClassName('artikel-item')[2].addEventListener('click', function() { replace(2); });
        document.getElementsByClassName('artikel-item')[3].addEventListener('click', function() { replace(3); });
        
        //fancy slider by koen van crombrugge

    </script>

    <section class="home-title">
        <h1>Projecten</h1>
    </section>

    <section class="recent-projects">
        <div id="recent-projects-slider">
            <div class="item fade">
                <div class="overlay">
                    <h1>Vlindertuin in de bosstraat</h1>
                </div>
                <img src="<?php bloginfo('template_directory'); ?>/img/owl1.jpg" alt="recent-project">
            </div>
           <div class="item fade">
                <div class="overlay">
                    <h1>Vlindertuin in de bosstraat</h1>
                </div>
                <img src="<?php bloginfo('template_directory'); ?>/img/owl1.jpg" alt="recent-project">
            </div>
            <div class="item fade">
                <div class="overlay">
                    <h1>Vlindertuin in de bosstraat</h1>
                </div>
                <img src="<?php bloginfo('template_directory'); ?>/img/owl1.jpg" alt="recent-project">
            </div>
           	<div class="item fade">
                <div class="overlay">
                    <h1>Vlindertuin in de bosstraat</h1>
                </div>
                <img src="<?php bloginfo('template_directory'); ?>/img/owl1.jpg" alt="recent-project">
            </div>
            <div class="item fade">
                <div class="overlay">
                    <h1>Vlindertuin in de bosstraat</h1>
                </div>
                <img src="<?php bloginfo('template_directory'); ?>/img/owl1.jpg" alt="recent-project">
            </div>
            <div class="item fade">
                <div class="overlay">
                    <h1>Vlindertuin in de bosstraat</h1>
                </div>
                <img src="<?php bloginfo('template_directory'); ?>/img/owl1.jpg" alt="recent-project">
            </div>
            <div class="item fade">
                <div class="overlay">
                    <h1>Vlindertuin in de bosstraat</h1>
                </div>
                <img src="<?php bloginfo('template_directory'); ?>/img/owl1.jpg" alt="recent-project">
            </div>
        </div>        
    </section>

    <section class="home-title">
        <h1>Zoekertjes</h1>
    </section>

    <section class="recent-zoekertjes">
        <section class="zoekertje-item">
            <h3>Bosmaaier</h3>
            <p>Bosmaaier te leen gedurende 2 weken tijdens mijn verlof. Werkt op benzine.</p>
           <div>
                <p>Locatie: wevelgem</p>
                <a href="#">Bekijk</a>
            </div>
        </section>
        <section class="zoekertje-item">
            <h3>Bosmaaier</h3>
            <p>Bosmaaier te leen gedurende 2 weken tijdens mijn verlof. Werkt op benzine.</p>
            <div>
                <p>Locatie: wevelgem</p>
                <a href="#">Bekijk</a>
            </div>
        </section>
        <section class="zoekertje-item">
            <h3>Bosmaaier</h3>
            <p>Bosmaaier te leen gedurende 2 weken tijdens mijn verlof. Werkt op benzine.</p><br/>
            <div>
                <p>Locatie: wevelgem</p>
                <a href="#">Bekijk</a>
            </div>
        </section>
        <section class="clear"></section>
    </section>

<?php get_footer(); ?>