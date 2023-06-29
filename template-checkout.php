<?php
	/*
	 * Template Name: Checkout
	 *
	 * */

	get_header( 'order' ); ?>

<div class="wrapper wrapper-cart">
	<main class="main">


		<?php
			get_template_part( 'template-parts/checkout/form' );
			get_template_part( 'template-parts/checkout/popup' );
		?>

	</main>
</div>
