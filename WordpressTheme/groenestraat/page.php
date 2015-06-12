<?php
get_header();

?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?php
while ( have_posts() ) : the_post();
?><div class="contentwrapper">
            <h1 class="title"><?php
the_title();
?></h1>
<?php
the_content();
?></div><?php
endwhile;
?>
        </main></div>
<section class="clear"></section>
<?php 
get_footer();
?>