<?php
get_header();

?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?php
while ( have_posts() ) : the_post();
?><div class="contentwrapper">
            <h1><?php
the_title();
?>
            </h1>
<?php
the_content();
?></div><?php
endwhile;
?>
        </main></div>
<?php 
get_footer();
?>