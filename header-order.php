<?php 
$logo_id = get_field('logo_header', 'options');
$phone = get_field('phone_header', 'options');
$working_mode = get_field('working_mode_header', 'options');
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

	<link rel="preload" href="<?= get_template_directory_uri() . '/assets/dist/fonts/Circe/Circe-Regular.woff2' ?>" as="font" type="font/woff2" crossorigin="anonymous">
	<link rel="preload" href="<?= get_template_directory_uri() . '/assets/dist/fonts/Circe/Circe-Bold.woff2' ?>" as="font" type="font/woff2" crossorigin="anonymous">

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?> 

	<header class="header header-order">
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

							<?php if ( WC()->cart->is_empty() || is_cart() || is_checkout() ) : ?>
								<a href="<?php echo home_url(); // ut_get_permalik_by_template('template-catalog.php'); ?>" class="header__back btn btn-icon">
									<svg class="btn__icon" width="24" height="24">
										<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#arrow-left'; ?>"></use>
									</svg>
									<?php _e('Back to shopping', 'natures-sunshine'); ?>
								</a>
							<?php endif; ?>

						</div>
					<?php endif; ?>

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
	</header>