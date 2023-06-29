<div class="popup cart-popup delete-popup" id="delete-account">
	<div class="ut-loader"></div>
	<div class="popup__header">
		<h2 class="popup__title"><?php _e('Delete account permanently', 'natures-sunshine'); ?></h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
	</div>
	<form id="delete_account_form" class="form popup__form form-delete" action="" method="post">
		<input type="hidden" name="test_word" id="test_word" value="<?php _e('Delete', 'natures-sunshine'); ?>">
		<label class="warning" for="delete"><?php _e('Enter the word "Delete" to confirm', 'natures-sunshine'); ?></label>
		<div class="form__input">
			<input class="warning" type="text" name="delete" id="delete" placeholder="<?php _e('Delete', 'natures-sunshine'); ?>" required>
		</div>
		<button type="submit" class="popup__action btn btn-secondary" disabled><?php _e('Delete permanently', 'natures-sunshine'); ?></button>
	</form>
</div>