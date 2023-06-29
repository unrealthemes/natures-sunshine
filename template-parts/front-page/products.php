<?php

/**
 * tab slider Block Template.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'tab-slider-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'section products js-products-tabs';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$terms = get_field('terms_tab_slider');
$count = get_field('count_tab_slider');
?>

<?php if ( !empty( $_POST['query']['preview'] ) ) : ?>

	<figure>
		<img src="<?php echo THEME_URI; ?>'/img/gutenberg-preview/products.png'" alt="Preview" style="width:100%;">
	</figure>

<?php else : ?>

	<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<div class="ut-loader"></div>
		<div class="container"> 

			<?php if ( $terms ) : ?>
				<div class="products-cats inline-scroll">
					<div class="inline-scroll__content">

						<?php 
						foreach  ( $terms as $key => $term ) : 
							$active = (  $key == 0 ) ? 'active loaded' : '';
						?>
							<span class="products-cats__item <?php echo $active; ?>"
								  data-taxonomy="<?php echo $term->taxonomy; ?>"
								  data-id="<?php echo $term->term_id; ?>"
								  data-count="<?php echo $count; ?>">
								<?php echo $term->name; ?>
							</span>
						<?php endforeach; ?>

					</div>
				</div>

				<?php 
				foreach  ( $terms as $key => $term ) : 
					$active = (  $key == 0 ) ? 'active' : '';
				?>

					<?php //if ( $key == 0 ) : ?>

						<?php 
						$args = [
							'post_type' => 'product',
							'post_status' => 'publish',
							'posts_per_page' => $count,
							'tax_query' => [ [
								'taxonomy' => $term->taxonomy,
								'field' => 'term_id', // can be 'term_id', 'slug' or 'name'
								'terms' => [ $term->term_id ],
							], ],
						];
						$loop = new WP_Query( $args ); 
						?>

						<div class="products-slider cards swiper <?php echo $active; ?>"
							 data-taxonomy="<?php echo $term->taxonomy; ?>"
							 data-id="<?php echo $term->term_id; ?>">
							<div class="swiper-wrapper">

								<?php
								if ( $loop->have_posts() ) :
									while ( $loop->have_posts() ) : $loop->the_post(); 
										global $product; 
										$GLOBALS['product'] = wc_get_product( $loop->post->ID ); 
										get_template_part( 
											'woocommerce/content', 
											'product', 
											[ 
												'cat_ids' => implode(', ', $product->get_category_ids()) 
											] 
										);
									endwhile; 
								endif;
								?>

							</div>
							<div class="swiper-button-prev cards__prev"></div>
							<div class="swiper-button-next cards__next"></div>
						</div>

					<?php // else : ?>

						<!-- <div class="products-slider cards swiper"
							 data-taxonomy="<?php //echo $term->taxonomy; ?>"
							 data-id="<?php //echo $term->term_id; ?>">
							<div class="swiper-wrapper"> -->
								<!-- AJAX LOOP -->
							<!-- </div>
							<div class="swiper-button-prev cards__prev"></div>
							<div class="swiper-button-next cards__next"></div>
						</div> -->

					<?php 
					// endif; 
					wp_reset_postdata(); 
					?>

				<?php endforeach; ?>

			<?php endif; ?>

		</div>
	</section>

<?php endif; ?>