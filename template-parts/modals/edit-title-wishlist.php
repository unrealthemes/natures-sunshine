<div class="popup popup--small list-popup" id="edit_title">
    <div class="ut-loader"></div>
	<div class="popup__header">
		<h2 class="popup__title"><?php _e( 'Edit title', 'natures-sunshine' ); ?></h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
	</div>
	<div class="popup__body">
		<div class="form__row">
			<div class="form__input">
				<input type="hidden" name="wishlist_id" id="wishlist_id">
				<input type="text" name="edit_name" id="edit_name">
			</div>
		</div>
	</div>
	<button type="submit" class="popup__action btn btn-green edit_name_wishlist">
        <?php _e( 'Save', 'natures-sunshine' ); ?>
    </button>
</div>