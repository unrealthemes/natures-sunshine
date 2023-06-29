<?php 
$logo_id = get_field('logo_header', 'options');
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

	<header class="header header-profile">
		<div class="header__main">
			<div class="container">
				<div class="header__main-inner">

					<?php if ( $logo_id ) : ?>
						<div class="header__logo logo">
							<a href="<?= get_site_url(); ?>" class="logo__link">
								<img src="<?php echo wp_get_attachment_url( $logo_id, 'full' ); ?>" 
									width="140" 
									height="40" 
									loading="lazy" 
									decoding="async" 
									alt="<?php echo get_bloginfo( 'name' ); ?>">
							</a>
						</div>
					<?php endif; ?>

					<div class="header__logout">
						<a href="<?php echo wp_logout_url( home_url() ); ?>" class="header__logout-link">
							<svg width="24" height="24"><use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#logout'; ?>"></use></svg>
							<span><?php _e('Logout', 'natures-sunshine'); ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</header>