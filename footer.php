<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the .content-area, .site-content and .site divs and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

$i = 1;
$languages = apply_filters( 'wpml_active_languages', NULL, array( 'skip_missing' => 0 ) );
$copyright = get_field('copyright_footer', 'options');
$logo_id = get_field('logo_footer', 'options');
$text_logo = get_field('txt_logo_footer', 'options');
$left_menu_id = ut_get_menu_id_by_location('footer-menu-left');
$center_menu_id = ut_get_menu_id_by_location('footer-menu-center');
$right_menu_id = ut_get_menu_id_by_location('footer-menu-right');
?>

	<footer class="footer">
		<div class="container">
			<div class="footer__main">
				<div class="footer__info">
					
					<?php if ( $logo_id ) : ?>
						<div class="footer__logo logo">
							<a href="<?= home_url(); ?>" class="logo__link">
								<img src="<?php echo wp_get_attachment_url( $logo_id, 'full' ); ?>" 
									 width="140" 
									 height="40" 
									 loading="lazy" 
									 decoding="async" 
									 alt="<?php echo get_bloginfo( 'name' ); ?>">
							</a>
						</div>
					<?php endif; ?>

					<?php if ( $text_logo ) : ?>
						<div class="footer__description">
							<?php echo $text_logo; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $languages ) ) : ?>

						<div class="footer__langs langs">

							<?php foreach ( $languages as $language ) : ?>

								<?php if ( $i > 1 ) : ?>
									<span class="langs__divider"></span>
								<?php endif; ?>

								<?php if ( $language['active'] ) : ?>

									<span class="langs__item langs__item--current">
										<?php echo $language['translated_name']; ?>
									</span>

								<?php 
								else : 
									$lang_url = ut_lang_url($language);
								?>

									<a href="<?php echo esc_url($lang_url); ?>" class="langs__item" data-code="<?php echo $language['code']; ?>">
										<?php echo $language['translated_name']; ?>
									</a>

								<?php endif; ?>

							<?php $i++; endforeach; ?>

						</div>

					<?php endif; ?>

				</div>
				<div class="footer__nav">
					<div class="footer__block">
						<span class="footer__block-title"> 
							<?php echo get_field('title_menu', 'term_' . $left_menu_id); ?>
							<svg width="24" height="24">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
							</svg>
						</span>
						<div class="footer__block-panel">

							<?php
							if ( has_nav_menu('footer-menu-left') ) {
								wp_nav_menu( [
									'theme_location' => 'footer-menu-left',
									'container'      => false,
									'menu_class'     => '',
									'items_wrap'     => '<ul id="%1$s" class="footer__menu %2$s">%3$s</ul>',
								] );
							}
							?>

						</div>
					</div>
					<div class="footer__block">
						<span class="footer__block-title">
						<?php echo get_field('title_menu', 'term_' . $center_menu_id); ?>
							<svg width="24" height="24">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
							</svg>
						</span>
						<div class="footer__block-panel">
							
							<?php
							if ( has_nav_menu('footer-menu-center') ) {
								wp_nav_menu( [
									'theme_location' => 'footer-menu-center',
									'container'      => false,
									'menu_class'     => '',
									'items_wrap'     => '<ul id="%1$s" class="footer__menu %2$s">%3$s</ul>',
								] );
							}
							?>

						</div>
					</div>
					<div class="footer__block">
						<span class="footer__block-title">
						<?php echo get_field('title_menu', 'term_' . $right_menu_id); ?>
							<svg width="24" height="24">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
							</svg>
						</span>
						<div class="footer__block-panel">
							
							<?php
							if ( has_nav_menu('footer-menu-right') ) {
								wp_nav_menu( [
									'theme_location' => 'footer-menu-right',
									'container'      => false,
									'menu_class'     => '',
									'items_wrap'     => '<ul id="%1$s" class="footer__menu %2$s">%3$s</ul>',
								] );
							}
							?>

						</div>
					</div>
				</div>

				<?php if ( have_rows('social_network_footer', 'options') ): ?>

					<ul class="footer__socials socials" role="list">

						<?php while ( have_rows('social_network_footer', 'options') ): the_row(); ?>

							<li class="socials__item">
								<a href="<?php the_sub_field('link_social_network_footer'); ?>" 
								   class="socials__item-link" 
								   target="_blank"
								   title="<?php the_sub_field('txt_social_network_footer'); ?>">
									<?php the_sub_field('icon_social_network_footer'); ?>
									<?php the_sub_field('txt_social_network_footer'); ?>
								</a>
							</li>
						
						<?php endwhile; ?>

					</ul>

				<?php endif; ?>

				<div class="footer__copyright copyright">
					&copy; <?= get_the_date('Y') . ' ' . $copyright; ?> 
				</div>
			</div>
		</div>
		<div class="footer__bottom">
			<div class="container">
				<div class="footer__bottom-inner">
					<ul class="footer__services" role="list">
						<li class="footer__services-item">
							<img src="<?= DIST_URI . '/images/icons/visa.svg'; ?>" class="footer__services-icon" width="31" height="10" loading="lazy" decoding="async" alt="">
						</li>
						<li class="footer__services-item">
							<img src="<?= DIST_URI . '/images/icons/mastercard.svg'; ?>" class="footer__services-icon" width="20" height="12" loading="lazy" decoding="async" alt="">
						</li>
						<li class="footer__services-item">
							<img src="<?= DIST_URI . '/images/icons/paypal.svg'; ?>" class="footer__services-icon" width="14" height="17" loading="lazy" decoding="async" alt="">
						</li>
					</ul>
				</div>
			</div>
		</div>
	</footer>

	<?php 
	if ( is_product() ) :
		get_template_part( 'template-parts/modals/comments' );
	endif; 
	
	if ( is_cart() ) :
		get_template_part( 'template-parts/modals/delete-join-cart' );
		get_template_part( 'template-parts/modals/exit-join-cart' );
		get_template_part( 'template-parts/modals/id-join-cart' );
	endif; 

	get_template_part( 'template-parts/modals/carts' );
	
	if ( is_checkout() ) :
		get_template_part( 'template-parts/modals/cities' );
	endif; 

	if ( is_checkout() && ! is_user_logged_in() && ( WC()->session->get('register_id_auth') || isset($_COOKIE['register_id_auth']) ) ) :
		get_template_part( 'template-parts/modals/edit-id' );
	endif; 


	if ( is_page_template('template-favorites.php') ) :
		get_template_part( 'template-parts/modals/replace-wishlist' );
		get_template_part( 'template-parts/modals/add-wishlist' );
		get_template_part( 'template-parts/modals/edit-title-wishlist' );
	endif;
	?>

	<?php wp_footer(); ?>

</body>

</html>