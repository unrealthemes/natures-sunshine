<?php 
$get_all_shipping_zones = ut_help()->address->get_all_shipping_zones();
?>

<div class="popup cart-popup popup-address" id="add-address">
	<div class="ut-loader"></div>
	<div class="popup__header">
		<h2 class="popup__title add-title"><?php _e('Add address', 'natures-sunshine'); ?></h2>
		<h2 class="popup__title upd-title"><?php _e('Edit address', 'natures-sunshine'); ?></h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
	</div>
	<form id="add_address_form" class="form popup__form form-address" action="" method="post">

		<input type="hidden" name="nova_poshta_city_code" id="nova_poshta_city_code" value="">
		<!-- <input type="hidden" name="justin_city_code" id="justin_city_code" value=""> -->
		<input type="hidden" name="ukr_city_code" id="ukr_city_code" value="">
				
		<input type="hidden" id="address_id" name="address_id" value="">
		<input type="hidden" id="type" name="type" value="pickup">
		<input type="hidden" id="billing_city" name="billing_city" value="">
		<input type="hidden" name="address_city_code" id="address_city_code" value="">
		<input type="hidden" name="main_warehouse_code" id="main_warehouse_code" value="">

		<div class="form-checkout__row">
			<div class="form-checkout__input">
				<label for="city"><?php _e('Choose city', 'natures-sunshine'); ?></label>
				<button type="button" class="form-checkout__city btn w-100" data-city="<?php _e('Choose', 'natures-sunshine'); ?>">
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

		<div class="form-checkout__row form-checkout__tabs-panels">

			<div class="form-checkout__tabs-panel active" id="pickup">
				<div class="form-checkout__row">
					<div class="ut-loader"></div>

					<?php
					foreach ( $get_all_shipping_zones as $zone ) :
						$i = 1;
						$zone_shipping_methods = $zone->get_shipping_methods();
						foreach ( $zone_shipping_methods as $index => $method ) :
							if ( ! $method->is_enabled() ) :
								continue;
							endif;
							$shipping_method = preg_replace("/[^A-Za-z_ ]/", '', $method->id);
							// $method_is_taxable = $method->is_taxable();
							$method_instance_id = $method->get_instance_id();
							// $method_title = $method->get_method_title(); // e.g. "Flat Rate"
							// $method_description = $method->get_method_description();
							// $method_user_title = $method->get_title(); // e.g. whatever you renamed "Flat Rate" into
							$method_rate_id = $method->get_rate_id(); // e.g. "flat_rate:18"
							$checked = ( $i == 1 ) ? 'checked' : '';
							?>

								<div class="form-checkout__radio">
									<input class="form-checkout__radio-input shipping_method" 
										   type="radio" 
										   name="shipping_method" 
										   value="<?php echo esc_attr( $method->id . ':' . $method_instance_id ); ?>"
										   data-value="<?php echo esc_attr( $method->id ); ?>"
										   id="<?php echo $method_instance_id; ?>"
										   <?php echo $checked; ?>>
									<label class="form-checkout__radio-label" for="<?php echo $method_instance_id; ?>">
										<span class="form-checkout__radio-content">
											<?php echo $method->get_title(); ?>
										</span>
									</label>
								</div>

								<?php if ( $shipping_method == 'nova_poshta_shipping_method' ) : ?>
									<div id="nova_poshta_warehouse" class="form-checkout__select" style="margin-bottom: 16px;">
										<select name="nova_poshta_warehouse" class="js-basic-single">
											<option value="" hidden><?php _e('Select branch', 'natures-sunshine'); ?></option>
										</select>
									</div>
								<?php endif; ?>
								
								<?php if ( $shipping_method == 'nova_poshta_shipping_method_poshtomat' ) : ?>
									<div id="nova_poshta_poshtomat" class="form-checkout__select" style="margin-bottom: 16px;">
										<select name="nova_poshta_poshtomat" class="js-basic-single">
											<option value="" hidden><?php _e('Choose a parcel terminal', 'natures-sunshine'); ?></option>
										</select>
									</div>
								<?php endif; ?>

								<?php if ( $shipping_method == 'ukrposhta_shippping' ) : ?>
									<div id="ukr_warehouses" class="form-checkout__select" style="margin-bottom: 16px;">
										<select name="ukr_warehouses" class="js-basic-single">
											<option value="" hidden><?php _e('Choose a parcel terminal', 'natures-sunshine'); ?></option>
										</select>
									</div>
								<?php endif; ?>

							<?php
							$i++;
						endforeach; 
					endforeach;
					?>

				</div>
			</div>

			<div class="form-checkout__tabs-panel" id="delivery">
				<h2 class="form-checkout__subtitle no-number"><?php _e('Delivery address', 'natures-sunshine'); ?></h2>
				<div class="form-checkout__row">
					<label for="billing_address_1"><?php _e('Street', 'natures-sunshine'); ?></label>
					<div class="form__input">
						<input type="text" 
							name="billing_address_1" 
							id="billing_address_1" 
							value=""
							>
					</div>
				</div>
				<div class="form-checkout__row form-checkout__row--half">
					<div class="form-checkout__input">
						<label for="billing_address_2"><?php _e('House', 'natures-sunshine'); ?></label> 
						<input type="text" 
							name="billing_address_2" 
							id="billing_address_2" 
							value=""
							>
					</div>
					<div class="form-checkout__input">
						<label for="billing_address_3"><?php _e('Flat', 'natures-sunshine'); ?></label>
						<input type="text" 
							name="billing_address_3" 
							id="billing_address_3"
							value="">
					</div>
				</div>
				<!-- <div class="form-checkout__row form-checkout__row--half">
					<div class="form-checkout__input">
						<label for="delivery_date"><?php _e('Delivery date', 'natures-sunshine'); ?></label>
						<div class="form-checkout__select select">
							<select name="delivery_date" id="delivery_date">
								<option value="jan24">Сегодня, 24 января</option>
								<option value="jan25">25 января</option>
								<option value="jan26">26 января</option>
								<option value="jan27">27 января</option>
								<option value="jan28">28 января</option>
							</select>
						</div>
					</div>
					<div class="form-checkout__input">
						<label for="delivery_time"><?php _e('Time of delivery', 'natures-sunshine'); ?></label>
						<div class="form-checkout__select select">
							<select name="delivery_time" id="delivery_time">
								<option value="9-11">09:00-11:00, 399 ₴</option>
								<option value="11-13">11:00-13:00, 399 ₴</option>
								<option value="13-15">13:00-15:00, 399 ₴</option>
								<option value="15-17">15:00-17:00, бесплатно</option>
								<option value="17-19">17:00-19:00, 449 ₴</option>
							</select>
						</div>
					</div>
				</div> -->
			</div>

		</div>

		<h2 class="form-checkout__subtitle no-number"><?php _e('Enter the details of the recipient of the order', 'natures-sunshine'); ?></h2>
		<div class="form-checkout__row">
			<label for="billing_first_name"><?php _e('First name', 'natures-sunshine'); ?></label>
			<div class="form__input">
				<input type="text" 
					   name="billing_first_name" 
					   id="billing_first_name" 
					   placeholder="<?php _e('Konstantin', 'natures-sunshine'); ?>"  
					   >
			</div>
		</div>
		<div class="form-checkout__row">
			<label for="surname"><?php _e('Last name', 'natures-sunshine'); ?></label>
			<div class="form__input">
				<input type="text" 
					   name="billing_last_name" 
					   id="billing_last_name" 
					   placeholder="<?php _e('Constantinople', 'natures-sunshine'); ?>" 
					   >
			</div>
		</div>
		<div class="form-checkout__row">
			<label for="middlename"><?php _e('Middle name', 'natures-sunshine'); ?></label>
			<div class="form__input">
				<input type="text" 
					   name="patronymic" 
					   id="patronymic" 
					   placeholder="<?php _e('Konstantinovich', 'natures-sunshine'); ?>">
			</div>
		</div>
		<div class="form-checkout__row form-checkout__row--half">
			<div class="form-checkout__input">
				<label for="email"><?php _e('Email', 'natures-sunshine'); ?></label>
				<div class="form__input">
					<input type="email" 
						   name="billing_email" 
						   id="billing_email" 
						   placeholder="example@domain.ru" 
						   >
				</div>
			</div>
			<div class="form-checkout__input">
				<label for="phone"><?php _e('Phone number', 'natures-sunshine'); ?></label>
				<div class="form__input">
					<input type="text" 
						   class="mask-js"
						   name="billing_phone" 
						   id="billing_phone" 
						   >
				</div>
			</div>
		</div>

		<?php get_template_part( 'template-parts/alert' ); ?>

		<button type="submit" class="popup__action btn btn-green add-btn">
			<?php _e('Add new address', 'natures-sunshine'); ?>
		</button>
		
		<button type="submit" class="popup__action btn btn-green upd-btn">
			<?php _e('Update address', 'natures-sunshine'); ?>
		</button>

	</form>
</div>