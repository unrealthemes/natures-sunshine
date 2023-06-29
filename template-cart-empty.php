<?php
	/*
	 * Template Name: Cart Empty
	 *
	 * */

	$redirect_url = get_home_url() . '/account';
	$login_url = ut_get_permalik_by_template('template-login.php') . '?redirect_url=' . $redirect_url;

	get_header( 'order' ); ?>

	<div class="wrapper">
		<main class="main flex">
				<div class="container">
					<div class="cart-empty">
						<div class="cart-empty__image">
							<svg width="151" height="152">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cart-empty'; ?>"></use>
							</svg>
						</div>
						<h1 class="cart-empty__title">Корзина пуста</h1>
						<p class="cart-empty__text color-mono-64">Если вы наполняли корзину при прошлом визите, авторизуйтесь, чтобы увидеть выбранные товары</p>
						<a href="<?php echo $login_url; ?>" class="cart-empty__button btn btn-green">Авторизоваться</a>
				</div>
			</div>
		</main>
	</div>

<?php
	get_footer('empty');
