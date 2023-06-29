<?php 
/**
	* The template for displaying 404 pages (not found)
	*
	* @link https://codex.wordpress.org/Creating_an_Error_404_Page
	*/

	get_header();
?>

<div class="page page__error-404">

	<section class="error-404 not-found">
		<img class="error-404__image" src="<?= DIST_URI . '/images/icons/404.svg'; ?>" width="96" height="152" loading="eager" decoding="async" alt="">
		<h1 class="error-404__title">404</h1>
		<p class="error-404__text text"><?php _e('Page not found', 'natures-sunshine'); ?></p>
		<span class="error-404__desc text color-mono-64"><?php _e('Wrong address typed or this page no longer exists on the site', 'natures-sunshine'); ?></span>
		<a href="<?= esc_url(home_url('/')); ?>" class="error-404__link btn btn-green"><?php _e('To home', 'natures-sunshine'); ?></a>
		<span class="error-404__notice text color-mono-64"><?php _e('or use search', 'natures-sunshine'); ?></span>
	</section>

</div>

<?php get_footer(); ?>