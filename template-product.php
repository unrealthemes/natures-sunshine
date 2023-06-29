<?php
	/*
	 * Template Name: Product
	 *
	 * */

	get_header(); ?>

	<div class="wrapper">
		<main class="main">

			<?php
				get_template_part( 'template-parts/product/breadcrumbs' );
				get_template_part( 'template-parts/product/product' );
				get_template_part( 'template-parts/product/description' );
				get_template_part( 'template-parts/product/popular' );
			?>

		</main>
	</div>

<?php
	get_footer();