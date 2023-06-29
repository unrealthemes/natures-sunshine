<div class="popup cart-popup delete-popup" id="change_password">
    <div class="ut-loader"></div>
	<div class="popup__header">
		<h2 class="popup__title"><?php _e('Change password', 'natures-sunshine'); ?></h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
	</div>
	<form id="change_password_form" class="form popup__form form-delete" action="" method="post">

		<label for="old_password"><?php _e('Old password', 'natures-sunshine'); ?></label>
		<div class="form__input">
			<input type="password" name="old_password" id="old_password" value="">
		</div><br/>
		
		<label for="password"><?php _e('Password', 'natures-sunshine'); ?></label>
		<div class="form__input">
			<input type="password" name="password" id="password" value="">
		</div><br/>

		<label for="repeat_password"><?php _e('Reapeat password', 'natures-sunshine'); ?></label>
		<div class="form__input">
			<input type="password" name="repeat_password" id="repeat_password" value="">
			<button type="button" class="form__input-control js-show-pass">
				<svg width="24" height="24" class="eye">
					<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#eye'; ?>"></use>
				</svg>
				<svg width="24" height="24" class="eye eye-off">
					<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#eye-off'; ?>"></use>
				</svg>
			</button>
		</div>

        <div class="form__notice text response"></div>

		<?php get_template_part( 'template-parts/alert' ); ?>

		<button type="submit" class="popup__action btn btn-secondary"><?php _e('Save', 'natures-sunshine'); ?></button>

	</form>
</div>