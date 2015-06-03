<?php 
	get_header(); 
	global $post;
?>

	<section class="project-image">
		<section class="project-info">

			<!-- Kristof zijn toevoegingen -->
			<form method="POST" id="projectMemberForm">
				<input id="projectMemberId" name="projectMemberId" type="hidden" value="<?php echo get_current_user_id(); ?>" />
				<input id="projectMemberProjectId" name="projectMemberProjectId" type="hidden" value="<?php echo $post->ID; ?>" />
				<input id="projectMemberSubmit" name="projectMemberSubmit" type="submit" value="Inschrijven" />
			</form>
		</section>
	</section>

	<section class="container" style="background-color:#ccc">

		<section class="main">
		
	<?php

	while(have_posts()) : the_post();
		$meta = get_post_meta($post->ID);
		$projectStreet = $meta['_projectStreet'][0];
		$projectCity = $meta['_projectCity'][0];
		$projectZipcode = $meta['_projectZipcode'][0];
		$adresInfo = $projectStreet . " " . $projectCity . " " . $projectZipcode;
		?>

		<script>

		var header = document.getElementsByClassName("project-image")[0];
		header.style["background-image"] = "url('<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>')";

		var info = document.getElementsByClassName("project-info")[0];
		var h1 = document.createElement("h1");
		h1.innerHTML = "<?php the_title(); ?>";
		var strong = document.createElement("strong");
		strong.innerHTML = "&#160; <?php echo $adresInfo ?>";

		h1.appendChild(strong);
		info.appendChild(h1);


		</script>

		<?php

			echo "number = " . $post->ID;

		?>

		<?php
			$args = array(
				'posts_per_page' => 9,
				'post_type' => 'projecten'
			);

			$my_query = new WP_Query($args);
			//print_r($my_query);

			while($my_query->have_posts()) : $my_query->the_post();
				/*
				$meta = get_post_meta($post->ID);
				$projectStreet = $meta['_projectStreet'][0];
				$projectCity = $meta['_projectCity'][0];
				$projectZipcode = $meta['_projectZipcode'][0];
				*/
			?>

				<section class="item">
					<h1><?php echo the_title(); ?></h1>

				</section>

			<?php
			endwhile;
		?>
		
		<!--
		<?php the_post_thumbnail(); ?>
		<h1><?php the_title(); ?></h1>
		<p>Hoofdbericht: <?php the_content() ?></p>
		<p>Straat: <?php echo $projectStreet; ?></p>
		<p>Gemeente: <?php echo $projectCity; ?></p>
		<p>Postcode: <?php echo $projectZipcode; ?></p>
		-->

		</section>
		<section class="sidebar"></section>

		<?php
	endwhile;
	?>

	</section>
	<section class="clear"></section>
	
	<?php
	get_footer();
?>