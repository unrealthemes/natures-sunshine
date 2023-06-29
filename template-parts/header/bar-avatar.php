<?php 
if ( is_user_logged_in() ) : 
    $user_id = get_current_user_id();
    $avatar_id = get_user_meta( $user_id, '_avatar', true );
?>

    <li class="header__controls-item avatar">
        <a href="<?php echo ut_get_permalik_by_template('template-account.php'); ?>" class="header__controls-link">
            <div class="profile-content__account-photo-inner">
                <?php 
                if ( $avatar_id ) : 
                    $avatar_url = ( $avatar_id ) ? wp_get_attachment_url( $avatar_id, 'full' ) : THEME_URI . '/img/user-avatar.png';
                    ?>
                    <img class="profile-content__account-photo-image image-absolute" 
                        src="<?php echo esc_attr( $avatar_url ); ?>" alt="">
                <?php 
                else : 
                    $user = get_userdata( $user_id );
                ?>
                    <div class="user-content__image" data-letter="<?php echo mb_substr($user->first_name, 0, 1); ?>"></div>
                <?php endif; ?>

            </div>

	        <div class="avatar-mobile">
		        <div class="header__controls-icon">
			        <svg width="24" height="24">
				        <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#person'; ?>"></use>
			        </svg>
		        </div>
		        <span class="header__controls-text">
                <?php _e('Account', 'natures-sunshine'); ?>
            </span>
	        </div>
        </a>
    </li> 

<?php endif; ?>