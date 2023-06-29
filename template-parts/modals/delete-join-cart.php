<div class="popup cart-popup" id="remove_cart">
	<div class="ut-loader"></div>
	<div class="popup__header">
		<h2 class="popup__title"><?php _e('Delete cart?', 'natures-sunshine'); ?></h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
		<p class="popup__header-text text"><?php _e('This action will delete the cart', 'natures-sunshine'); ?></p>
	</div>
	<input type="hidden" id="delete_partner_id" name="delete_partner_id" value="">
	<button type="button" class="popup__action btn btn-secondary delete-partner-js"><?php _e('Proceed', 'natures-sunshine'); ?></button>
</div>