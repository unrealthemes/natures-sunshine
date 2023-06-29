<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

global $wp_query;

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] === 443 ? "https://" : "http://";
$page_url = "{$protocol}{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$current_page = (int)get_query_var('paged');
$per_page = get_option('posts_per_page');
$post_count = ( $current_page - 1 ) * (int)$per_page + (int)$wp_query->post_count; // number of products shown along with previous pages
$show_loadmore = ( $post_count == $wp_query->found_posts || $post_count < 0 ) ? 'style="display: none;"' : '';
$category = get_queried_object();
?>

<div class="wrapper">
	<main class="main">

		<form id="product_form" action="" method="post">
			<input type="hidden" id="filter_type" name="filter_type" value="">
			<input type="hidden" id="paged" name="paged" value="<?php echo $current_page ?: 1; ?>">
			<input type="hidden" id="current_url" name="current_url" value="<?php echo $page_url; ?>">
			<input type="hidden" id="category" name="category" value="<?php echo $category->slug; ?>">
			<input type="hidden" name="current_lang" value="<?php // echo ICL_LANGUAGE_CODE; ?>">
		</form>

		<?php do_action( 'echo_kama_breadcrumbs' ); ?>

		<section class="catalog-content">
			<div class="container"> 

				<div class="catalog-body">
					<div class="cards catalog-cards catalog-cards--full">
						<div class="ut-loader"></div>
						
						<?php if ( have_posts() ) : ?>

							<?php 
							while ( have_posts() ) : the_post(); 
								global $product; 
								$product_class = ( $product->get_upsell_ids() ) ? 'cards__item cards__item--complex' : 'cards__item';
								$product_type = ( $product->get_upsell_ids() ) ? 'complex' : 'simple';
								ob_start();
								get_template_part( 
									'woocommerce/content', 
									'product', 
									[
										'class_wrapp' => $product_class,
									] 
								);
								$product_html = ob_get_clean();
								$product_types[ $product_type ][] = $product_html;
							endwhile; 

							foreach ( $product_types as $product_type ) :
								foreach ( $product_type as $_product ) :
									$products_html .= $_product;
								endforeach;
							endforeach;
							
							echo $products_html;
							?>

						<?php else : ?>

							<?php echo '<h3>' . __( 'No results were found for your parameters.', 'natures-sunshine' ) . '</h3>'; ?>

						<?php endif; ?>
						
					</div>
				</div>

				<div class="catalog-footer text-center product-pagination">
					<div class="ut-loader"></div>
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
						] );
						?>
					</div>
				</div>
			</div>
		</section>

	</main> 
</div>

<?php
get_footer( 'shop' );