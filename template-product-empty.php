<?php
	/*
	 * Template Name: Product not available
	 *
	 * */

	get_header(); ?>

	<div class="wrapper">
		<main class="main">

			<?php
				get_template_part( 'template-parts/product/breadcrumbs' );
				get_template_part( 'template-parts/product/product-empty' );
				get_template_part( 'template-parts/product/include' );
				get_template_part( 'template-parts/product/description-empty' );
				get_template_part( 'template-parts/product/popular' );
			?>

		</main>
	</div>

<?php
	get_footer();