<?php
/*
 * Template Name: Главная
 *
 * */

if ( is_cart() && sizeof( WC()->cart->get_cart() ) == 0 ) :
	get_header('order');
else :
	get_header();
endif;
?>

<div class="wrapper">
	<main class="main">

		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
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