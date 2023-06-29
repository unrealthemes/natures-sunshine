<?php
/*
 * Template Name: Login
 */
get_header('login'); 

$redirect_url = ( isset($_GET['redirect_url']) ) ? $_GET['redirect_url'] : ut_get_permalik_by_template('template-login.php');
$redirect_soc = $redirect_url;
$text_login = get_field('txt_login_form');
$txt_register = get_field('txt_register_form');
?>

<main class="main login-main">

    <div class="login-block">
		<div class="ut-loader"></div>
		<!-- TABS -->
        <div class="login-tabs">
            <a href="#login" class="login-tabs__link js-tab-link active"><?php _e('Login', 'natures-sunshine'); ?></a>
            <a href="#register" class="login-tabs__link js-tab-link"><?php _e('Registration', 'natures-sunshine'); ?></a>
        </div>

		<!-- LOGIN -->
        <div class="login-panel js-tab-panel active" id="login">
            <form class="login-form form-login form" id="auth_form" action="" method="post">
				<input type="hidden" name="redirect_url" value="<?php echo esc_attr( $redirect_url ); ?>">
                <div class="form__row">
                    <label for="login_name"><?php _e('Email or ID', 'natures-sunshine'); ?></label>
                    <div class="form__input">
                        <input type="text" name="login_name" id="login_name" placeholder="example@domain.com" required>
                    </div>
                </div>
                <div class="form__row">
                    <label for="login_password"><?php _e('Password', 'natures-sunshine'); ?></label>
                    <div class="form__input">
                        <input type="password" class="dots" name="login_password" id="login_password" placeholder="••••••" required>
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
				<div id="auth_error" class="form__row">
					<span class="form__alert">
						<svg width="24" height="24" class="eye">
							<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#warning'; ?>"></use>
						</svg>
						<span></span>
					</span>
				</div>
	            <div class="form__row">
		            <button type="submit" class="form__submit btn btn-green w-100"><?php _e('Login', 'natures-sunshine'); ?></button>
	            </div>
	            <div class="form__row text-center"> 
		            <a href="<?php echo ut_get_permalik_by_template('template-lostpassword.php'); ?>" class="form__link">
						<?php _e('Restore password', 'natures-sunshine'); ?>
					</a>

					<?php if ( $text_login ) : ?>
						<div class="form__accept">
							<?php echo $text_login; ?>
						</div>
					<?php endif; ?>

	            </div>
	            <div class="form__row">
					<div class="form__divider text-center"><span><?php _e('OR', 'natures-sunshine'); ?></span></div>
		            <ul class="form__login" role="list">
			            <li class="form__login-item">
				            <a class="form__login-app" 
							   href="<?php echo get_site_url(); ?>/my-office/?loginSocial=google" 
							   data-plugin="nsl" 
							   data-action="connect" 
							   data-redirect="<?php echo $redirect_soc; ?>" 
							   data-provider="google" 
							   data-popupwidth="600" 
							   data-popupheight="600"
							   title="Google">
					            <img src="<?= DIST_URI . '/images/icons/google.svg'; ?>" loading="lazy" decoding="async" alt="Google">
				            </a>
			            </li>
			            <li class="form__login-item">
				            <a class="form__login-app" 
							   href="<?php echo get_site_url(); ?>/my-office/?loginSocial=facebook" 
                        	   data-plugin="nsl" 
                        	   data-action="connect" 
                        	   data-redirect="<?php echo $redirect_soc; ?>" 
                        	   data-provider="facebook" 
                        	   data-popupwidth="475" 
                        	   data-popupheight="175"
							   title="Facebook">
					            <img src="<?= DIST_URI . '/images/icons/facebook.svg'; ?>" loading="lazy" decoding="async" alt="Facebook">
				            </a>
			            </li>
		            </ul>
	            </div>
            </form>
        </div>

		<!-- REGISTER -->
        <div class="login-panel js-tab-panel" id="register">
            <form class="login-form form-register form" id="user_register_form" action="" method="post">
				<input type="hidden" id="step" name="step" value="1">

				<!-- STEP 1 -->
				<div class="form__step step-1 active">
					<div class="form__row">
						<span class="form__counter"><?php _e('Step 1 of 3', 'natures-sunshine'); ?></span>
					</div>
					<div class="form__row">
						<label for="register_name"><?php _e('First name', 'natures-sunshine'); ?></label>
						<div class="form__input">
							<input type="text" name="register_name" id="register_name" placeholder="<?php _e('Konstantin', 'natures-sunshine'); ?>" required>
							<div class="form__input-require">
								<svg width="24" height="24" class="eye">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#star-req'; ?>"></use>
								</svg>
							</div>
						</div>
					</div>
					<div class="form__row">
						<label for="register_surname"><?php _e('Last name', 'natures-sunshine'); ?></label>
						<div class="form__input">
							<input type="text" name="register_surname" id="register_surname" placeholder="<?php _e('Constantinople', 'natures-sunshine'); ?>" required>
							<div class="form__input-require">
								<svg width="24" height="24" class="eye">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#star-req'; ?>"></use>
								</svg>
							</div>
						</div>
					</div>
					<div class="form__row">
						<label for="register_phone"><?php _e('Phone', 'natures-sunshine'); ?></label>
						<div class="form__input">
							<input type="tel" name="register_phone" id="register_phone" class="mask-js" required>
							<div class="form__input-require">
								<svg width="24" height="24" class="eye">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#star-req'; ?>"></use>
								</svg>
							</div>
						</div>
					</div>
					<div class="form__row">
						<button type="button" class="form__button btn btn-secondary btn-green w-100 js-step-next"><?php _e('Proceed', 'natures-sunshine'); ?></button>
					</div>
					<div class="form__row text-center">
						<a href="javascript:" class="form__link js-tab-back"><?php _e("I'm already registered", 'natures-sunshine'); ?></a>
					</div>
					<div class="form__row text-center">
						
						<?php if ( $txt_register ) : ?>
							<div class="form__accept">
								<?php echo $txt_register; ?>
							</div>
						<?php endif; ?>

					</div>
				</div>

				<!-- STEP 2 -->
				<div class="form__step step-2">
					<div class="form__row">
						<span class="form__counter"><?php _e('Step 2 of 3', 'natures-sunshine'); ?></span>
					</div>
					<div class="form__row">
						<label for="register_id"><?php _e('Your ID', 'natures-sunshine'); ?></label>
						<div class="form__input">
							<input type="text" name="register_id" id="register_id" placeholder="12354678" required>
							<div class="form__input-require">
								<svg width="24" height="24" class="eye">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#star-req'; ?>"></use>
								</svg>
							</div>
						</div>
					</div>
					<div class="form__row">
						<label for="register_sponsor_id"><?php _e('Sponsor ID', 'natures-sunshine'); ?></label>
						<div class="form__input">
							<input type="text" name="register_sponsor_id" id="register_sponsor_id" placeholder="12354678" required>
							<div class="form__input-require">
								<svg width="24" height="24" class="eye">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#star-req'; ?>"></use>
								</svg>
							</div>
						</div>
					</div>
					
					<?php get_template_part('template-parts/alert', null, ['class' => 'step_2']); ?>

					<div class="form__row">
						<button type="button" class="form__button btn btn-secondary btn-green w-100 js-step-2"><?php _e('Proceed', 'natures-sunshine'); ?></button>
					</div>
					<div class="form__row">
						<a href="javascript:" class="form__link js-step-prev"><?php _e('One step back', 'natures-sunshine'); ?></a>
					</div>
				</div>

				<!-- STEP 3 -->
				<div class="form__step step-3">
					<div class="form__row">
						<span class="form__counter"><?php _e('Step 3 of 3', 'natures-sunshine'); ?></span>
					</div>
					<div class="form__row">
						<label for="register_email"><?php _e('Fill in the mail', 'natures-sunshine'); ?></label>
						<div class="form__input">
							<input type="email" name="register_email" id="register_email" placeholder="example@domain.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
							<div class="form__input-require">
								<svg width="24" height="24" class="eye">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#star-req'; ?>"></use>
								</svg>
							</div>
						</div>
					</div>
					<div class="form__row">
						<label for="register_password"><?php _e('Create a password', 'natures-sunshine'); ?></label>
						<div class="form__input">
							<input type="password" class="dots" name="register_password" id="register_password" placeholder="••••••" required>
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
						<input type="hidden" name="redirect_url" value="<?php echo esc_attr( $redirect_url ); ?>">
						<button type="button" class="form__button btn btn-green w-100 js-step-3"><?php _e('Register', 'natures-sunshine'); ?></button>
					</div>

					<?php get_template_part('template-parts/alert', null, ['class' => 'step_3']); ?>

					<div class="form__row">
						<a href="javascript:" class="form__link js-step-prev"><?php _e('One step back', 'natures-sunshine'); ?></a>
					</div>
				</div>

            </form>
        </div>
    </div>

</main>

<?php
get_footer('empty');