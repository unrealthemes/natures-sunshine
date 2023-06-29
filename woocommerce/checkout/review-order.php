<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

global $order_button_text;

$my_total_price = 0;
$my_total_pv = 0;
$type_cart = WC()->session->get('joint_order_mode');
$total_txt = ut_num_decline( 
    WC()->cart->get_cart_contents_count(), 
    [ 
        __('product 1', 'natures-sunshine'),
        __('product 2', 'natures-sunshine'),
        __('product 3', 'natures-sunshine')
    ] 
); 
$total_pv = 0; 
$page_id = get_option( 'woocommerce_checkout_page_id' );
$txt_under_btn = get_field('txt_under_btn_checkout', $page_id);
$tooltip_txt = get_field('tooltip_checkout', $page_id);
?>

<div class="form-checkout__sidebar">

	<!-- <a href="<?php // echo esc_url( wc_get_cart_url() ); ?>" class="form-checkout__link btn btn-white"> -->
	<a href="<?php echo home_url('/cart/'); ?>" class="form-checkout__link btn btn-white">
		<?php _e('Back to cart', 'natures-sunshine'); ?>
	</a>

	<div class="form-checkout__total">
		<div class="ut-loader"></div>
		<p class="form-checkout__total-title text"><?php _e('Total', 'natures-sunshine'); ?></p>

		<div class="form-checkout__results">

			<?php 
			if ( $type_cart ) : 
				$user_id = get_current_user_id();
				$register_id = get_user_meta( $user_id, 'register_id', true );
				$partner_ids = WC()->session->get('partner_ids');
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :         
					if ( isset($cart_item['partner_id']) ) :
						continue;
					endif;
					$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
						$my_total_price += $_product->get_price() * $cart_item['quantity'];
						$pv = get_post_meta( $cart_item['product_id'], '_pv', true );
						$my_total_pv += $pv * $cart_item['quantity'];
					endif;
				endforeach;
			?>

				<div class="cart-results__item">
					<span class="text color-mono-64 bold">
						<?php _e('Your cart', 'natures-sunshine'); ?> – <?php echo $register_id; ?>
					</span>
					<span class="text"><?php echo wc_price( $my_total_price ); ?></span>
				</div>

				<?php if ( $partner_ids ) : ?>
					<?php foreach ( (array)$partner_ids as $partner_id => $full_name ) : ?>

						<?php 
						$partner_total_price = 0;
						$partner_total_pv = 0;
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
							if ( isset($cart_item['partner_id']) && $cart_item['partner_id'] == $partner_id ) :
								$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
								if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
									$partner_total_price += $_product->get_price() * $cart_item['quantity'];
									$pv = get_post_meta( $cart_item['product_id'], '_pv', true );
									$partner_total_pv += $pv * $cart_item['quantity'];
								endif;
							endif;
						endforeach;
						$total_pv += $partner_total_pv;
						?>

						<div class="cart-results__item">
							<span class="text color-mono-64">
								<?php 
									if ( $full_name ) :
										echo sprintf(
											'%1s - %2s',
											$full_name,
											$partner_id
										);
									else :
										echo $partner_id;
									endif;
								?>
							</span>
							<span class="text"><?php echo wc_price( $partner_total_price ); ?></span>
						</div>

					<?php endforeach; ?>
				<?php endif; ?>

			<?php else : ?>

				<?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
                        $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                        $pv = get_post_meta( $cart_item['product_id'], '_pv', true );
                        $total_pv += $pv * $cart_item['quantity'];
                        ?>

                        <p class="form-checkout__results-item">
                            <span class="text color-mono-64">
                                <?php echo $cart_item['quantity']; ?> × <?php echo $product_name; ?>
                            </span> 
                            <span class="text">
                                <?php echo wc_price( $cart_item['line_subtotal'] ); ?>
                            </span>
                        </p>

                    <?php
                    endif;
                    
                endforeach;
                ?>

			<?php endif; ?>

			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<p class="form-checkout__results-item form-checkout__results-item--coupon">
					<span class="text color-mono-64">
						<button type="button" 
								class="cart-products__button btn remove-coupon-js" 
								aria-label="<?php _e('Remove Promo code'); ?>" 
								data-coupon="<?php echo $code; ?>">
							<svg width="18" height="18">
								<use xlink:href="<?php echo DIST_URI; ?>/images/sprite/svg-sprite.svg#trash"></use>
							</svg>
						</button>
						<?php wc_cart_totals_coupon_label( $coupon ); ?>
					</span>
					<span class="text">
						<?php ut_help()->checkout->checkout_totals_coupon_html( $coupon ); ?>
					</span>
				</p>
			<?php endforeach; ?>

			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
				<p class="form-checkout__results-item">
					<span class="text color-mono-64">
						<?php echo esc_html( $fee->name ); ?>
					</span>
					<span class="text">
						<?php wc_cart_totals_fee_html( $fee ); ?>
					</span>
				</p>
			<?php endforeach; ?>

			<?php
			if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
				$taxable_address = WC()->customer->get_taxable_address();
				$estimated_text  = '';

				if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
					/* translators: %s location. */
					$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
				}

				if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
					foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
						?>
						<p class="form-checkout__results-item">
							<span class="text color-mono-64">
								<?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
							<span class="text">
								<?php echo wp_kses_post( $tax->formatted_amount ); ?>
							</span>
						</p>
						<?php
					}
				} else {
					?>
					<p class="form-checkout__results-item">
						<span class="text color-mono-64">
							<?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
						<span class="text">
							<?php wc_cart_totals_taxes_total_html(); ?>
						</span>
					</p>
					<?php
				}
			}
			?>

			<?php 
			foreach( WC()->session->get('shipping_for_package_0')['rates'] as $method_id => $rate ) {

				if ( WC()->session->get('chosen_shipping_methods')[0] == $method_id ) {
					$rate_label = $rate->label; // The shipping method label name
					$rate_cost_excl_tax = floatval($rate->cost); // The cost excluding tax
					// The taxes cost
					$rate_taxes = 0;
					foreach ( $rate->taxes as $rate_tax ) {
						$rate_taxes += floatval($rate_tax);
					}
					// The cost including tax
					$rate_cost_incl_tax = $rate_cost_excl_tax + $rate_taxes;
					?>
					<p class="form-checkout__results-item">
						<span class="text color-mono-64">
							<?php echo $rate_label; ?>
						</span> 
						<span class="text">
							<?php echo WC()->cart->get_cart_shipping_total(); ?>
						</span>
					</p>
					<?php 
					break;
				}
			}
			?>

		</div>

		<div class="form-checkout__price">
			<span class="text color-mono-64"><?php _e('To pay', 'natures-sunshine'); ?></span> 
			<span class="form-checkout__price-total"><?php wc_cart_totals_order_total_html(); ?></span>
		</div>

		<noscript>
			<?php
			/* translators: $1 and $2 opening and closing emphasis tags respectively */
			printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
			?>
			<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
		</noscript>

		<?php // wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<?php 
		echo apply_filters( 
			'woocommerce_order_button_html', 
			'<button type="submit" 
					 class="button alt" 
					 name="woocommerce_checkout_place_order" 
					 id="place_order" 
					 value="' . esc_attr( $order_button_text ) . '" 
					 data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '--</button>' 
		); // @codingStandardsIgnoreLine 
		?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>

		<?php if ( $txt_under_btn ) : ?>
			<div class="form-checkout__info text color-mono-64">
				<?php echo $txt_under_btn; ?>
			</div>
		<?php endif; ?>

	</div>

	<?php $total_pv = $total_pv + $my_total_pv; ?> 

	<?php if ( $total_pv || $tooltip_txt ) : ?>
		<div class="form-checkout__points w-100">
			<div class="card__price-points">
				<img src="<?php echo DIST_URI; ?>/images/icons/nsp-logo.svg"
					loading="lazy" 
					decoding="async" 
					alt=""> 
				<?php echo $total_pv . ' ' . PV; ?>
			</div>
			<div class="tooltip" data-tooltip="<?php echo nl2br($tooltip_txt); ?>">
				<svg width="20" height="20">
					<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
				</svg>
			</div>
		</div>
	<?php endif; ?>

</div>