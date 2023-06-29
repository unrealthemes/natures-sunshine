<?php
	/*
	 * Template Name: Post
	 *
	 * */

	get_header(); ?>

	<div class="wrapper">
		<main class="main">

			<div class="breadcrumbs">
				<div class="container">
					<ul class="breadcrumbs-list" role="list">
						<li class="breadcrumbs-list__item">
							<a href="<?= get_site_url(); ?>" class="breadcrumbs-list__link" title="На главную">
								<svg class="breadcrumbs-list__home" width="24" height="24">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#home'; ?>"></use>
								</svg>
							</a>
							<svg class="breadcrumbs-list__divider" width="24" height="24">
								<use
									xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-right'; ?>"></use>
							</svg>
						</li>
						<li class="breadcrumbs-list__item">
							<a href="#" class="breadcrumbs-list__link" title="Категория">Категория</a>
							<svg class="breadcrumbs-list__divider" width="24" height="24">
								<use
									xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-right'; ?>"></use>
							</svg>
						</li>
						<li class="breadcrumbs-list__item breadcrumbs-list__item--current">Карточка товара</li>
					</ul>
				</div>
			</div>
			<section class="post">
				<div class="container">
					<div class="post-header">
						<div class="post-image">
							<img src="<?= DIST_URI . '/images/base/news-5.jpg'; ?>" loading="eager" decoding="async"
							     alt="">
						</div>
					</div>
					<div class="post-content">
						<h1 class="post-title">We believe in the healing power of nature.</h1>
						<p>Nature gives us everything we need to thrive, and as we care for our planet, it will continue
							taking care of us for generations to come.</p>
						<h2>Purity, First and Foremost</h2>
						<p>We keep it simple, using only the cleanest, purest ingredients for your health. That means
							Non-GM0, organic and regenerative whenever possible, and rejecting anything that doesn’t
							meet our exacting, industry-leading standards. Our strict specifications often mean we
							reject raw materials that others will use.</p>
						<div class="wp-block">
							<figure>
								<div class="wp-block__image">
									<img src="<?= DIST_URI . '/images/base/news-7.jpg'; ?>" loading="eager"
									     decoding="async" alt="">
								</div>
								<figcaption>Figcaption</figcaption>
							</figure>
							<p>We keep it simple, using only the cleanest, purest ingredients for your health. That
								means Non-GM0, organic and regenerative whenever possible, and rejecting anything that
								doesn’t meet our exacting, industry-leading standards. Our strict specifications often
								mean we reject raw materials that others will use.</p>
						</div>
						<p>We keep it simple, using only the cleanest, purest ingredients for your health. That means
							Non-GM0, organic and regenerative whenever possible, and rejecting anything that doesn’t
							meet our exacting, industry-leading standards. Our strict specifications often mean we
							reject raw materials that others will use.</p>
						<h4>Purity, First and Foremost</h4>
						<ul>
							<li>Some text</li>
							<li>Some text</li>
						</ul>
						<ol>
							<li>Some text</li>
							<li>Some text</li>
						</ol>
					</div>
				</div>
			</section>

		</main>
	</div>

<?php
	get_footer();
