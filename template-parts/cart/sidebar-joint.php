<div class="cart-sidebar">
	<div class="cart-switcher">
		<div class="cart-switcher__switch switcher">
			<input class="switcher__input js-cart-mode" type="checkbox" name="cart_mode" id="cart_mode" checked>
			<label class="switcher__label" for="cart_mode">
				Режим совместного заказа
				<span class="switcher__slider"></span>
			</label>
		</div>
		<div class="cart-switcher__collapse">
			<div class="cart-switcher__notice">
				Выходя из режима совместного заказа все корзины партнеров удаляться кроме вашей
			</div>
		</div>
	</div>
	<div class="cart-total">
		<p class="cart-total__title text active">
			Итого
			<svg class="cart-total__title-icon" width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
			</svg>
		</p>
		<div class="cart-collapse active">
			<div class="cart-results">
				<div class="cart-results__item">
					<span class="text color-mono-64 bold">Ваша корзина – 78123</span>
					<span class="text">1789 ₴</span>
				</div>
				<div class="cart-results__item">
					<span class="text color-mono-64">Власов А.А – 78123</span>
					<span class="text">1789 ₴</span>
				</div>
				<div class="cart-results__item">
					<span class="text color-mono-64">Власов А.А – 78123</span>
					<span class="text">1789 ₴</span>
				</div>
				<div class="cart-results__item">
					<span class="text color-mono-64">Власов А.А – 78123</span>
					<span class="text">1789 ₴</span>
				</div>
				<div class="cart-results__item">
					<span class="text color-mono-64">Власов А.А – 78123</span>
					<span class="text">1789 ₴</span>
				</div>
				<div class="cart-results__item">
					<span class="text color-mono-64">Власов А.А – 78123</span>
					<span class="text">1789 ₴</span>
				</div>
				<div class="cart-results__item">
					<span class="text color-mono-64">Власов А.А – 78123</span>
					<span class="text">1789 ₴</span>
				</div>
				<div class="cart-results__item">
					<span class="text color-mono-64">Власов А.А – 78123</span>
					<span class="text">1789 ₴</span>
				</div>
				<div class="cart-results__item">
					<span class="text color-mono-64">Власов А.А – 78123</span>
					<span class="text">1789 ₴</span>
				</div>
			</div>
		</div>
		<div class="cart-price">
			<span class="text color-mono-64">В корзинах сейчас 54 товара</span>
			<span class="cart-price__total">
				<span class="card__price-old">1590 ₴</span>
				1898 ₴
			</span>
		</div>
		<button type="submit" class="cart-submit btn btn-green w-100">Перейти к оформлению</button>
	</div>
	<div class="cart-total-joint w-100">
		<p class="cart-total__title text active">
			<img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="">
			Итого
			<svg class="cart-total__title-icon" width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
			</svg>
		</p>
		<div class="cart-collapse active">
			<div class="cart-results">
				<div class="cart-results__item">
					<span class="text">Власов А.А – 78123</span>
					<span class="text">120 PV</span>
				</div>
				<div class="cart-results__item">
					<span class="text">Власов А.А – 78123</span>
					<span class="text">120 PV</span>
				</div>
				<div class="cart-results__item">
					<span class="text">Власов А.А – 78123</span>
					<span class="text">120 PV</span>
				</div>
				<div class="cart-results__item">
					<span class="text">Власов А.А – 78123</span>
					<span class="text">120 PV</span>
				</div>
			</div>
		</div>
		<div class="cart-total-joint__points">
			<span class="text">PV по корзинам</span>
			<span class="cart-total-joint__total bold">1898 PV</span>
		</div>
	</div>
</div>