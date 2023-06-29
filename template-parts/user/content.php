<?php 
$register_id = get_query_var('partner_id');

$users = get_users(
	[
		'meta_key' => 'register_id',
		'meta_value' => $register_id
	]
);

if ( ! $users ) {
	return false;
}

if ( ! isset($users[0]) ) {
	return false;
}

$user = $users[0];
$user_id = $user->ID;
$middle_name = get_user_meta( $user_id, 'patronymic', true );
$register_id = get_user_meta( $user_id, 'register_id', true );
$description = get_user_meta( $user_id, 'description', true );
$phone = get_user_meta( $user_id, 'billing_phone', true );
$avatar_id = get_user_meta( $user_id, '_avatar', true );
$avatar_url = ( $avatar_id ) ? wp_get_attachment_url( $avatar_id, 'full' ) : THEME_URI . '/img/user-avatar.png';

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

<section class="user">
	<div class="container">
		<div class="user-block">
			<?php the_title('<h1 class="user-block__title">', '</h1>'); ?>

			<?php if ( $public_profile ) : ?>

				<div class="user-block__content user-content">

					<?php if ( $profile_photo ) : ?>
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
					<?php endif; ?>

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

							<?php if ( $profile_name ) : ?>
								<h2 class="user-info__name">
									<?php 
									echo sprintf( 
										'%1s %2s %3s',
										$user->last_name,
										$user->first_name,
										$middle_name
									); 
									?>
								</h2>
							<?php endif; ?>

						</div>

						<?php if ( $profile_info ) : ?>
							<div class="user-info__row">
								<h3 class="user-info__title text bold"><?php _e('A little bit about yourself', 'natures-sunshine'); ?></h3>
								<p class="user-info__text"><?php echo nl2br( $description ); ?></p>
							</div>
						<?php endif; ?>

						<?php if ( $profile_phone ) : ?>
							<div class="user-info__row">
								<h3 class="user-info__title text bold"><?php _e('Telephone number', 'natures-sunshine'); ?></h3>

								<?php foreach ( (array)$additional_phones as $key => $additional_phone ) : ?>

									<?php if ( $additional_phone['public'] ) : ?>
										<a class="user-info__link" href="tel:<?php echo $additional_phone['number']; ?>">
											<?php echo $additional_phone['number']; ?>
										</a>
									<?php endif; ?>

								<?php endforeach; ?>

							</div>
						<?php endif; ?>

						<?php if ( $profile_email ) : ?>
							<div class="user-info__row">
								<h3 class="user-info__title text bold"><?php _e('Email', 'natures-sunshine'); ?></h3>

								<?php foreach ( (array)$additional_emails as $key => $additional_email ) : ?>

									<?php if ( $additional_email['public'] ) : ?>
										<a class="user-info__link" href="mailto:<?php echo $additional_email['email']; ?>">
											<?php echo $additional_email['email']; ?>
										</a>
									<?php endif; ?>

								<?php endforeach; ?>

							</div>
						<?php endif; ?>

						<?php if ( $profile_messengers ) : ?>
							<div class="user-info__row color">
								<h3 class="user-info__title text bold"><?php _e('Messengers', 'natures-sunshine'); ?></h3>
								<div class="profile-info__item messengers">
									
									<?php if ( $public_telegram ) : ?>
										<a class="profile-info__checkbox" target="_blank" href="https://t.me/<?php echo $telegram; ?>">
											<label class="profile-info__checkbox-label color messengers__item-label" for="profile_msg_telegram">
												<svg class="messengers__item-label__icon messengers__item-label__icon--tg" width="20" height="20">
													<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#telegram'; ?>"></use>
												</svg>
												<?php echo $telegram; ?>
											</label>
										</a>
									<?php endif; ?>
										
									<?php if ( $public_whatsapp ) : ?>
										<a class="profile-info__checkbox" target="_blank" href="https://wa.me/<?php echo $whatsapp; ?>">
											<label class="profile-info__checkbox-label color messengers__item-label" for="profile_msg_whatsapp">
												<svg class="messengers__item-label__icon messengers__item-label__icon--wa" width="20" height="20">
													<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#whatsapp'; ?>"></use>
												</svg>
												<?php echo $whatsapp; ?>
											</label>
										</a>
									<?php endif; ?>
										
									<?php if ( $public_skype ) : ?>
										<a class="profile-info__checkbox" target="_blank" href="Skype:<?php echo $skype; ?>?chat">
											<label class="profile-info__checkbox-label color messengers__item-label" for="profile_msg_skype">
												<svg class="messengers__item-label__icon messengers__item-label__icon--sk" width="20" height="20">
													<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#skype'; ?>"></use>
												</svg>
												<?php echo $skype; ?>
											</label>
										</a>
									<?php endif; ?>

								</div>
							</div>
						<?php endif; ?>

						<div class="user-info__row user-info__buttons">

							<a href="https://nsp25.com/signup?idb=333&sid=<?php echo esc_attr($register_id); ?>" class="user-info__button btn btn-green" target="_blank">
								<?php _e('Subscribe', 'natures-sunshine'); ?>
							</a>

							<a href="<?php echo esc_url(home_url('/?partnerID=' . $register_id)); ?>" class="user-info__button btn btn-secondary">
								<?php _e('Go shopping', 'natures-sunshine'); ?>
							</a>

						</div>
					</div>
				</div>

			<?php endif; ?>

		</div>
	</div>
</section> 