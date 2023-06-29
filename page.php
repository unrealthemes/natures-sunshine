<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

if ( is_cart() || is_checkout() ) :
	get_header('order');
else :
	get_header();
endif;

$wrapper_class = is_cart() || is_checkout() ? 'wrapper wrapper-cart' : 'wrapper';
?>

<div class="<?php echo $wrapper_class; ?>">
	<main class="main">

		<?php
		while ( have_posts() ) :
			the_post();

			if ( get_post_type() == 'page' && ! is_cart() && ! is_checkout() ) :
				get_template_part( 'template-parts/blog/content', 'page' );
			else :
				the_content();
			endif;

		endwhile; // End of the loop.
		?>

	</main>
</div>

<?php 
if ( is_cart() && sizeof( WC()->cart->get_cart() ) == 0 ) :
	get_footer('empty'); 
else :
	get_footer(); 
endif;

?>