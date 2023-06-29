<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
// do_action( 'woocommerce_cart_is_empty' );
?>

<main class="main flex">
		<div class="container">
			<div class="cart-empty">
				<div class="cart-empty__image">
					<svg width="151" height="152">
						<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cart-empty'; ?>"></use>
					</svg>
				</div>
				<h1 class="cart-empty__title"><?php _e('Cart is empty', 'natures-sunshine'); ?></h1>

				<?php 
				if ( ! is_user_logged_in() ) : 
					$redirect_url = get_home_url() . '/account';
					$login_url = ut_get_permalik_by_template('template-login.php') . '?redirect_url=' . $redirect_url;
				?>

					<p class="cart-empty__text color-mono-64">
						<?php _e('If you filled your shopping cart during your last visit, please log in to see the selected items', 'natures-sunshine'); ?>
					</p>
					<a href="<?php echo $login_url; ?>" class="cart-empty__button btn btn-green">
						<?php _e('Login', 'natures-sunshine'); ?>
					</a>

				<?php endif; ?>
		</div>
	</div>
</main>