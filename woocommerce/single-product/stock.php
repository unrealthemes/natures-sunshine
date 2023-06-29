<?php
/**
 * Single Product stock.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/stock.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$notify_low_stock_amount = get_option('woocommerce_notify_low_stock_amount');
// outofstock | onbackorder | instock
if ( $product->get_manage_stock() && $product->get_stock_status() == 'instock' && $product->get_stock_quantity() <= $notify_low_stock_amount ) :

	$piece_list = [ 
		__('piece 1', 'natures-sunshine'),
		__('piece 2', 'natures-sunshine'),
		__('piece 3', 'natures-sunshine')
	];
	$piece_txt = ut_num_decline( $product->get_stock_quantity(), $piece_list );
	$availability = sprintf( __( '%s left', 'natures-sunshine'), $piece_txt );
	$status_class = 'product-info__available--less';

elseif ( $product->get_stock_status() == 'instock' ) :

	$availability = __('In stock', 'natures-sunshine');
	$status_class = 'product-info__available--yes';

else :

	$availability = $availability;
	$status_class = 'product-info__available--yes';

endif;
?>

<span class="product-info__available <?php echo esc_attr( $status_class ); ?> hidden-mobile <?php echo esc_attr( $class ); ?>">

	<?php if ( $product->get_manage_stock() && $product->get_stock_status() == 'instock' && $product->get_stock_quantity() <= $notify_low_stock_amount ) : ?>

		<svg width="24" height="24">
			<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#checkmark-circle-dashed'; ?>"></use>
		</svg>
	
	<?php elseif ( $product->get_stock_status() == 'instock' ) : ?>

		<svg width="24" height="24">
			<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#checkmark-circle'; ?>"></use>
		</svg>

	<?php else : ?>

		<svg width="24" height="24">
			<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#box'; ?>"></use>
		</svg>

	<?php endif; ?>

	<span><?php echo wp_kses_post( $availability ); ?></span>
</span>