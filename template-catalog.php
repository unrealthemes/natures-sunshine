<?php
	/*
	 * Template Name: Catalog
	 *
	 * */

	get_header(); ?>

	<div class="wrapper">
		<main class="main">

			<?php do_action( 'echo_kama_breadcrumbs' ); ?>

			<?php
				get_template_part( 'template-parts/catalog/banner' );
				get_template_part( 'template-parts/catalog/catalog' );
			?>

		</main> 
	</div>

<?php
	get_footer();