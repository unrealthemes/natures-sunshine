<?php 
$logo_id = get_field('logo_header', 'options');
$phone = get_field('phone_header', 'options');
$working_mode = get_field('working_mode_header', 'options');
$products_list = ut_help()->compare->products_list();
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<link rel="apple-touch-icon" sizes="180x180" href="<?= DIST_URI . '/images/favicon/apple-touch-icon.png'; ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= DIST_URI . '/images/favicon/favicon-32x32.png'; ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= DIST_URI . '/images/favicon/favicon-16x16.png'; ?>">
	<link rel="manifest" href="<?= DIST_URI . '/images/favicon/site.webmanifest'; ?>">
	<link rel="mask-icon" href="<?= DIST_URI . '/images/favicon/safari-pinned-tab.svg'; ?>" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">

	<link rel="preload" href="<?= DIST_URI . '/fonts/Circe/Circe-Regular.woff2' ?>" as="font" type="font/woff2" crossorigin="anonymous">
	<link rel="preload" href="<?= DIST_URI . '/fonts/Circe/Circe-Bold.woff2' ?>" as="font" type="font/woff2" crossorigin="anonymous">

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

	<?php wp_body_open(); ?>

	<?php ut_help()->user->verrify_user_email(); ?>

	<?php get_template_part('template-parts/add-to-compare-alert'); ?>

	<?php get_template_part('template-parts/header/promo'); ?>

	<div class="header__top">
		<div class="container">
			<div class="header__top-inner">

				<?php get_template_part('template-parts/header/language-switcher'); ?>
				
				<?php
				if ( has_nav_menu('header-menu-top') ) {
					wp_nav_menu( [
						'theme_location' => 'header-menu-top',
						'container'      => false,
						'menu_class'     => '',
						'items_wrap'     => '<ul id="%1$s" class="header__info %2$s">%3$s</ul>',
					] );
				}
				?>

				<?php if ( $phone ) : ?>
					<div class="header__phone">
						<a href="tel:<?php echo $phone; ?>" class="header__phone-link">
							<?php echo $phone; ?>
						</a>
						<?php if ( $working_mode ) : ?>
							<div class="header__phone-info">
								<svg width="16" height="16">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
								</svg>
							</div>
							<div class="header__phone-notice">
								<span><?php echo $phone; ?></span>
								<p><?php echo $working_mode; ?></p>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>
	<header class="header">
	    <div class="header__main">
			<div class="container">
				<div class="header__main-inner">
					
					<?php if ( $logo_id ) : ?>
						<div class="header__logo logo">
							<a href="<?php echo home_url(); ?>" class="logo__link">
								<img src="<?php echo wp_get_attachment_url( $logo_id, 'full' ); ?>" 
									width="140" 
									height="40" 
									loading="lazy" 
									decoding="async" 
									alt="<?php echo get_bloginfo( 'name' ); ?>">
							</a>
						</div>
					<?php endif; ?>

					<?php get_template_part('template-parts/header/search'); ?>

					<div class="header__bar">
						<ul class="header__controls" role="list">

							<?php get_template_part('template-parts/header/bar', 'main'); ?>

							<?php get_template_part('template-parts/header/bar', 'compare', ['products_list' => $products_list]); ?>

							<?php get_template_part('template-parts/header/bar', 'whishlist'); ?>

							<?php get_template_part('template-parts/header/bar', 'cart'); ?>

							<?php get_template_part('template-parts/header/bar', 'login'); ?>

							<?php get_template_part('template-parts/header/bar', 'avatar'); ?>
						
						</ul>

					</div>
				</div>
			</div>
		    <nav class="header__nav">
			    <div class="container">

					<?php
					if ( has_nav_menu('header-menu-main') ) {
						wp_nav_menu( [
							'theme_location' => 'header-menu-main',
							'container'      => false,
							'walker'         => new UT_Mega_Menu(),
							'items_wrap'     => '<ul id="%1$s" class="header__menu %2$s">%3$s</ul>',
						] );
					}
					?>
					
			    </div>
		    </nav>
	    </div>
	</header>

	<?php if ( is_product() ) : ?>

		<?php wc_get_template_part( 'single-product/product-panel' ) ?>

	<?php endif; ?>