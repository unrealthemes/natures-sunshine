<div class="popup cart-popup" id="cart_id">
	<div class="popup__header">
		<h2 class="popup__title">Такого ID пока нет</h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
		<p class="popup__header-text text">Создать корзину на ID – 135892?</p>
	</div>
	<button type="button" class="popup__action btn btn-green">Создать</button>
</div>

<div class="popup cart-popup" id="remove_cart">
	<div class="popup__header">
		<h2 class="popup__title">Удалить корзину?</h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
		<p class="popup__header-text text">Данное действие приведет к удалению корзины</p>
	</div>
	<button type="button" class="popup__action btn btn-secondary">Продолжить</button>
</div>

<div class="popup cart-popup" id="joint_exit">
	<div class="popup__header">
		<h2 class="popup__title">Вы точно хотите выйти?</h2>
		<button class="popup__close js-close-popup">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
			</svg>
		</button>
		<p class="popup__header-text text">Все корзины удаляться кроме вашей</p>
	</div>
	<button type="button" class="popup__action btn btn-secondary">Выйти</button>
</div>
