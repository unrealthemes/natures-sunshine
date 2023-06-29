<?php
	/*
	 * Template Name: Home
	 */

	get_header(); ?>

	<div class="wrapper">
		<main class="main">

			<?php
				get_template_part( 'template-parts/front-page/front' );
				get_template_part( 'template-parts/front-page/seo' );
				get_template_part( 'template-parts/front-page/themes' );
				get_template_part( 'template-parts/front-page/categories' );
				get_template_part( 'template-parts/front-page/popular' );
				get_template_part( 'template-parts/front-page/results' );
				get_template_part( 'template-parts/front-page/banners' );
				get_template_part( 'template-parts/front-page/products' );
				get_template_part( 'template-parts/front-page/news' );
				get_template_part( 'template-parts/front-page/watch' );
				get_template_part( 'template-parts/front-page/banner' );
			?>

		</main>
	</div>

<?php
	get_footer();