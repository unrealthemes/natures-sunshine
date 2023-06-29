<?php
/*
	* Template Name: Blog
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
		]
	];
?>

	<div class="wrapper">
		<main class="main">

			<section class="section news blog-page">
				<div class="container">
					<div class="blog-section">
						<div class="blog-section__header">
							<h1 class="blog-section-title">Блог</h1>
							<a href="#" class="blog-section__more btn btn-secondary">Смотреть все</a>
						</div>
						<div class="blog-section__feed news swiper blog-swiper">
							<div class="swiper-wrapper">
								<?php foreach ( $news as $key => $item ) : ?>
									<div class="swiper-slide news-item">
										<a href="#" class="news-item__image" title="<?php echo $item['title']; ?>"> <img
												src="<?= DIST_URI . '/images/base/news-' . ( $key + 5 ) . '.jpg'; ?>"
												loading="lazy" decoding="async" alt=""> </a>
										<?php if ( $item['tag'] ) : ?>
											<div class="news-item__tags">
												<span class="news-item__tag"><?php echo $item['tag']; ?></span>
											</div>
										<?php endif; ?>
										<h2 class="news-item__title">
											<a href="#"
											   title="<?php echo $item['title']; ?>"><?php echo $item['title']; ?></a>
										</h2>
										<p class="news-item__text"><?php echo $item['text']; ?></p>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="blog-section">
						<div class="blog-section__header">
							<h2 class="blog-section-title h1">Новости</h2>
							<a href="#" class="blog-section__more btn btn-secondary">Смотреть все</a>
						</div>
						<div class="blog-section__feed news swiper blog-swiper">
							<div class="swiper-wrapper">
								<?php foreach ( $news as $key => $item ) : ?>
									<div class="swiper-slide news-item">
										<a href="#" class="news-item__image" title="<?php echo $item['title']; ?>"> <img
												src="<?= DIST_URI . '/images/base/news-' . ( $key + 5 ) . '.jpg'; ?>"
												loading="lazy" decoding="async" alt=""> </a>
										<?php if ( $item['tag'] ) : ?>
											<div class="news-item__tags">
												<span class="news-item__tag"><?php echo $item['tag']; ?></span>
											</div>
										<?php endif; ?>
										<h2 class="news-item__title">
											<a href="#"
											   title="<?php echo $item['title']; ?>"><?php echo $item['title']; ?></a>
										</h2>
										<p class="news-item__text"><?php echo $item['text']; ?></p>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="blog-section">
						<div class="blog-section__header">
							<h2 class="blog-section-title h1">Акции</h2>
							<a href="#" class="blog-section__more btn btn-secondary">Смотреть все</a>
						</div>
						<div class="blog-section__feed news swiper blog-swiper">
							<div class="swiper-wrapper">
								<?php foreach ( $news as $key => $item ) : ?>
									<div class="swiper-slide news-item">
										<a href="#" class="news-item__image" title="<?php echo $item['title']; ?>"> <img
												src="<?= DIST_URI . '/images/base/news-' . ( $key + 5 ) . '.jpg'; ?>"
												loading="lazy" decoding="async" alt=""> </a>
										<?php if ( $item['tag'] ) : ?>
											<div class="news-item__tags">
												<span class="news-item__tag"><?php echo $item['tag']; ?></span>
											</div>
										<?php endif; ?>
										<h2 class="news-item__title">
											<a href="#"
											   title="<?php echo $item['title']; ?>"><?php echo $item['title']; ?></a>
										</h2>
										<p class="news-item__text"><?php echo $item['text']; ?></p>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</section>

		</main>
	</div>

<?php
	get_footer();
