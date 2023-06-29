<div class="popup cart-popup" id="joint_exit">
	<div class="ut-loader"></div>
	<div class="popup__header">
		<h2 class="popup__title"><?php _e('Are you sure you want to go out?', 'natures-sunshine'); ?></h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
		<p class="popup__header-text text"><?php _e('All carts will be deleted except yours', 'natures-sunshine'); ?></p>
	</div>
	<button type="button" class="popup__action btn btn-green exit-join-cart-js"><?php _e('Exit', 'natures-sunshine'); ?></button>
</div>