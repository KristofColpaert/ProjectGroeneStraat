<?php get_header(); ?>

<!-- dit tekstje moet nog vervangen worden door een mooie afbeelding 
best een plantje dat geen bladeren heeft.. dus iemand die tijd heeft? -->
<style>
	.not-found
	{
	    text-align: center;
	    background-color: #69686e;
	    color:#ffffff;
	    width: 100%;
	    height: auto;
	}
	.not-found img
	{
		width: 20%;
		height: auto;
		margin:0 auto;
		margin-bottom: 40px;
	}
	.not-found h1
	{
	    font-size:7em;
	    font-weight: bold;
	    line-height: 2em;
	}
</style>
<section class="container">
	<section class="not-found">
		<h1>404 - Pagina niet gevonden</h1>
		<img src="<?php echo get_template_directory_uri() . '/img/logo_dor.png'; ?>">
	</section>
</section>

<?php get_footer(); ?>