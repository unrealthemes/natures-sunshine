<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); 

$redirect_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$type_cart = WC()->session->get('joint_order_mode');
$checked = ( $type_cart ) ? 'checked' : '';
$class = ( $type_cart ) ? 'join-cart' : 'simple-cart';
?>

<form class="form form-cart woocommerce-cart-form <?php echo $class; ?>" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

    <input type="hidden" name="redirect_url" id="redirect_url" value="<?php echo $redirect_url; ?>">
    <input class="switcher__input" type="checkbox" name="joint_order_mode" id="joint_order_mode" <?php echo $checked; ?>>

	<?php do_action( 'woocommerce_before_cart_table' ); ?>

    <div class="cart-products">

        <div class="cart-products__header cart-header">
            <?php if ( $type_cart ) : ?>
                <h1 class="cart-title"><?php _e('Joint cart', 'natures-sunshine'); ?></h1>
            <?php else : ?>
                <h1 class="cart-title"><?php _e('My cart', 'natures-sunshine'); ?></h1>
            <?php endif; ?>
	        <a href="/filter" class="cart-header__back"><?php _e('Back to shopping', 'natures-sunshine'); ?></a>
        </div>

        <?php 
        if ( $type_cart ) :
            wc_get_template_part( 'cart/join-cart-info' ); 
            wc_get_template_part( 'cart/join-cart-partner' );
            wc_get_template_part( 'cart/join-cart-items' );
            wc_get_template_part( 'cart/join-cart-partner-items' );
        else :
            wc_get_template_part( 'cart/cart-products-search' ); 
            wc_get_template_part( 'cart/cart-items' ); 
        endif;
        ?>

    </div>

    <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

    <?php
        /**
         * Cart collaterals hook.
         *
         * @hooked woocommerce_cross_sell_display
         * @hooked woocommerce_cart_totals - 10
         */
        do_action( 'woocommerce_cart_collaterals' );
    ?>

	<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<?php do_action( 'woocommerce_after_cart' ); ?>
