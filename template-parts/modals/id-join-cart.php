<div class="popup cart-popup" id="cart_id">
    <div class="ut-loader"></div>
	<div class="popup__header">
		<h2 class="popup__title"><?php _e('There is no such ID yet', 'natures-sunshine'); ?></h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
		<p class="popup__header-text text"><?php _e('Create cart on ID', 'natures-sunshine'); ?> – <span class="partner-id"></span>?</p>
	</div>

    <?php get_template_part('template-parts/alert', null, []); ?>

    <input type="hidden" id="add_new_partner_id" value="">
	<button type="button" class="popup__action btn btn-green add-new-partner-id-js"><?php _e('Create', 'natures-sunshine'); ?></button>
</div>