<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

if ( is_user_logged_in() ) {
	$user_id = get_current_user_id();
	$user = get_userdata( $user_id );
	$first_name = $user->first_name;
	$last_name = $user->last_name;
	$middle_name = get_user_meta( $user_id, 'patronymic', true );
	$email = $user->user_email;
	$phone = get_user_meta( $user_id, 'billing_phone', true );
	$street = get_user_meta( $user_id, 'billing_address_1', true );
	$house = get_user_meta( $user_id, 'billing_address_2', true );
	$flat = get_user_meta( $user_id, 'billing_address_3', true );
} else {
	$first_name = '';
	$last_name = '';
	$middle_name = '';
	$email = '';
	$phone = '';
	$street = '';
	$house = '';
	$flat = '';
}

$td_dates = ut_help()->time_delivery->generate_dates();
$td_filter_dates = ut_help()->time_delivery->remove_empty_days($td_dates);
$html_options = ut_help()->time_delivery->generate_time_options($td_filter_dates);
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout form form-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<div class="form-checkout__inner">
		<div class="form-checkout__fields">

			<?php if ( is_user_logged_in() || WC()->session->get('register_id_auth') || isset($_COOKIE['register_id_auth']) ) : ?>

				<input type="hidden" name="nova_poshta_city_code" id="nova_poshta_city_code" value="">
				<!-- <input type="hidden" name="justin_city_code" id="justin_city_code" value=""> -->
				<input type="hidden" name="ukr_city_code" id="ukr_city_code" value="">
				<input type="hidden" name="main_warehouse_code" id="main_warehouse_code" value="">
				<input type="hidden" name="billing_city" id="billing_city" value="">
				<input type="hidden" name="city_code" id="city_code" value="">
				<input type="hidden" name="warehouse" id="warehouse" value="">
				<!-- Отделение Justin -->
				<!-- <input type="hidden" name="justin_shipping_method_city" id="justin_shipping_method_city" value=""> -->
				<!-- Почтомат и Отделение Нова Пошта -->
				<input type="hidden" name="billing_nova_poshta_warehouse" id="billing_nova_poshta_warehouse" value="">
				<!-- CITY Нова Пошта -->
				<input type="hidden" name="billing_nova_poshta_city" id="billing_nova_poshta_city" value="">
				<!-- CITY Укр Пошта -->
				<input type="hidden" name="ukrposhta_shippping_city" id="ukrposhta_shippping_city" value="">
				<input type="hidden" name="ukrposhta_shippping_warehouse" id="ukrposhta_shippping_warehouse" value="">
				<!-- ADDRESSES (Кур'єр) Нова Пошта -->
				<input type="hidden" name="billing_mrkvnp_patronymics" id="billing_mrkvnp_patronymics" value="<?php echo esc_attr($middle_name); ?>">
				<input type="hidden" name="billing_mrkvnp_street" id="billing_mrkvnp_street" value="<?php echo esc_attr($street); ?>">
				<input type="hidden" name="billing_mrkvnp_house" id="billing_mrkvnp_house" value="<?php echo esc_attr($house); ?>">
				<input type="hidden" name="billing_mrkvnp_flat" id="billing_mrkvnp_flat" value="<?php echo esc_attr($flat); ?>">

				<input type="hidden" id="type" name="type" value="pickup">
				<input type="hidden" name="old_billing_phone" value="<?php echo esc_attr($phone); ?>">

				<!-- SHIPPING -->
				<div class="form-checkout__block">

					<?php the_title('<h1 class="form-checkout__title">', '</h1>'); ?>

					<?php 
					if ( ! is_user_logged_in() && ( WC()->session->get('register_id_auth') || isset($_COOKIE['register_id_auth']) ) ) : 
						$register_id_auth = ( isset($_COOKIE['register_id_auth']) ) ? $_COOKIE['register_id_auth'] : WC()->session->get('register_id_auth');
					?>

						<div class="your-id">
							<h2><?php _e('Yor ID', 'natures-sunshine'); ?></h2>
							<b><?php echo $register_id_auth; ?></b>
							<button class="btn btn-green open-edit_id-js" data-fancybox="" data-src="#edit_id">
								<?php _e('Edit ID', 'natures-sunshine'); ?>
							</button>
						</div>

					<?php endif; ?>

					<h2 class="form-checkout__subtitle"><?php _e('How would you like to receive your order?', 'natures-sunshine'); ?></h2>

					<select name="billing_country" 
							id="billing_country" 
							class="country_to_state country_select select2-hidden-accessible hidden" 
							autocomplete="country" 
							data-placeholder="Выберите страну/регион…" 
							data-label="Страна/регион" 
							tabindex="-1" 
							aria-hidden="true">
						<option value="UA" selected="selected">Украина</option>
					</select>

					<div class="form-checkout__row">
						<div class="form-checkout__input">
							<label for="city"><?php _e('Choose city', 'natures-sunshine'); ?></label>
							<button type="button" class="form-checkout__city btn w-100" data-city="<?php _e('Choose', 'natures-sunshine'); ?>" data-fancybox="" data-src="#cities-popup">
								<?php _e('Choose', 'natures-sunshine'); ?>
							</button>
						</div>
					</div>

					<div class="form-checkout__row form-checkout__tabs no-bg">
						<div class="ut-loader"></div>
						<a href="#pickup" data-value="pickup" class="form-checkout__tabs-item form-checkout__tabs-item--icon active">
							<svg class="form-checkout__tabs-icon" width="24" height="24">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#store'; ?>"></use>
							</svg>
							<span class="form-checkout__tabs-content">
								<?php _e('Pickup', 'natures-sunshine'); ?> 
								<!-- <span>Сегодня, бесплатно</span> -->
							</span>
						</a>
						<a href="#delivery" data-value="delivery" class="form-checkout__tabs-item form-checkout__tabs-item--icon">
							<svg class="form-checkout__tabs-icon" width="24" height="24">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#motocycle'; ?>"></use>
							</svg>
							<span class="form-checkout__tabs-content">
								<?php _e('Delivery', 'natures-sunshine'); ?> 
								<!-- <span>Сегодня 399 ₴</span> -->
							</span>
						</a>
					</div>

					<?php wc_get_template_part( 'checkout/saved-addresses' );  ?>

					<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

						<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

						<?php // wc_cart_totals_shipping_html(); ?>

						<div class="form-checkout__row form-checkout__tabs-panels"> 

							<div class="form-checkout__tabs-panel active" id="pickup">
								<div class="form-checkout__row">
									<div class="ut-loader"></div>
									<?php ut_help()->address->cart_totals_shipping_html( 'pickup' ); ?>

								</div>
							</div>

							<div class="form-checkout__tabs-panel" id="delivery">
								<div class="form-checkout__row">
									
									<?php // ut_help()->address->cart_totals_shipping_html( 'delivery' ); ?>

								</div>

								<h2 class="form-checkout__subtitle no-number"><?php _e('Delivery address', 'natures-sunshine'); ?></h2>
								<div class="form-checkout__row">
									<label for="billing_address_1"><?php _e('Street', 'natures-sunshine'); ?></label>
									<div class="form__input">
										<input type="text" 
											name="billing_address_1" 
											id="billing_address_1" 
											value="<?php echo esc_attr($street); ?>"
											required>
									</div>
								</div>
								<div class="form-checkout__row form-checkout__row--half">
									<div class="form-checkout__input">
										<label for="billing_address_2"><?php _e('House', 'natures-sunshine'); ?></label> 
										<input type="text" 
											name="billing_address_2" 
											id="billing_address_2" 
											value="<?php echo esc_attr($house); ?>"
											required>
									</div>
									<div class="form-checkout__input">
										<label for="billing_address_3"><?php _e('Flat', 'natures-sunshine'); ?></label>
										<input type="text" 
											name="billing_address_3" 
											id="billing_address_3"
											value="<?php echo esc_attr($flat); ?>">
									</div>
								</div>

								<div class="form-checkout__row form-checkout__row--half valid-td-section">
									<div class="form-checkout__input">
										<label for="delivery_date"><?php _e('Delivery date', 'natures-sunshine'); ?></label>
										<div class="form-checkout__select select">
											<select name="delivery_date" id="delivery_date">
												<option value=""><?php esc_html_e('Select date', 'natures-sunshine'); ?></option>

												<?php 
												foreach ( (array)$td_filter_dates as $td_date => $td_times ) : 
													$td_date_f = date_i18n("j F", strtotime($td_date));	
												?>

													<option value="<?php echo esc_attr($td_date); ?>" <?php echo ( (WC()->session->get('delivery_date') == $td_date) ? 'selected' : '' ); ?>>
														<?php echo strtolower($td_date_f); ?>
													</option>

												<?php endforeach; ?>

											</select>
										</div>
									</div>
									<div class="form-checkout__input delivery-time">
										<label for="delivery_time"><?php _e('Time of delivery', 'natures-sunshine'); ?></label>
										<div class="form-checkout__select select">
											<select name="delivery_time" id="delivery_time">

												<?php echo $html_options; ?>

											</select>
										</div>
									</div>

									<?php if ( empty($td_filter_dates) ) : ?>
										<span class="error-field-td">
											<?php echo esc_html( __('Delivery is impossible. Please choose another delivery method!', 'natures-sunshine') ); ?>
										</span>
									<?php endif; ?>

								</div>

							</div>
						</div>

						<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

					<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

						<p class="form-checkout__results-item">
							<span class="text color-mono-64">
								<?php esc_html_e( 'Shipping', 'woocommerce' ); ?>
							</span>
							<span class="text">
								<?php woocommerce_shipping_calculator(); ?>
							</span>
						</p>

					<?php endif; ?>

				</div>

				<!-- PAYMENT METHODS -->
				<?php do_action( 'woocommerce_review_order_submit_natures' ); ?>

				<!-- PROMOCOD -->
				<div class="form-checkout__block coupon-wrapper">
					<div class="ut-loader"></div>
					<h2 class="form-checkout__subtitle"><?php _e('Order discounts', 'natures-sunshine'); ?></h2>
					<div class="form-checkout__row">
						<div class="form-checkout__checkbox">
							<input class="form-checkout__checkbox-input" type="checkbox" name="promo" id="promo">
							<label class="form-checkout__checkbox-label" for="promo"><?php _e('Promo code', 'natures-sunshine'); ?></label>
						</div>
						<div class="form-checkout__collapse">
							<div class="form-checkout__code">
								<label for="code" hidden><?php _e('Enter promo code number', 'natures-sunshine'); ?></label>
								<input type="text" name="code" id="code" placeholder="<?php _e('Enter promo code number', 'natures-sunshine'); ?>">
								<button type="button" class="form-checkout__code-button btn btn-green coupon-js"><?php _e('Apply', 'natures-sunshine'); ?></button>
							</div>
						</div>
					</div>
				</div>

				<!-- FIELDS -->
				<div class="form-checkout__block">
					<h2 class="form-checkout__subtitle"><?php _e('Enter the details of the recipient of the order', 'natures-sunshine'); ?></h2>

					<div class="form-checkout__row">
						<label for="billing_last_name"><?php _e('Last name', 'natures-sunshine'); ?></label>
						<div class="form__input">
							<input type="text" 
								name="billing_last_name" 
								id="billing_last_name" 
								placeholder="<?php _e('Constantinople', 'natures-sunshine'); ?>" 
								value="<?php echo esc_attr($last_name); ?>"
								required>
						</div>
					</div>
					<div class="form-checkout__row">
						<label for="billing_first_name"><?php _e('First name', 'natures-sunshine'); ?></label>
						<div class="form__input">
							<input type="text" 
								name="billing_first_name" 
								id="billing_first_name" 
								placeholder="<?php _e('Konstantin', 'natures-sunshine'); ?>" 
								value="<?php echo esc_attr($first_name); ?>"
								required>
						</div>
					</div>
					<div class="form-checkout__row">
						<label for="patronymic"><?php _e('Middle name', 'natures-sunshine'); ?></label>
						<div class="form__input">
							<input type="text" 
								name="patronymic" 
								id="patronymic" 
								placeholder="<?php _e('Konstantinovich', 'natures-sunshine'); ?>"
								value="<?php echo esc_attr($middle_name); ?>">
						</div>
					</div>
					<div class="form-checkout__row form-checkout__row--half">
						<div class="form-checkout__input">
							<label for="billing_email"><?php _e('Email', 'natures-sunshine'); ?></label>
							<div class="form__input">
								<input type="email" 
									name="billing_email" 
									id="billing_email" 
									placeholder="example@domain.ru" 
									value="<?php echo $email; ?>"
									required>
							</div>
						</div>
						<div class="form-checkout__input">
							<label for="billing_phone"><?php _e('Phone number', 'natures-sunshine'); ?></label>
							<div class="form__input">
								<input type="text" 
									class="mask-js"
									name="billing_phone" 
									id="billing_phone" 
									value="<?php echo esc_attr($phone); ?>"
									required>
							</div>
						</div>
					</div>
					<div class="form-checkout__row form-checkout__notice form-checkout__notice--pt">
						<svg width="20" height="20">
							<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
						</svg>
						<?php _e('When paying for a purchase on the site, please indicate the name of the recipient. If the goods will pick up another person (not the customer) - indicate his name. Orders are issued upon presentation of any document, identity card or bank card with which the order was paid.', 'natures-sunshine'); ?>
					</div>
				</div>

				<!-- ADDITION FIELDS -->
				<div class="form-checkout__block">
					<h2 class="form-checkout__subtitle"><?php _e('Additionally', 'natures-sunshine'); ?></h2>
					<div class="form-checkout__row">
						<div class="form-checkout__checkbox">
							<input class="form-checkout__checkbox-input" type="checkbox" name="callback" id="callback">
							<label class="form-checkout__checkbox-label" for="callback"><?php _e('Manager`s call', 'natures-sunshine'); ?></label>
						</div>
					</div>
					<div class="form-checkout__row form-checkout__notice">
						<svg width="20" height="20">
							<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
						</svg>
						<?php _e('Attention! If the checkbox is not checked, the order will be processed and sent without a call from the manager!', 'natures-sunshine'); ?>
					</div>
					<div class="form-checkout__row">
						<label for="order_comments"><?php _e('Comment to the order', 'natures-sunshine'); ?></label>
						<div class="form__input">
							<textarea name="order_comments" id="order_comments" rows="5" placeholder="<?php _e('Message', 'natures-sunshine'); ?>"></textarea>
						</div>
					</div>
				</div>

			<?php else : ?>

				<?php
				get_template_part(
					'woocommerce/checkout/login', 
					null, 
					[]
				); 
				?>

			<?php endif; ?>

		</div>

		<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
	
		<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

		<?php do_action( 'woocommerce_checkout_order_review' ); ?>

		<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
