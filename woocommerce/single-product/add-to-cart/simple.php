<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

$products_list = ut_help()->compare->products_list();
$type_view = get_field('type_view_product_price', 'options');
$class_modal = ( WC()->session->get('partner_ids') ) ? 'modal' : 'not-modal';

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : 
?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

    <form class="form product-info__buy card__controls card__controls--mobile cart"
          action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
          method="post"
          enctype='multipart/form-data'>

        <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<?php
		do_action( 'woocommerce_before_add_to_cart_quantity' );

		woocommerce_quantity_input(
			array(
                'location'    => 'single_poduct',
				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
				'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
			)
		);

		do_action( 'woocommerce_after_add_to_cart_quantity' );
		?>

        <?php
        if ( $type_view == 3 && ! is_user_logged_in() ) :
            $redirect_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $login_url = ut_get_permalik_by_template('template-login.php') . '?redirect_url=' . $redirect_url;
        ?>
            <a class="card__controls-buy btn btn-green" href="<?php echo $login_url; ?>">
                <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
            </a>
        <?php elseif ( WC()->session->get('partner_ids') ) : ?>
            <button type="submit"
                    name="add-to-cart"
                    value="<?php echo esc_attr( $product->get_id() ); ?>"
                    data-fancybox="" 
                    data-src="#select_cart"
                    class="card__controls-buy btn btn-green single_add_to_cart_button <?php echo $class_modal; ?>">
                    <!-- <span class="card__controls-buy-mobile">&nbsp;· 1190 ₴</span> -->
                    <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
            </button>
        <?php else : ?>
            <button type="submit"
                    name="add-to-cart"
                    value="<?php echo esc_attr( $product->get_id() ); ?>"
                    class="card__controls-buy btn btn-green single_add_to_cart_button <?php echo $class_modal; ?>">
                    <!-- <span class="card__controls-buy-mobile">&nbsp;· 1190 ₴</span> -->
                    <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
            </button>
        <?php endif; ?>

        <?php
        get_template_part(
            'template-parts/favorites/icon',
            null, [
                'product_id' => $product->get_id(),
                'is_product_in_wishlist' => ut_help()->wishlist->is_product_in_wishlist( $product->get_id() ),
            ]
        );
        ?>

        <?php
        get_template_part(
            'template-parts/compare/icon',
            null,
            [
                'product_id' => $product->get_id(),
                'products_list' => $products_list,
            ]
        );
        ?>

        <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

    </form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
