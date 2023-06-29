<div class="popup popup--small list-popup" id="add_list">
	<div class="ut-loader"></div>
	<div class="popup__header">
		<h2 class="popup__title"><?php _e( 'Create a new wishlist', 'natures-sunshine' ); ?></h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
	</div>
	<div class="popup__body">
		<div class="form__row">
			<label for="list_name"><?php _e( 'Name of the new list', 'natures-sunshine' ); ?></label>
			<div class="form__input">
				<input type="text" name="list_name" id="list_name">
			</div>
		</div>
		<div class="form__row">
			<div class="form__input">
				<input type="checkbox" name="list_main" id="list_main" checked>
				<label for="list_main"><?php _e( 'Make List Primary', 'natures-sunshine' ); ?></label>
			</div>
		</div>
	</div>
	<button type="button" class="popup__action btn btn-green create_wishlist">
		<?php _e( 'Add', 'natures-sunshine' ); ?>
	</button>
</div>