<?php

/**
 * seo Block Template.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'seo-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'categories section';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$title = get_field('title_terms_products');
$terms = get_field('terms_products');
?>

<?php if ( !empty( $_POST['query']['preview'] ) ) : ?>

    <figure>
        <img src="<?php echo THEME_URI; ?>'/img/gutenberg-preview/categories-products.png'" alt="Preview" style="width:100%;">
    </figure>

<?php else : ?>

	<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<div class="container">

			<?php if ( $title ) : ?>
				<h2 class="categories__title"><?php echo $title; ?></h2>
			<?php endif; ?>

			<?php if ( $terms ) : ?>
				<div class="categories-carousel swiper row-slider">
					<div class="swiper-wrapper">

						<?php 
						foreach  ( $terms as $term ) : 
							// $thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true ); 
							$thumb_id = get_field('img_c_p', $term); 
							$img_url = ( $thumb_id ) ? wp_get_attachment_url( $thumb_id, 'full' ) : wc_placeholder_img_src();
							// $link = get_term_link( (int)$term->term_id, 'product_cat' );
							$link = home_url('/catalog/pc-in-bady/cp-in-'. $term->slug .'/');
						?>
							<div class="swiper-slide categories-carousel__slide">
								<a href="<?php echo $link; ?>" class="categories-carousel__link" title="<?php echo $term->name; ?>">
									<div class="categories-carousel__image">
										<img src="<?php echo $img_url; ?>" 
											 loading="lazy" 
											 decoding="async" 
											 alt="<?php echo $term->name; ?>">
									</div>
									<span class="categories-carousel__title"><?php echo $term->name; ?></span>
								</a>
							</div>
						<?php endforeach; ?>

					</div>
					<div class="swiper-button-prev categories-carousel__prev row-slider__prev"></div>
					<div class="swiper-button-next categories-carousel__next row-slider__next"></div>
				</div>
			<?php endif; ?>

		</div>
	</section>

<?php endif; ?>