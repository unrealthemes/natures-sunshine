<div class="popup popup--small delete-popup" id="change_main_phone_email">
    <div class="ut-loader"></div>
	<div class="popup__header">
		<h2 class="popup__title"><?php _e('Change email and phone', 'natures-sunshine'); ?></h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
	</div>
	<form id="change_main_phone_email_form" class="form popup__form" action="" method="post">

		<label for="phone"><?php _e('Main number', 'natures-sunshine'); ?></label>
		<div class="form__input">
			<input type="text" class="mask-js" name="phone" id="phone" value="">
		</div><br/>
		
		<label for="email"><?php _e('Email', 'natures-sunshine'); ?></label>
		<div class="form__input">
			<input type="email" name="email" id="email" value="">
		</div><br/>

		<?php get_template_part( 'template-parts/alert' ); ?>

		<button type="submit" class="popup__action btn btn-secondary"><?php _e('Save', 'natures-sunshine'); ?></button>

	</form>
</div>