<?php 
$register_id_auth = ( isset($_COOKIE['register_id_auth']) ) ? $_COOKIE['register_id_auth'] : WC()->session->get('register_id_auth');
?>

<div class="popup popup-checkout" id="edit_id">
	<div class="ut-loader"></div>
	<div class="popup__header">
		<h2 class="popup__title"><?php _e('Edit your ID', 'natures-sunshine'); ?></h2>
		<button class="popup__close js-close-popup"> 
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
	</div>
	<form id="form_edit_id" class="form form-edit-id" action="" method="POST">
        <div class="popup__row form__row">
            <div class="form__input">
                <input name="register_id" id="register_id" value="<?php echo $register_id_auth; ?>">
            </div>
        </div>
		<div class="form__row" style="text-align: right;">
			<button type="submit" class="form__button btn btn-green">
                <?php _e('Apply', 'natures-sunshine'); ?>
            </button>
		</div>
	</form>
</div>
