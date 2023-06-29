<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

get_header();

global $wp_query;

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] === 443 ? "https://" : "http://";
$page_url = "{$protocol}{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$per_page = get_option('posts_per_page');
$post_count = ( (int)get_query_var('paged') - 1 ) * (int)$per_page + (int)$wp_query->post_count; // number of products shown along with previous pages
$show_loadmore = ( $post_count == $wp_query->found_posts || $post_count < 0 ) ? 'style="display: none;"' : '';
$category = get_queried_object();
$bg_banner_id = get_field('bg_banner_cat', $category);
?>

	<div class="wrapper">
		<main class="main">

			<form id="blog_form" action="" method="post">
				<input type="hidden" id="filter_type" name="filter_type" value="">
				<input type="hidden" id="paged" name="paged" value="<?php echo get_query_var('paged') ?: 1; ?>">
				<input type="hidden" id="current_url" name="current_url" value="<?php echo $page_url; ?>">
				<input type="hidden" id="category" name="category" value="<?php echo $category->slug; ?>">
				<input type="hidden" name="current_lang" value="<?php // echo ICL_LANGUAGE_CODE; ?>">
			</form>

			<section class="section blog-full news">
				<div class="container">
					<div class="blog-section">
						<div class="blog-section__header">
							<?php the_archive_title( '<h1 class="blog-section-title">', '</h1>' ); ?>
							<?php the_archive_description( '<div class="blog-section-description">', '</div>' ); ?>
						</div>
						<div class="blog-section__feed news">

							<?php 
							if ( $bg_banner_id ) :
								$bg_banner_url = wp_get_attachment_url( $bg_banner_id, 'full' ); 
								$title_banner = get_field('title_banner_cat', $category);
								$desc_banner = get_field('desc_banner_cat', $category);
								$link = get_field('link_banner_cat', $category);
								$tag = ( $link ) ? 'a' : 'div';
								$href = ( $link ) ? 'href="'. $link .'"' : '';
							?>
								
								<div class="news-item news-item--main">
									<<?php echo $tag; ?> 
										<?php echo $href; ?> 
										class="news-item__image" 
										title="<?php echo $title_banner; ?>">
										<img src="<?php echo $bg_banner_url; ?>" loading="eager" decoding="async" alt="">
									</<?php echo $tag; ?>>
									<div class="news-item__content">
										<h2 class="news-item__title">
											<a href="#" title="<?php echo $title_banner; ?>"><?php echo $title_banner; ?></a>
										</h2>
										<p class="news-item__text">
											<?php echo nl2br( $desc_banner ); ?>
										</p>
									</div>
								</div>

							<?php endif; ?>

							<?php if ( have_posts() ) : ?>

								<?php while ( have_posts() ) : the_post(); ?>

									<?php get_template_part( 'template-parts/blog/content' ); ?>
								
								<?php endwhile; ?>

							<?php else : ?>

								<?php get_template_part( 'template-parts/blog/content', 'none' ); ?>

							<?php endif; ?>

						</div>
					</div>
				</div>
			</section>

			<div class="blog-pagination text-center">
				<div class="ut-loader"></div>
				<div class="container">

					<button class="catalog-footer__button btn btn-green js-load-more" <?php echo $show_loadmore; ?>>
						<?php _e('Load more', 'natures-sunshine'); ?>
					</button>

					<div class="js-pagination">
						<?php
						the_posts_pagination( [
							'prev_text' => '<svg width="24" height="24">
												<use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-left"></use>
											</svg>',
							'next_text' => '<svg width="24" height="24">
												<use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-right"></use>
											</svg>',
							// 'base' => $url . 'p-%#%/',
						] );
						wp_reset_query();
						?>
					</div>

				</div>
			</div>

		</main>
	</div>


<?php
get_footer();