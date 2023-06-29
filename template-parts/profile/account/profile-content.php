<?php 
$user_id = get_current_user_id();
$user = get_userdata( $user_id );
$middle_name = get_user_meta( $user_id, 'patronymic', true );
$register_id = get_user_meta( $user_id, 'register_id', true );
$description = get_user_meta( $user_id, 'description', true );
$phone = get_user_meta( $user_id, 'billing_phone', true );
$avatar_id = get_user_meta( $user_id, '_avatar', true );

$additional_phones = get_user_meta( $user_id, 'additional_phones', true );
$additional_emails = get_user_meta( $user_id, 'additional_emails', true );

$telegram = get_user_meta( $user_id, 'telegram', true );
$whatsapp = get_user_meta( $user_id, 'whatsapp', true );
$skype = get_user_meta( $user_id, 'skype', true );

$interface_language = get_user_meta( $user_id, 'interface_language', true );
$tzstring = get_user_meta( $user_id, 'timezone_string', true );
$languages = apply_filters( 'wpml_active_languages', NULL, ['skip_missing' => 0] );
?>

<div class="profile-content">
    <?php the_title('<h1 class="profile-content__title">', '</h1>'); ?>

    <form id="accaunt_form" action="" class="form profile-form" method="post" enctype="multipart/form-data">

        <?php get_template_part( 'template-parts/alert' ); ?>

        <div class="profile-content__row profile-content__account">

            <div class="profile-content__account-photo">
                <div class="ut-loader"></div>
                <div class="profile-content__account-photo-inner">

                    <?php 
                        get_template_part(
                            'template-parts/profile/account/avatar',
                            null,
                            [
                                'user' => $user,
                                'avatar_id' => $avatar_id,
                            ]
                        ); 
                    ?>

                </div>
                <div class="error-avatar"></div>
                <a href="#" class="profile-content__account-photo-delete"><?php _e('Delete', 'natures-sunshine'); ?></a>
            </div>

            <div class="profile-content__account-info">

                <?php 
                if ( $register_id ) : 
                    $public_url = home_url('/partner/' . $register_id);
                ?>

                    <div class="profile-content__account-info-id">
                        <div class="profile-content__account-info-id_title">ID: </div>
                        <div class="profile-content__account-info-id_value" data-user-id="<?php echo esc_attr( $public_url ); ?>">
                            <?php echo $register_id; ?>
                        </div>
                        <a href="javascript:" 
                           class="profile-content__account-info-id_copy js-copy-id" 
                           title="<?php _e('Copy link on public profile', 'natures-sunshine'); ?>"
                           data-text="<?php _e('Copied', 'natures-sunshine'); ?>">
                            <svg width="24" height="24" ><use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#account-id-copy'; ?>"></use></svg>
                        </a>
                    </div>

                <?php endif; ?>

                <div class="profile-content__account-info-name">
                    <?php 
                    echo sprintf( 
                        '%1s %2s %3s',
                        $user->last_name,
                        $user->first_name,
                        $middle_name
                    ); 
                    ?>
                </div>
                <div class="profile-content__account-info-about">
                    <div class="profile-content__account-info-about_title"><?php _e('A little bit about yourself', 'natures-sunshine'); ?></div>
                    <div class="profile-content__account-info-about_textarea">
                        <textarea value="<?php echo esc_attr( $description ); ?>" name="description" id="description" cols="30" rows="3"><?php echo nl2br( $description ); ?></textarea>
                        
                    </div>
                </div>
                <button type="button" data-fancybox data-src="#change_password" class="profile-content__account-password btn btn-secondary">
                    <?php _e('Change password', 'natures-sunshine'); ?>
                </button>
            </div>

        </div>

        <div class="profile-divider"></div>
        
        <?php if ( $languages ) : ?>

            <div class="profile-content__row profile-content__language">
                <div class="profile-content__language-title profile-title"><?php _e('Interface language', 'natures-sunshine'); ?></div>
                <div class="profile-content__language-select select">
                    <select name="interface_language">

                        <?php 
                        foreach ( $languages as $language ) : 
                            $selected = ( $language['code'] == $interface_language ) ? 'selected' : '';
                        ?>

                            <option value="<?php echo esc_attr( $language['code'] ); ?>" <?php echo $selected; ?>>
                                <?php echo $language['translated_name']; ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                    
                </div>
            </div>

        <?php endif; ?>

        <div class="profile-divider"></div>

        <div class="profile-content__row profile-content__person">
            <div class="profile-content__person-title profile-title"><?php _e('Personal information', 'natures-sunshine'); ?></div>

            <div class="profile-content__person-fields">
                
                <div class="profile-content__person-fields-item">
                    <label for="surname" class="profile-content__person-fields_label"><?php _e('Surname', 'natures-sunshine'); ?></label>
                    <div class="profile-content__person-fields_input">
                        <input name="surname" id="surname" type="text" required value="<?php echo esc_attr( $user->last_name ); ?>">
                        
                    </div>
                </div>

                <div class="profile-content__person-fields-item">
                    <label for="name" class="profile-content__person-fields_label"><?php _e('Name', 'natures-sunshine'); ?></label>
                    <div class="profile-content__person-fields_input">
                        <input name="name" id="name" type="text" required value="<?php echo esc_attr( $user->first_name ); ?>">
                        
                    </div>
                </div>

                <div class="profile-content__person-fields-item">
                    <label for="patronymic" class="profile-content__person-fields_label"><?php _e('Middle name', 'natures-sunshine'); ?></label>
                    <div class="profile-content__person-fields_input">
                        <input name="patronymic" id="patronymic" type="text" required value="<?php echo esc_attr( $middle_name ); ?>">
                        
                    </div>
                </div>

            </div>

            <!-- <button class="profile-content__person-update btn btn-secondary" disabled><?php _e('Save', 'natures-sunshine'); ?></button> -->
        </div>

        <div class="profile-divider"></div>

        <div class="profile-content__row profile-content__mails">
            <div class="profile-content__mails-title profile-title"><?php _e('Email and phones', 'natures-sunshine'); ?></div>
            <div class="profile-content__mails-grid">
                <div class="profile-content__mails-grid__item">
                    <div class="profile-content__mails-grid__item-title"><?php _e('Main number', 'natures-sunshine'); ?></div>
                    <div class="profile-content__mails-grid__item-input">
                        <input type="text" class="mask-js" disabled value="<?php echo esc_attr( $phone ); ?>" required>
                    </div>
                </div>
                <div class="profile-content__mails-grid__item">
                    <div class="profile-content__mails-grid__item-title"><?php _e('Email', 'natures-sunshine'); ?></div>
                    <div class="profile-content__mails-grid__item-input">
                        <input type="email" disabled value="<?php echo esc_attr( $user->user_email ); ?>" required>
                    </div>
                </div>
            </div>
            <button type="button" 
                    data-phone="<?php echo esc_attr( $phone ); ?>"
                    data-email="<?php echo esc_attr( $user->user_email ); ?>"
                    data-fancybox data-src="#change_main_phone_email" 
                    class="btn btn-secondary change-main-phone-email-js">
                <?php _e('Change', 'natures-sunshine'); ?>
            </button>
        </div>

        <div class="profile-divider"></div>

        <div class="profile-content__row profile-content__extrafields">
            <div class="profile-content__extrafields-title profile-title"><?php _e('Additional Phone', 'natures-sunshine'); ?></div>
            <div class="profile-content__extrafields-list additional-phones-js">

                <?php foreach ( (array)$additional_phones as $key => $additional_phone ) : ?>

                    <div class="profile-content__extrafields-list__item">
                        <div class="profile-content__extrafields-list__item-input">
                            <input type="text" 
                                   class="mask-js"
                                   name="additional_phones[]" 
                                   value="<?php echo esc_attr( $additional_phone['number'] ); ?>" 
                                   placeholder="+38(___) ___-____">
                            
                        </div>
                        <div class="profile-content__extrafields-list__item-delete remove-additional-phone-js">
                            <svg width="24" height="24"><use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#account-input-delete'; ?>"></use></svg>
                        </div>
                    </div>

                <?php endforeach; ?>

            </div>
            <button type="button" class="profile-content__extrafields-add btn btn-secondary add-additional-phone-js"><?php _e('Add', 'natures-sunshine'); ?></button>
        </div>

        <div class="profile-divider"></div>

        <div class="profile-content__row profile-content__extrafields">
            <div class="profile-content__extrafields-title profile-title"><?php _e('Additional email', 'natures-sunshine'); ?></div>
            <div class="profile-content__extrafields-list additional-emails-js">

                <?php foreach ( (array)$additional_emails as $key => $additional_email ) : ?>

                    <div class="profile-content__extrafields-list__item">
                        <div class="profile-content__extrafields-list__item-input">
                            <input type="text" 
                                   class="emask-js"
                                   name="additional_emails[]" 
                                   value="<?php echo esc_attr( $additional_email['email'] ); ?>">
                            
                        </div>
                        <div class="profile-content__extrafields-list__item-delete remove-additional-email-js">
                            <svg width="24" height="24"><use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#account-input-delete'; ?>"></use></svg>
                        </div>
                    </div>
                
                <?php endforeach; ?>

            </div>
            <button type="button" class="profile-content__extrafields-add btn btn-secondary add-additional-email-js"><?php _e('Add', 'natures-sunshine'); ?></button>
        </div>

        <div class="profile-divider"></div>

        <div class="profile-content__row profile-content__time">
            <div class="profile-content__time-title profile-title"><?php _e('Time zone and time', 'natures-sunshine'); ?></div>
            <div class="profile-content__language-select select">
                <select id="timezone_string" name="timezone_string" aria-describedby="timezone-description">
                    <?php echo wp_timezone_choice( $tzstring, get_user_locale() ); ?>
                </select>
                
            </div>
        </div>

	    <div class="profile-content__row profile-content__messengers">
		    <div class="profile-content__messengers-title profile-title"><?php _e('Messengers', 'natures-sunshine'); ?></div>
		    <ul class="profile-content__messengers-list messengers">
				<li class="messengers-list__item messengers__item">
					<label class="messengers__item-label" for="telegram">
						<svg class="messengers__item-label__icon messengers__item-label__icon--tg" width="20" height="20">
							<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#telegram'; ?>"></use>
						</svg>
						Telegram
					</label>
					<input class="messengers__item-input" type="text" name="telegram" id="telegram" placeholder="@username" value="<?php echo esc_attr( $telegram ); ?>">
                    
				</li>
			    <li class="messengers-list__item messengers__item">
				    <label class="messengers__item-label" for="whatsapp">
					    <svg class="messengers__item-label__icon messengers__item-label__icon--wa" width="20" height="20">
						    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#whatsapp'; ?>"></use>
					    </svg>
					    Whatsapp
				    </label>
				    <input class="messengers__item-input" type="text" name="whatsapp" id="whatsapp" placeholder="+7 000 000-00-00" value="<?php echo esc_attr( $whatsapp ); ?>">
                    
			    </li>
			    <li class="messengers-list__item messengers__item">
				    <label class="messengers__item-label" for="skype">
					    <svg class="messengers__item-label__icon messengers__item-label__icon--sk" width="20" height="20">
						    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#skype'; ?>"></use>
					    </svg>
					    Skype
				    </label>
				    <input class="messengers__item-input" type="text" name="skype" id="skype" value="<?php echo esc_attr( $skype ); ?>" placeholder="<?php _e('Account login', 'natures-sunshine'); ?>">
                    
                </li>
		    </ul>
            <!-- <button class="profile-content__person-update btn btn-secondary"><?php _e('Save', 'natures-sunshine'); ?></button> -->
	    </div>

	    <div class="profile-divider"></div>

	    <div class="profile-content__row profile-content__connect">
		    <div class="profile-title profile-title--24"><?php _e('Link to account', 'natures-sunshine'); ?></div>
		    <div class="profile-content__connect-items connect-items">

                <?php echo do_shortcode('[nextend_social_login style="" login="1" link="1" unlink="1" align="left"]'); ?>

			    <!-- <a href="#" class="profile-content__connect-item connect-items__link connect-items__link--connected">
				    <div class="connect-items__link-icon link-icon">
					    <img class="link-icon__img link-icon__img--default" src="<?= DIST_URI . '/images/icons/gg.svg'; ?>" alt="">
					    <img class="link-icon__img link-icon__img--disabled" src="<?= DIST_URI . '/images/icons/gg-disabled.svg'; ?>" alt="">
				    </div>
				    <div class="connect-items__link-content link-content">
					    <span class="link-content__title text">Google</span>
					    <span class="link-content__action"><?php _e('Delete connection', 'natures-sunshine'); ?></span>
				    </div>
			    </a>

			    <a href="#" class="profile-content__connect-item connect-items__link">
				    <div class="connect-items__link-icon link-icon">
					    <img class="link-icon__img link-icon__img--default" src="<?= DIST_URI . '/images/icons/facebook.svg'; ?>" alt="">
					    <img class="link-icon__img link-icon__img--disabled" src="<?= DIST_URI . '/images/icons/facebook-disabled.svg'; ?>" alt="">
				    </div>
				    <div class="connect-items__link-content link-content">
					    <span class="link-content__title text">Facebook</span>
					    <span class="link-content__action"><?php _e('Add connection', 'natures-sunshine'); ?></span>
				    </div>
			    </a> -->

		    </div>
	    </div>

	    <div class="profile-divider"></div>

	    <div class="profile-content__row profile-content__delete">
		    <div class="profile-content__delete-text">
			    <p>
                    <?php 
                    echo sprintf(
                        __('Here you can delete your account on the services <a href="%1s" target="_blank">%2s</a>', 'natures-sunshine'),
                        'https://nsp.com.ua/',
                        'nsp.com.ua',
                    );
                    ?>
                </p>
			    <p>
                    <?php _e('This action will result in the termination of access to these services, but not the termination of the partnership agreement', 'natures-sunshine'); ?>    
                </p>
		    </div>
		    <button type="button" data-fancybox data-src="#delete-account" class="profile-content__delete-button btn btn-transparent">
                <?php _e('Delete account', 'natures-sunshine'); ?>
            </button>
	    </div>

    </form>
</div>