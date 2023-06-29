<?php
/*
 * Template Name: Lost password
 *
 * */

get_header('login'); 
?>

<main class="main login-main">

	<div class="login-block login-block--restore reset-password">
		<div class="ut-loader"></div>
		<h2 class="login-title"><?php _e('Password recovery', 'natures-sunshine'); ?></h2>
		<form id="forgot_pass_form" action="" class="login-form form-lostpassword form" method="post">
			<div class="form__row">
				<label for="lostpassword_name"><?php _e('Email or ID', 'natures-sunshine'); ?></label>
				<div class="form__input">
					<input type="text" name="lostpassword_name" id="lostpassword_name" placeholder="Example@domain.com" required>
				</div>
			</div>
			<div id="forgot_pass_error" class="form__row">
				<span class="form__alert">
					<svg width="24" height="24" class="eye">
						<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#warning'; ?>"></use>
					</svg>
					<span></span>
				</span>
			</div>
			<div class="form__row">
				<button type="submit" class="form__submit btn btn-green w-100"><?php _e('Send', 'natures-sunshine'); ?></button>
			</div>
			<div class="form__row text-center">
				<a href="/login" class="form__link"><?php _e('Come back', 'natures-sunshine'); ?></a>
			</div>
		</form>
	</div>
	<!-- Success block -->
	<div class="login-block login-block--restore success-reset-password">
		<h2 class="login-title"><?php _e('Application sent!', 'natures-sunshine'); ?></h2>
		<div class="login-form form-lostpassword form">
			<div class="form__row">
				<label><?php _e('Check your email, we have sent a link to restore your account', 'natures-sunshine'); ?></label>
			</div>
			<div class="form__row">
				<a href="<?php echo home_url(); ?>" class="form__submit btn btn-green w-100"><?php _e('To home', 'natures-sunshine'); ?></a>
			</div>
		</div>
	</div>

</main>

<?php
get_footer('empty');