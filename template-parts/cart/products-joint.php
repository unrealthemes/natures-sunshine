<div class="cart-products">
	<div class="cart-products__header">
		<?php the_title('<h1 class="cart-title">', '</h1>'); ?>
	</div>

	<div class="cart-products__info">
		<h2 class="cart-products__info-title text bold"><?php _e('Shared cart mode', 'natures-sunshine'); ?></h2>
		<p class="cart-products__info-text text"><?php _e('In shared basket mode, you can create multiple additional baskets for other Sunshine IDs.', 'natures-sunshine'); ?></p>
	</div>

	<div class="cart-products__partner">
		<h2 class="cart-products__title text bold"><?php _e('Add partner', 'natures-sunshine'); ?></h2>
		<div class="cart-products__partner-row">
			<input type="text" name="partner_id" id="partner_id" placeholder="ID">
			<button type="button" class="cart-products__partner-add btn btn-secondary js-joint-id"><?php _e('Add ID', 'natures-sunshine'); ?></button>
		</div>
	</div>

	<div class="cart-products__block">
		<div class="cart-products__block-controls">
			<span class="btn btn-square cart-products__block-button">
				<svg width="24" height="24">
					<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#dots-menu'; ?>"></use>
				</svg>
			</span>
			<div class="cart-products__block-extra">
				<a href="#" class="cart-products__block-link cart-products__block-duplicate js-cart-duplicate"><?php _e('Duplicate cart', 'natures-sunshine'); ?></a>
				<a href="#remove_cart" data-fancybox class="cart-products__block-link cart-products__block-remove"><?php _e('Delete', 'natures-sunshine'); ?></a>
			</div>
		</div>
		<div class="cart-total__title active">
			<h2><?php _e('Your cart', 'natures-sunshine'); ?> – 34231</h2>
			<span class="btn btn-transparent btn-square">
				<svg class="cart-total__title-icon" width="24" height="24">
					<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
				</svg>
			</span>
		</div>
		<div class="cart-collapse active">
			<div class="cart-products__search">
				<h2 class="cart-products__title text bold"><?php _e('Product search', 'natures-sunshine'); ?></h2>
				<div class="search cart-products__search-field">
					<label for="cart_search" class="hidden"><?php _e('Search', 'natures-sunshine'); ?></label>
					<input type="search" class="search__input" name="cart_search" id="cart_search" placeholder="<?php _e('Find a product by name or article number', 'natures-sunshine'); ?>">
					<button type="button" class="search__button search__submit" title="<?php _e('Search', 'natures-sunshine'); ?>">
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
						<button class="cart-products__button cart-products__search-button btn btn-white">
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
		<div class="cart-points">
			<div class="card__price-points">
				<img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="">
				<span class="hidden-mobile">Начислим 150 PV</span>
				<span class="hidden-desktop text">+150 баллов за заказ</span>
			</div>
			<div class="tooltip">
				<svg width="20" height="20">
					<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
				</svg>
				<span class="tooltip__content tooltip__content--bottom">Some content</span>
			</div>
		</div>
		<div class="cart-results__item">
			<span class="text color-mono-64">В корзине 4 товара на сумму</span>
			<span class="text">1789 ₴</span>
		</div>
		<div class="cart-results__item">
			<span class="text color-mono-64">Скидка</span>
			<span class="text color-green">189 ₴</span>
		</div>
		<div class="cart-price">
			<span class="text">Подытог</span>
			<span class="cart-price__total">1898 ₴</span>
		</div>
	</div>
	<div class="cart-products__block">
		<div class="cart-products__block-controls">
			<span class="btn btn-square cart-products__block-button">
				<svg width="24" height="24">
					<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#dots-menu'; ?>"></use>
				</svg>
			</span>
			<div class="cart-products__block-extra">
				<a href="#" class="cart-products__block-link cart-products__block-duplicate">Дублировать корзину</a>
				<a href="#remove_cart" data-fancybox class="cart-products__block-link cart-products__block-remove">Удалить</a>
			</div>
		</div>
		<div class="cart-total__title active">
			<h2 class="color-mono-32">Лясов К. И – 78892</h2>
			<span class="btn btn-transparent btn-square">
				<svg class="cart-total__title-icon" width="24" height="24">
					<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
				</svg>
			</span>
		</div>
		<div class="cart-collapse active">
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
		<div class="cart-points">
			<div class="card__price-points">
				<img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="">
				<span class="hidden-mobile">Начислим 150 PV</span>
				<span class="hidden-desktop text">+150 баллов за заказ</span>
			</div>
			<div class="tooltip">
				<svg width="20" height="20">
					<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
				</svg>
				<span class="tooltip__content tooltip__content--bottom">Some content</span>
			</div>
		</div>
		<div class="cart-price">
			<span class="text">Подытог</span>
			<span class="cart-price__total">1898 ₴</span>
		</div>
	</div>
</div>
