<?php
	/*
	 * Template Name: Cart
	 *
	 * */

	get_header( 'order' ); ?>

<div class="wrapper wrapper-cart">
	<main class="main">

		<form class="form form-cart">

			<?php
				get_template_part( 'template-parts/cart/products' );
				get_template_part( 'template-parts/cart/sidebar' );
				get_template_part( 'template-parts/cart/popups' );
			?>

		</form>

	</main>
</div>

<?php
get_footer('empty');
