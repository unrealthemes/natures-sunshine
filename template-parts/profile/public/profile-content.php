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
// public
$public_profile = get_user_meta( $user_id, 'public_profile', true );
$profile_photo = get_user_meta( $user_id, 'profile_photo', true );
$profile_name = get_user_meta( $user_id, 'profile_name', true );
$profile_info = get_user_meta( $user_id, 'profile_info', true );
$profile_phone = get_user_meta( $user_id, 'profile_phone', true );
$profile_email = get_user_meta( $user_id, 'profile_email', true );
$profile_messengers = get_user_meta( $user_id, 'profile_messengers', true );

$public_telegram = get_user_meta( $user_id, 'public_telegram', true );
$public_whatsapp = get_user_meta( $user_id, 'public_whatsapp', true );
$public_skype = get_user_meta( $user_id, 'public_skype', true );
?>

<div class="profile-content">
	<?php the_title('<h1 class="profile-content__title profile-content__title--public">', '</h1>'); ?>
    <form id="public_info_form" action="" class="form public-form" method="post">

		<?php get_template_part( 'template-parts/alert' ); ?>

	    <div class="profile-content__row">
		    <div class="switcher">
			    <input class="switcher__input" type="checkbox" name="public_profile" id="public_profile" <?php echo (( $public_profile ) ? 'checked' : ''); ?>>
			    <label class="switcher__label" for="public_profile">
				    <?php _e('Public profile', 'natures-sunshine'); ?>
				    <span class="switcher__slider"></span>
			    </label>
		    </div>
	    </div>  

        <div class="profile-content__row profile-content__public <?php echo (( !$public_profile ) ? 'public-hide' : ''); ?>">

	        <div class="user-block">
		        <div class="user-block__notice">
			        <p class="user-block__notice-title"><?php _e('Privacy settings', 'natures-sunshine'); ?></p>
			        <span class="user-block__notice-text color-mono-64"><?php _e('Here are the public information settings. Customize what information you want to show in public access.', 'natures-sunshine'); ?></span>
		        </div>
		        <div class="user-block__content user-content">

			        <div class="user-content__image <?php echo (( !$profile_photo ) ? 'public-hide' : ''); ?>" 
						 data-letter="<?php echo mb_substr($user->first_name, 0, 1); ?>">

						<?php 
						if ( $avatar_id ) : 
							$avatar_url = ( $avatar_id ) ? wp_get_attachment_url( $avatar_id, 'full' ) : THEME_URI . '/img/user-avatar.png';
						?>

				        	<img src="<?php echo esc_attr( $avatar_url ); ?>" 
								 loading="eager" 
								 decoding="async" 
								 alt="<?php echo $user->first_name; ?>">

						<?php endif; ?>

			        </div>

			        <div class="user-content__info user-info">
				        <div class="user-info__row">

							<?php 
							if ( $register_id ) : 
								$public_url = home_url('/partner/' . $user_id);
							?>
								
								<div class="user-info__id">
									<span class="user-info__id-number" data-user-id="<?php echo esc_attr( $public_url ); ?>">
										ID: <?php echo $register_id; ?>
									</span>
									<a href="javascript:" 
									   class="user-info__id-copy js-copy-id" 
									   title="<?php _e('Copy link on public profile', 'natures-sunshine'); ?>"
									   data-text="<?php _e('Copied', 'natures-sunshine'); ?>">
										<svg width="24" height="24">
											<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#account-id-copy'; ?>"></use>
										</svg>
									</a>
								</div>

							<?php endif; ?>

					        <h2 class="user-info__name <?php echo (( !$profile_name ) ? 'public-hide' : ''); ?>">
								<?php 
								echo sprintf( 
									'%1s %2s %3s',
									$user->last_name,
									$user->first_name,
									$middle_name
								); 
								?>
							</h2>
				        </div>
				        <div class="user-info__row profile_info <?php echo (( !$profile_info ) ? 'public-hide' : ''); ?>">
					        <h3 class="user-info__title text bold"><?php _e('A little bit about yourself', 'natures-sunshine'); ?></h3>
					        <p class="user-info__text"><?php echo nl2br( $description ); ?></p>
				        </div>
						
						<!-- PHONES -->
						<div class="user-info__row phones <?php echo (( !$profile_phone ) ? 'public-hide' : ''); ?>">
							<h3 class="user-info__title text bold"><?php _e('Telephone number', 'natures-sunshine'); ?></h3>

							<?php foreach ( (array)$additional_phones as $key => $additional_phone ) : ?>

								<a class="user-info__link profile_tel_<?php echo $key; ?> <?php echo (( !$additional_phone['public'] ) ? 'public-hide' : ''); ?>" 
								   href="tel:<?php echo $additional_phone['number']; ?>">
									<?php echo $additional_phone['number']; ?>
								</a>
							
							<?php endforeach; ?>
								
						</div>
						
						<!-- EMAILS -->
						<div class="user-info__row emails <?php echo (( !$profile_email ) ? 'public-hide' : ''); ?>">
							<h3 class="user-info__title text bold"><?php _e('Email', 'natures-sunshine'); ?></h3>

							<?php foreach ( (array)$additional_emails as $key => $additional_email ) : ?>
								
								<a class="user-info__link profile_email_<?php echo $key; ?> <?php echo (( !$additional_email['public'] ) ? 'public-hide' : ''); ?>" 
								   href="mailto:<?php echo $additional_email['email']; ?>">
									<?php echo $additional_email['email']; ?>
								</a>

							<?php endforeach; ?>

						</div>

						<!-- MESSENDGERS -->
						<div class="user-info__row messengers <?php echo (( !$profile_messengers ) ? 'public-hide' : ''); ?>">
							<h3 class="user-info__title text bold"><?php _e('Messengers', 'natures-sunshine'); ?></h3>

							<div class="user-info__link color profile_msg_telegram <?php echo (( !$public_telegram ) ? 'public-hide' : ''); ?>">
								<svg class="messengers__item-label__icon messengers__item-label__icon--tg" width="20" height="20">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#telegram'; ?>"></use>
								</svg>
								<?php echo $telegram; ?>
							</div>
							
							<div class="user-info__link color profile_msg_whatsapp <?php echo (( !$public_whatsapp ) ? 'public-hide' : ''); ?>">
								<svg class="messengers__item-label__icon messengers__item-label__icon--wa" width="20" height="20">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#whatsapp'; ?>"></use>
								</svg>
								<?php echo $whatsapp; ?>
							</div>
							
							<div class="user-info__link color profile_msg_skype <?php echo (( !$public_skype ) ? 'public-hide' : ''); ?>">
								<svg class="messengers__item-label__icon messengers__item-label__icon--sk" width="20" height="20">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#skype'; ?>"></use>
								</svg>
								<?php echo $skype; ?>
							</div>

						</div>

			        </div>
		        </div>
		        <p class="user-block__text color-mono-32"><?php _e('Displaying information on a page', 'natures-sunshine'); ?></p>
	        </div>

        </div>

	    <div class="profile-content__info profile-info <?php echo (( !$public_profile ) ? 'public-hide' : ''); ?>">
			<div class="profile-info__row">
				<p class="profile-info__title"><?php _e('Personal information', 'natures-sunshine'); ?></p>
				<div class="profile-info__item">
					<div class="switcher">
						<input class="switcher__input" type="checkbox" name="profile_photo" id="profile_photo" <?php echo (( $profile_photo ) ? 'checked' : ''); ?>>
						<label class="switcher__label" for="profile_photo">
							<?php _e('Profile photo', 'natures-sunshine'); ?>
							<span class="switcher__slider"></span>
						</label>
					</div>
				</div>
				<div class="profile-info__item">
					<div class="switcher">
						<input class="switcher__input" type="checkbox" name="profile_name" id="profile_name" <?php echo (( $profile_name ) ? 'checked' : ''); ?>>
						<label class="switcher__label" for="profile_name">
							<?php _e('Username', 'natures-sunshine'); ?>
							<span class="switcher__slider"></span>
						</label>
					</div>
				</div>
				<div class="profile-info__item">
					<div class="switcher">
						<input class="switcher__input" type="checkbox" name="profile_info" id="profile_info" <?php echo (( $profile_info ) ? 'checked' : ''); ?>>
						<label class="switcher__label" for="profile_info">
							<?php _e('Personal information', 'natures-sunshine'); ?>
							<span class="switcher__slider"></span>
						</label>
					</div>
				</div>
			</div>
		    <div class="profile-divider"></div>
		    <div class="profile-info__row">

			    <p class="profile-info__title"><?php _e('Email and phones', 'natures-sunshine'); ?></p>

				<!-- PHONES -->
			    <div class="profile-info__part">
				    <div class="profile-info__item">
					    <div class="switcher">
						    <input class="switcher__input" type="checkbox" name="profile_phone" id="profile_phone" <?php echo (( $profile_phone ) ? 'checked' : ''); ?>>
						    <label class="switcher__label" for="profile_phone">
								<?php _e('Telephone number', 'natures-sunshine'); ?>
							    <span class="switcher__slider"></span>
						    </label>
					    </div>
				    </div>
				    <div class="profile-info__item phones <?php echo (( !$profile_phone ) ? 'public-hide' : ''); ?>">
					    <p class="text"><?php _e('What phone number to show', 'natures-sunshine'); ?></p>
				    </div>
				    <div class="profile-info__item phones <?php echo (( !$profile_phone ) ? 'public-hide' : ''); ?>">

						<?php if ( $additional_phones ) : ?>
							
							<?php foreach ( (array)$additional_phones as $key => $additional_phone ) : ?>
								
								<div class="profile-info__checkbox">
									<input class="profile-info__checkbox-input" 
										type="checkbox" 
										name="additional_phones[]" 
										id="profile_tel_<?php echo $key; ?>" 
										<?php echo (( $additional_phone['public'] ) ? 'checked' : ''); ?>
										value="<?php echo esc_attr( $additional_phone['number'] ); ?>">
									<label class="profile-info__checkbox-label" for="profile_tel_<?php echo $key; ?>">
										<?php echo $additional_phone['number']; ?>
									</label>
								</div>

							<?php endforeach; ?>

						<?php else : ?>

							<p class="text public-info-help-text">
								<?php 
									$account = ut_get_page_data_by_template('template-account.php');
									echo sprintf(
										__('Add at least one phone number per page <a href="%1s">%2s</a>', 'natures-sunshine'),
										get_permalink( $account->ID ),
										$account->post_title
									);
								?>
							</p>

						<?php endif; ?>

				    </div>
			    </div>

				<!-- EMAILS -->
			    <div class="profile-info__part">
					<div class="profile-info__item">
						<div class="switcher">
							<input class="switcher__input" type="checkbox" name="profile_email" id="profile_email" <?php echo (( $profile_email ) ? 'checked' : ''); ?>>
							<label class="switcher__label" for="profile_email">
								<?php _e('Email', 'natures-sunshine'); ?>
								<span class="switcher__slider"></span>
							</label>
						</div>
					</div>
					<div class="profile-info__item emails <?php echo (( !$profile_email ) ? 'public-hide' : ''); ?>">
					    <p class="text"><?php _e('What email number to show', 'natures-sunshine'); ?></p>
				    </div>
				    <div class="profile-info__item emails <?php echo (( !$profile_email ) ? 'public-hide' : ''); ?>">

						<?php if ( $additional_emails ) : ?>

							<?php foreach ( (array)$additional_emails as $key => $additional_email ) : ?>
								
								<div class="profile-info__checkbox">
									<input class="profile-info__checkbox-input" 
										type="checkbox" 
										name="additional_emails[]" 
										id="profile_email_<?php echo $key; ?>"
										<?php echo (( $additional_email['public'] ) ? 'checked' : ''); ?>
										value="<?php echo esc_attr( $additional_email['email'] ); ?>">
									<label class="profile-info__checkbox-label" for="profile_email_<?php echo $key; ?>">
										<?php echo $additional_email['email']; ?>
									</label>
								</div>

							<?php endforeach; ?>

						<?php else : ?>

							<p class="text public-info-help-text">
								<?php 
									$account = ut_get_page_data_by_template('template-account.php');
									echo sprintf(
										__('Add at least one email per page <a href="%1s">%2s</a>', 'natures-sunshine'),
										get_permalink( $account->ID ),
										$account->post_title
									);
								?>
							</p>

						<?php endif; ?>

				    </div>
			    </div>

				<!-- MESSENGERS -->
			    <div class="profile-info__part">
					<div class="profile-info__item">
						<div class="switcher">
							<input class="switcher__input" type="checkbox" name="profile_messengers" id="profile_messengers" <?php echo (( $profile_messengers ) ? 'checked' : ''); ?>>
							<label class="switcher__label" for="profile_messengers">
								<?php _e('Messengers', 'natures-sunshine'); ?>
								<span class="switcher__slider"></span>
							</label>
						</div>
					</div>
					<div class="profile-info__item messengers <?php echo (( !$profile_messengers ) ? 'public-hide' : ''); ?>">
					    <p class="text"><?php _e('What messendger number to show', 'natures-sunshine'); ?></p>
				    </div>
				    <div class="profile-info__item messengers <?php echo (( !$profile_messengers ) ? 'public-hide' : ''); ?>">
							
						<div class="profile-info__checkbox">
							<input class="profile-info__checkbox-input" 
								   type="checkbox" 
								   name="profile_msg_telegram" 
								   id="profile_msg_telegram" 
								   <?php echo (( $public_telegram ) ? 'checked' : ''); ?>>
							<label class="profile-info__checkbox-label color messengers__item-label" for="profile_msg_telegram">
								<svg class="messengers__item-label__icon messengers__item-label__icon--tg" width="20" height="20">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#telegram'; ?>"></use>
								</svg>
								<?php echo $telegram; ?>
							</label>
						</div>
						
						<div class="profile-info__checkbox">
							<input class="profile-info__checkbox-input" 
								   type="checkbox" 
								   name="profile_msg_whatsapp" 
								   id="profile_msg_whatsapp"
								   <?php echo (( $public_whatsapp ) ? 'checked' : ''); ?>>
							<label class="profile-info__checkbox-label color messengers__item-label" for="profile_msg_whatsapp">
								<svg class="messengers__item-label__icon messengers__item-label__icon--wa" width="20" height="20">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#whatsapp'; ?>"></use>
								</svg>
								<?php echo $whatsapp; ?>
							</label>
						</div>
						
						<div class="profile-info__checkbox">
							<input class="profile-info__checkbox-input" 
								   type="checkbox" 
								   name="profile_msg_skype" 
								   id="profile_msg_skype"
								   <?php echo (( $public_skype ) ? 'checked' : ''); ?>>
							<label class="profile-info__checkbox-label color messengers__item-label" for="profile_msg_skype">
								<svg class="messengers__item-label__icon messengers__item-label__icon--sk" width="20" height="20">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#skype'; ?>"></use>
								</svg>
								<?php echo $skype; ?>
							</label>
						</div>

				    </div>
			    </div>

		    </div>
	    </div>

    </form>
</div>