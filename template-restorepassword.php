<?php
	/* Template Name: Restore password */
	get_header('login'); ?>

<main class="main login-main">

	<?php 
	if ( wp_verify_nonce( $_GET['_wpnonce'], 'ut_reset_pass' ) ) : 
		$user_login = sanitize_text_field( $_GET['login'] );
	?>

		<div class="login-block login-block--restore">
			<div class="ut-loader"></div>
			<h2 class="login-title text-center"><?php _e('Password recovery', 'natures-sunshine'); ?></h2>
			<form id="new_password_form" action="" class="login-form form-restorepassword form" method="post">
				<input type="hidden" name="user_login" value="<?php echo $user_login; ?>">
				<div class="form__row">
					<label for="new_password"><?php _e('New password', 'natures-sunshine'); ?></label>
					<div class="form__input">
						<input type="password" class="dots" name="new_password" id="new_password" required>
						<button type="button" class="form__input-control js-show-pass">
							<svg width="24" height="24" class="eye">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#eye'; ?>"></use>
							</svg>
							<svg width="24" height="24" class="eye eye-off">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#eye-off'; ?>"></use>
							</svg>
						</button>
					</div>
				</div>
				<div class="form__row">
					<label for="repeat_password"><?php _e('Repeat password', 'natures-sunshine'); ?></label>
					<div class="form__input">
						<input type="password" class="dots" name="repeat_password" id="repeat_password" required>
						<button type="button" class="form__input-control js-show-pass">
							<svg width="24" height="24" class="eye">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#eye'; ?>"></use>
							</svg>
							<svg width="24" height="24" class="eye eye-off">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#eye-off'; ?>"></use>
							</svg>
						</button>
					</div>
				</div>
				<div id="new_pass_error" class="form__row">
					<span class="form__alert">
						<svg width="24" height="24" class="eye">
							<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#warning'; ?>"></use>
						</svg>
						<span></span>
					</span>
				</div>
				<div id="new_pass_success" class="form__row">
					<span class="form__alert">
						<svg width="24" height="24" class="eye">
							<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#warning'; ?>"></use>
						</svg>
						<span></span>
					</span>
				</div>
				<div class="form__row">
					<button type="submit" class="form__submit btn btn-green w-100"><?php _e('Restore password', 'natures-sunshine'); ?></button>
				</div>
			</form>
		</div>

	<?php endif; ?>

</main>

<?php
get_footer('empty');