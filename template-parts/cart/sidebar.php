<div class="cart-sidebar">
	<div class="cart-switcher">
		<div class="cart-switcher__switch switcher">
			<input class="switcher__input js-cart-mode" type="checkbox" name="cart_mode" id="cart_mode">
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
					<span class="text color-mono-64">2 × Супер Комплекс</span> <span class="text">1789 ₴</span>
				</div>
			</div>
		</div>
		<div class="cart-price">
			<span class="text color-mono-64">В корзине сейчас 4 товара</span>
			<span class="cart-price__total">
				<span class="card__price-old">1590 ₴</span>
				1898 ₴
			</span>
		</div>
		<button type="submit" class="cart-submit btn btn-green w-100">Перейти к оформлению</button>
	</div>
	<div class="cart-points form-checkout__points w-100">
		<div class="card__price-points">
			<img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="">
			<span class="hidden-mobile">Начислим 150 PV</span>
			<span class="hidden-desktop">+150 баллов за заказ</span>
		</div>
		<div class="tooltip">
			<svg width="20" height="20">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
			</svg>
			<span class="tooltip__content tooltip__content--bottom">Some content</span>
		</div>
	</div>
</div>