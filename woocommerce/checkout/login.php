<div class="form-checkout__block">
    <?php the_title('<h1 class="form-checkout__title">', '</h1>'); ?>
    <h2 class="form-checkout__subtitle"><?php _e('Contact details', 'natures-sunshine'); ?></h2>
    <div class="form-checkout__row form-checkout__tabs">
        <a href="#client" class="form-checkout__tabs-item active"><?php _e('I am a regular customer', 'natures-sunshine'); ?></a>
        <a href="#order" class="form-checkout__tabs-item"><?php _e('Order by ID', 'natures-sunshine'); ?></a>
    </div>
    <div class="form-checkout__row form-checkout__tabs-panels">
            
        <div class="form-checkout__tabs-panel active" id="client">
            <div id="checkout_auth_form">
                <div class="ut-loader"></div>
                <div class="form-checkout__row">
                    <label for="account_email"><?php _e('Email or ID', 'natures-sunshine'); ?></label>
                    <div class="form__input">
                        <input type="text" name="login_name" id="login_name" placeholder="example@domain.com" required>
                    </div>
                </div>
                <div class="form-checkout__row">
                    <label for="account_password"><?php _e('Password', 'natures-sunshine'); ?></label>
                    <div class="form__input">
                        <input type="password" class="dots" name="login_password" id="login_password" placeholder="••••••" required>
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
                <div class="form-checkout__row text-center">
                    <button class="form-checkout__button btn btn-green w-100" type="button">
                        <?php _e('Login', 'natures-sunshine'); ?>
                    </button>
                    <a href="<?php echo ut_get_permalik_by_template('template-lostpassword.php'); ?>" class="form-checkout__forget text">
                        <?php _e('Restore password', 'natures-sunshine'); ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="form-checkout__tabs-panel" id="order">
            <div id="checkout_auth_id_form">
                <div class="ut-loader"></div>
                <div class="form-checkout__row">
                    <label for="register_id"><?php _e('Order by ID', 'natures-sunshine'); ?></label>
                    <div class="form__input">
                        <input type="text" name="register_id" id="register_id" placeholder="12345" required>
                    </div>
                </div>
                <div id="auth_id_error" class="form__row">
					<span class="form__alert">
						<svg width="24" height="24" class="eye">
							<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#warning'; ?>"></use>
						</svg>
						<span></span>
					</span>
				</div>
                <div class="form-checkout__row">
                    <button class="form-checkout__button btn btn-green w-100" type="button">
                        <?php _e('Confirm', 'natures-sunshine'); ?>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>