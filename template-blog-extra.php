<?php
	/*
		* Template Name: Blog Extra
		*
		* */

	get_header();
?>

<?php
	$news = [
		[
			'title' => '6 Tips for Making Resolutions into Realizations',
			'text'  => 'Every year we have the opportunity to review our lives and make adjustments or improvements'
		],
		[
			'title' => '9 FREE Holiday Activities for Your Family',
			'text'  => 'Holidays can be fun and festive. But they can also put a strain on the budget if you’re not careful.',
			'tag'   => '#Activities'
		],
		[
			'title' => '9 FREE Holiday Activities for Your Family',
			'text'  => 'Holidays can be fun and festive. But they can also put a strain on the budget if you’re not careful.'
		],
		[
			'title' => '9 FREE Holiday Activities for Your Family',
			'text'  => 'Holidays can be fun and festive. But they can also put a strain on the budget if you’re not careful.',
			'tag'   => '#Activities'
		],
		[
			'title' => '9 FREE Holiday Activities for Your Family',
			'text'  => 'Holidays can be fun and festive. But they can also put a strain on the budget if you’re not careful.'
		],
		[
			'title' => '6 Tips for Making Resolutions into Realizations',
			'text'  => 'Every year we have the opportunity to review our lives and make adjustments or improvements'
		],
		[
			'title' => '9 FREE Holiday Activities for Your Family',
			'text'  => 'Holidays can be fun and festive. But they can also put a strain on the budget if you’re not careful.',
			'tag'   => '#Activities'
		],
		[
			'title' => '9 FREE Holiday Activities for Your Family',
			'text'  => 'Holidays can be fun and festive. But they can also put a strain on the budget if you’re not careful.'
		],
		[
			'title' => '9 FREE Holiday Activities for Your Family',
			'text'  => 'Holidays can be fun and festive. But they can also put a strain on the budget if you’re not careful.',
			'tag'   => '#Activities'
		],
		[
			'title' => '9 FREE Holiday Activities for Your Family',
			'text'  => 'Holidays can be fun and festive. But they can also put a strain on the budget if you’re not careful.'
		]
	];
?>

	<div class="wrapper">
		<main class="main">
			<section class="section blog-full news">
				<div class="container">
					<div class="blog-section">
						<div class="blog-section__header">
							<h1 class="blog-section-title">Блог</h1>
						</div>
						<div class="blog-section__feed news">
							<div class="news-item news-item--main">
								<a href="#" class="news-item__image" title="Goodbye 2021. Hello 2022!">
									<img src="<?= DIST_URI . '/images/base/news-1.jpg'; ?>" loading="eager" decoding="async" alt="">
								</a>
								<div class="news-item__content">
									<h2 class="news-item__title">
										<a href="#" title="Goodbye 2021. Hello 2022!">Goodbye 2021. Hello 2022!</a>
									</h2>
									<p class="news-item__text">We’ve welcomed in a New Year with the start of 2022. As we reflect on 2021, we can also begin looking forward to what this New Year can bring. To help you achieve better success, and support you in setting effective goals, we’ve compiled a few points for you to</p>
								</div>
							</div>
							<?php
							$num = 2;
							foreach ( $news as $key => $item ) :
								if ($num === count($news)) {
									$num = 2;
								} ?>
								<div class="news-item">
									<a href="#" class="news-item__image" title="<?php echo $item['title']; ?>">
										<img src="<?= DIST_URI . '/images/base/news-' . $num . '.jpg'; ?>" loading="lazy" decoding="async" alt="">
									</a>
									<?php if ( $item['tag'] ) : ?>
										<div class="news-item__tags">
											<span class="news-item__tag"><?php echo $item['tag']; ?></span>
										</div>
									<?php endif; ?>
									<h2 class="news-item__title">
										<a href="#" title="<?php echo $item['title']; ?>"><?php echo $item['title']; ?></a>
									</h2>
									<p class="news-item__text"><?php echo $item['text']; ?></p>
								</div>
								<?php $num++;
							endforeach; ?>
						</div>
					</div>
				</div>
			</section>
			<div class="blog-pagination text-center">
				<div class="container">
					<button class="catalog-footer__button btn btn-green js-load-more">Загрузить еще</button>
					<nav class="catalog-footer__pagination pagination text-center">
						<ul class="pagination__list" role="list">
							<li class="pagination__list-item current">1</li>
							<li class="pagination__list-item divider">
								<svg width="24" height="24">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#ellipsis'; ?>"></use>
								</svg>
							</li>
							<li class="pagination__list-item">
								<a href="#" class="pagination__list-link">5</a>
							</li>
							<li class="pagination__list-item">
								<a href="#" class="pagination__list-link">6</a>
							</li>
							<li class="pagination__list-item">
								<a href="#" class="pagination__list-link">7</a>
							</li>
							<li class="pagination__list-item divider">
								<svg width="24" height="24">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#ellipsis'; ?>"></use>
								</svg>
							</li>
							<li class="pagination__list-item">
								<a href="#" class="pagination__list-link">20</a>
							</li>
							<li class="pagination__list-item next">
								<a href="#" class="pagination__list-link">
									<svg width="24" height="24">
										<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-right'; ?>"></use>
									</svg>
								</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</main>
	</div>

<?php
	get_footer();
