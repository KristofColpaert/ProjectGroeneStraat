<?php 
/*
Template Name: Forgot
*/
get_header();
?>
<form action="<?php echo wp_lostpassword_url(); ?>" method="post">
<input type="text" placeholder="email"/>
</form>


<?php
get_footer();
?>