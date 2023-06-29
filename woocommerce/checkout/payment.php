<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.3
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>

<div id="payment" class="woocommerce-checkout-payment">
	<div class="form-checkout__block">

		<h2 class="form-checkout__subtitle">
			<?php _e('What is the most convenient way to pay for an order', 'natures-sunshine'); ?>
		</h2>

		<div class="form-checkout__row form-checkout__notice form-checkout__notice--green">
			<svg width="20" height="20">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
			</svg>
			<?php _e('When delivered by taxi service, only online payment is available', 'natures-sunshine'); ?>
		</div>

		<?php if ( WC()->cart->needs_payment() ) : ?>

			<div class="form-checkout__row">

				<?php
				if ( ! empty( $available_gateways ) ) {
					foreach ( $available_gateways as $gateway ) {
						wc_get_template( 'checkout/payment-method.php', ['gateway' => $gateway] );
					}
				} else {
					echo '<div class="form-checkout__radio wc_payment_method">
							  <label class="form-checkout__radio-label" for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
								  <span class="form-checkout__radio-content">
								      ' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '
								  </span>
							  </label>
						  </div>';
				}
				?>

			</div>

		<?php endif; ?>

	</div>
</div>

<?php
if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
