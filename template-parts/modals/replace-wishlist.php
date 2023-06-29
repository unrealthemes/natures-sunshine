<?php 
$user_wishlists = YITH_WCWL()->get_current_user_wishlists();
?>

<div class="popup popup--small replace-popup" id="replace_list">
    <div class="ut-loader"></div>
	<div class="popup__header">
		<h2 class="popup__title"><?php _e('Move to list', 'natures-sunshine'); ?></h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
	</div>
	<label for="lists" hidden><?php _e('Lists', 'natures-sunshine'); ?></label>
	<div class="form__input select">
        <input type="hidden" name="wishlist_id_from" value="">
        <input type="hidden" name="product_ids" value="">
		<select name="lists" id="lists">
<!--            <option hidden>--><?php // _e('Select list', 'natures-sunshine'); ?><!--</option>-->

            <?php foreach ( $user_wishlists as $wishlist ) : ?>

                <option value="<?php echo $wishlist->get_id(); ?>">
                    <?php echo esc_html( $wishlist->get_formatted_name() ); ?>
                </option>

            <?php endforeach; ?>
            
		</select>
	</div>
</div>