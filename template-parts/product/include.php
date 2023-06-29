<section class="section products include">
	<div class="container">
		<h2 class="products__title">В состав комплекса входит</h2>
		<div class="products-slider cards swiper">
			<div class="swiper-wrapper">
				<?php $i = 1; while ( $i <= 6 ) : ?>
					<div class="swiper-slide cards__item">
						<div class="card">
							<div class="card__preview">
								<div class="card__image">
									<img class="card__image-default" src="<?= DIST_URI . '/images/base/product.png'; ?>" loading="lazy" decoding="async" alt="">
									<img class="card__image-hover" src="<?= DIST_URI . '/images/base/product-hover.png'; ?>" loading="lazy" decoding="async" alt="">
								</div>
							</div>
							<div class="card__info">
								<p class="card__type">
									<span class="card__type-name">Super Complex</span>
								</p>
								<h2 class="card__title">
									<a href="#" class="card__title-link" title="Супер Комплекс">Супер Комплекс</a>
								</h2>
								<div class="card__price">
									<div class="card__price-block">
										<span class="card__price-current">1190 ₴</span>
										<span class="card__price-old">1590 ₴</span>
										<span class="card__price-notice">Партнерская цена</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php $i++; endwhile; ?>
			</div>
			<div class="swiper-button-prev cards__prev"></div>
			<div class="swiper-button-next cards__next"></div>
		</div>
	</div>
</section>