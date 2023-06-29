<?php
	/*
	 * Template Name: User
	 *
	 * */

	get_header(); ?>

	<div class="wrapper">
		<main class="main">

			<?php // do_action( 'echo_kama_breadcrumbs' ); ?>
			
			<?php get_template_part( 'template-parts/user/content' ); ?>

		</main> 
	</div>

<?php
	get_footer();