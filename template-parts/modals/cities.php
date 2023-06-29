<div class="popup popup-checkout" id="cities-popup">
	<div class="ut-loader"></div>
	<div class="popup__header">
		<h2 class="popup__title"><?php _e('Choose city', 'natures-sunshine'); ?></h2>
		<button class="popup__close js-close-popup"> 
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
	</div>
	<form class="form location form-cities" autocomplete="off">
		<ul class="location-list">
			<li class="location-list__item"
				data-np-code="8d5a980d-391c-11dd-90d9-001a92567626"
				data-justin="32b69b95-9018-11e8-80c1-525400fb7782"
				data-ukr="29713">
				<span class="location-list__item-link">
					<?php _e('Kyiv', 'natures-sunshine'); ?>
				</span>
			</li>
			<li class="location-list__item"
				data-np-code="db5c88e0-391c-11dd-90d9-001a92567626"
				data-justin="e7ebcef9-dbfb-11e7-80c6-00155dfbfb00"
				data-ukr="24550">
				<span class="location-list__item-link">
					<?php _e('Kharkiv', 'natures-sunshine'); ?>
				</span>
			</li>
			<li class="location-list__item"
				data-np-code="db5c88d0-391c-11dd-90d9-001a92567626"
				data-justin="e156e3c8-dbfb-11e7-80c6-00155dfbfb00"
				data-ukr="17069">
				<span class="location-list__item-link">
					<?php _e('Odessa', 'natures-sunshine'); ?>
				</span>
			</li>
			<li class="location-list__item"
				data-np-code="db5c88f0-391c-11dd-90d9-001a92567626"
				data-justin="45ebd1f0-dbfd-11e7-80c6-00155dfbfb00"
				data-ukr="3641">
				<span class="location-list__item-link">
					<?php _e('Dnipro', 'natures-sunshine'); ?>
				</span>
			</li>
			<li class="location-list__item"
				data-np-code="db5c88c6-391c-11dd-90d9-001a92567626"
				data-justin="8703ddbd-dbfd-11e7-80c6-00155dfbfb00"
				data-ukr="8968">
				<span class="location-list__item-link"
				><?php _e('Zaporozhye', 'natures-sunshine'); ?>
			</span>
			</li>
			<li class="location-list__item"
				data-np-code="db5c88f5-391c-11dd-90d9-001a92567626"
				data-justin="8d1051d8-dbfd-11e7-80c6-00155dfbfb00"
				data-ukr="14288">
				<span class="location-list__item-link">
					<?php _e('Lviv', 'natures-sunshine'); ?>
				</span>
			</li>
		</ul> 
		<div class="form__row">
			<label for="city"><?php _e('Enter the locality of Ukraine', 'natures-sunshine'); ?></label>
			<div class="form__input location-city">

				<input type="hidden" name="city_shipping_method" id="city_shipping_method" value="">
				<input type="text" name="city" id="city" value="" placeholder="<?php _e('Choose city', 'natures-sunshine'); ?>">

				<ul class="location-cities"></ul>
			</div>
			<div class="location-example">
				<p class="location-example__text">
					<?php 
						echo sprintf( 
							__('For example, %1sCrimea%2s', 'natures-sunshine'),
							'<span class="location-example__place">',
							'</span>'
						);
					?>
				</p>
			</div>
		</div>
		<div class="form__row" style="text-align: right;">
			<button type="button" class="form__button btn btn-green js-choose-city"><?php _e('Apply', 'natures-sunshine'); ?></button>
		</div>
	</form>
</div>
