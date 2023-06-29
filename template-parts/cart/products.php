<div class="cart-products">
	<div class="cart-products__header">
		<h1 class="cart-title">Моя корзина</h1>
	</div>
	<div class="cart-products__search">
		<h2 class="cart-products__title text bold">Поиск товаров</h2>
		<div class="search cart-products__search-field">
			<label for="cart_search" class="hidden">Поиск</label>
			<input type="search" class="search__input" name="cart_search" id="cart_search" placeholder="Найдите товар по названию или артикулу">
			<button type="button" class="search__button search__submit" title="Поиск">
				<svg width="24" height="24">
					<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#search'; ?>"></use>
				</svg>
			</button>
			<button type="reset" class="search__button search__reset hidden" title="Очистить">
				<svg width="24" height="24">
					<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-small'; ?>"></use>
				</svg>
			</button>
		</div>
		<div class="cart-products__search-results">
			<ul class="cart-products__search-list">
				<li class="cart-products__search-item">
					<a href="#" class="product-preview__image" title="Колоїдні мінерали з соком Асаі">
						<img src="<?= DIST_URI . '/images/base/product.png'; ?>" loading="lazy" decoding="async" alt="">
					</a>
					<div class="product-preview__info">
						<span class="product-preview__available product-preview__available--yes">В наличии</span>
						<a href="#" class="product-preview__title text" title="Колоїдні мінерали з соком Асаі">Колоїдні мінерали з соком Асаі</a>
						<span class="product-preview__price card__price-current">1789 ₴</span>
					</div>
					<div class="product-preview__button">
						<button class="product-preview__action btn btn-secondary">Добавить</button>
					</div>
				</li>
				<li class="cart-products__search-item">
					<a href="#" class="product-preview__image" title="Колоїдні мінерали з соком Асаі">
						<img src="<?= DIST_URI . '/images/base/product.png'; ?>" loading="lazy" decoding="async" alt="">
					</a>
					<div class="product-preview__info">
						<span class="product-preview__available product-preview__available--no">Нет в наличии</span>
						<a href="#" class="product-preview__title text" title="Колоїдні мінерали з соком Асаі">Колоїдні мінерали з соком Асаі</a>
						<span class="product-preview__price card__price-current">1789 ₴</span>
					</div>
					<div class="product-preview__button">
						<button class="product-preview__action btn btn-white btn-white--border">Отложить на потом</button>
					</div>
				</li>
			</ul>
			<div class="cart-products__search-clean">
				<button type="button" class="cart-products__button cart-products__search-button btn btn-white">
					<svg class="btn__icon" width="24" height="24">
						<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#trash'; ?>"></use>
					</svg>
					<span>Очистить выбор</span>
				</button>
			</div>
		</div>
	</div>
	<div class="cart-products__list">
		<h2 class="cart-products__title text bold">Список товаров</h2>
		<ul class="cart-products__list-items">
			<li class="cart-products__list-item cart-product">
				<div class="cart-product__info">
					<div class="cart-product__info-image">
						<img class="card__image-default" src="<?= DIST_URI . '/images/base/product.png'; ?>" loading="lazy" decoding="async" alt="">
					</div>
					<div class="cart-product__info-details">
						<div class="cart-product__info-top">
							<span class="cart-product__info-sku">Арт. 2778</span>
							<span class="cart-product__info-old card__price-old">1590 ₴</span>
						</div>
						<div class="cart-product__info-title">
							<h2 class="card__title">
								<a href="#" class="card__title-link" title="Колоїдні мінерали з соком Асаі">Колоїдні мінерали з соком Асаі</a>
							</h2>
							<span class="cart-product__info-price text">1190 ₴</span>
						</div>
						<div class="cart-product__info-bottom">
							<span class="cart-product__info-desc">100 Капсул</span>
							<div class="cart-product__info-points card__price-points">
								<img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="">
								150 PV
							</div>
						</div>
					</div>
				</div>
				<div class="cart-product__controls">
					<div class="cart-product__controls-counter counter">
						<button type="button" class="counter__btn counter-minus">
							<svg width="24" height="24">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#minus'; ?>"></use>
							</svg>
						</button>
						<input class="qty" id="qty" name="qty" type="text" min="1" pattern="[0-9.]+" value="1">
						<button type="button" class="counter__btn counter-plus">
							<svg width="24" height="24">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#plus'; ?>"></use>
							</svg>
						</button>
					</div>
					<div class="cart-product__controls-points card__price-points">
						<img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="">
						150 PV
					</div>
					<a href="#" class="cart-products__button cart-product__book btn btn-white">
						<span class="full">Отложить на потом</span>
						<span class="short">Отложить</span>
					</a>
					<a href="#" class="cart-products__button cart-product__delete btn btn-square">
						<svg width="24" height="24">
							<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#trash'; ?>"></use>
						</svg>
					</a>
				</div>
			</li>
			<li class="cart-products__list-item cart-product">
				<div class="cart-product__info">
					<div class="cart-product__info-image">
						<img class="card__image-default" src="<?= DIST_URI . '/images/base/product.png'; ?>" loading="lazy" decoding="async" alt="">
					</div>
					<div class="cart-product__info-details">
						<div class="cart-product__info-top">
							<span class="cart-product__info-sku">Арт. 2778</span>
							<span class="cart-product__info-old card__price-old">1590 ₴</span>
						</div>
						<div class="cart-product__info-title">
							<h2 class="card__title">
								<a href="#" class="card__title-link" title="Колоїдні мінерали з соком Асаі">Колоїдні мінерали з соком Асаі</a>
							</h2>
							<span class="cart-product__info-price text">1190 ₴</span>
						</div>
						<div class="cart-product__info-bottom">
							<span class="cart-product__info-desc">100 Капсул</span>
							<div class="cart-product__info-points card__price-points">
								<img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="">
								150 PV
							</div>
						</div>
					</div>
				</div>
				<div class="cart-product__controls">
					<div class="cart-product__controls-counter counter">
						<button type="button" class="counter__btn counter-minus">
							<svg width="24" height="24">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#minus'; ?>"></use>
							</svg>
						</button>
						<input class="qty" id="qty" name="qty" type="text" min="1" pattern="[0-9.]+" value="1">
						<button type="button" class="counter__btn counter-plus">
							<svg width="24" height="24">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#plus'; ?>"></use>
							</svg>
						</button>
					</div>
					<div class="cart-product__controls-points card__price-points">
						<img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="">
						150 PV
					</div>
					<a href="#" class="cart-products__button cart-product__book btn btn-white">
						<span class="full">Отложить на потом</span>
						<span class="short">Отложить</span>
					</a>
					<a href="#" class="cart-products__button cart-product__delete btn btn-square">
						<svg width="24" height="24">
							<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#trash'; ?>"></use>
						</svg>
					</a>
				</div>
			</li>
			<li class="cart-products__list-item cart-product">
				<div class="cart-product__info">
					<div class="cart-product__info-image">
						<img class="card__image-default" src="<?= DIST_URI . '/images/base/product.png'; ?>" loading="lazy" decoding="async" alt="">
					</div>
					<div class="cart-product__info-details">
						<div class="cart-product__info-top">
							<span class="cart-product__info-sku">Арт. 2778</span>
							<span class="cart-product__info-old card__price-old">1590 ₴</span>
						</div>
						<div class="cart-product__info-title">
							<h2 class="card__title">
								<a href="#" class="card__title-link" title="Колоїдні мінерали з соком Асаі">Колоїдні мінерали з соком Асаі</a>
							</h2>
							<span class="cart-product__info-price text">1190 ₴</span>
						</div>
						<div class="cart-product__info-bottom">
							<span class="cart-product__info-desc">100 Капсул</span>
							<div class="cart-product__info-points card__price-points">
								<img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="">
								150 PV
							</div>
						</div>
					</div>
				</div>
				<div class="cart-product__controls">
					<div class="cart-product__controls-counter counter">
						<button type="button" class="counter__btn counter-minus">
							<svg width="24" height="24">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#minus'; ?>"></use>
							</svg>
						</button>
						<input class="qty" id="qty" name="qty" type="text" min="1" pattern="[0-9.]+" value="1">
						<button type="button" class="counter__btn counter-plus">
							<svg width="24" height="24">
								<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#plus'; ?>"></use>
							</svg>
						</button>
					</div>
					<div class="cart-product__controls-points card__price-points">
						<img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="">
						150 PV
					</div>
					<a href="#" class="cart-products__button cart-product__book btn btn-white">
						<span class="full">Отложить на потом</span>
						<span class="short">Отложить</span>
					</a>
					<a href="#" class="cart-products__button cart-product__delete btn btn-square">
						<svg width="24" height="24">
							<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#trash'; ?>"></use>
						</svg>
					</a>
				</div>
			</li>
		</ul>
	</div>
</div>
