<?php 
$user_id = get_current_user_id();
$account = ut_get_page_data_by_template('template-account.php');
$news = get_user_meta( $user_id, 'profile_news', true );
$news_id = get_user_meta( $user_id, 'profile_news_id', true );
$promo = get_user_meta( $user_id, 'profile_promo', true );
$promo_id = get_user_meta( $user_id, 'profile_promo_id', true );
?>

<div class="profile-content"> 

    <h1 class="profile-content__title profile-content__title--public"><?php _e('Newsletter settings', 'natures-sunshine'); ?></h1>

    <form id="subscriptions_form" class="form public-form" action="" method="post">
	    <div class="profile-content__row profile-content__public">
		    <div class="user-block">
			    <div class="user-block__notice">
				    <p class="user-block__notice-title"><?php _e('Set up your newsletters', 'natures-sunshine'); ?></p>
				    <div class="user-block__notice-text color-mono-64">
						<?php 
							echo sprintf(
								__('<p>Here you can set up your newsletters. Technical mailings cannot be disabled, this is necessary to update the information and so that nothing is lost</p><p>The language of the mailing list depends on the language of the profile, to change the language go to <a href="%1s">%2s</a></p>', 'natures-sunshine'),
								get_permalink( $account->ID ),
								$account->post_title
							);
						?>
				    </div>
			    </div>
		    </div>
	    </div>
	    <div class="profile-content__row">
		    <div class="profile-content__letters">
				<div class="ut-loader"></div>

			    <div class="profile-content__letters-item">
				    <div class="switcher">
					    <input class="switcher__input" 
							   type="checkbox" 
							   name="profile_news" 
							   id="profile_news"
							   data-esputnik-id="<?php echo esc_attr($news_id); ?>"
							   <?php echo ( ($news) ? 'checked' : '' ); ?>>
					    <label class="switcher__label" for="profile_news">
							<?php _e('News', 'natures-sunshine'); ?>
						    <span class="switcher__slider"></span>
					    </label>
				    </div>
			    </div>

			    <div class="profile-content__letters-item">
				    <div class="switcher">
					    <input class="switcher__input" 
							   type="checkbox" 
							   name="profile_promo" 
							   id="profile_promo"
							   data-esputnik-id="<?php echo esc_attr($promo_id); ?>"
							   <?php echo ( ($promo) ? 'checked' : '' ); ?>>
					    <label class="switcher__label" for="profile_promo">
							<?php _e('Promotions', 'natures-sunshine'); ?>
						    <span class="switcher__slider"></span>
					    </label>
				    </div>
			    </div>

			    <div class="profile-content__letters-item">
				    <div class="switcher">
					    <input class="switcher__input" type="checkbox" name="profile_tech" id="profile_tech" disabled checked>
					    <label class="switcher__label" for="profile_tech">
							<?php _e('Technical newsletter', 'natures-sunshine'); ?>
						    <span class="switcher__slider"></span>
					    </label>
				    </div>
			    </div>

		    </div>
	    </div>
    </form>

</div>