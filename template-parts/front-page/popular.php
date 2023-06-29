<?php

/**
 * popular Block Template.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'popular-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'section products';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$title = get_field('title_popular');
$sort = get_field('sort_popular');
$count = get_field('count_popular');

if ( empty($title) ) {
	$title = __('Popular', 'natures-sunshine');
}

$args = [
	'post_type' => 'product',
	'post_status' => 'publish',
	'posts_per_page' => $count,
];

if ( $sort == 'recently_watched' ) {
	$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
	$viewed_products = array_filter( array_map( 'absint', $viewed_products ) );

	if ( empty( $viewed_products ) ) {
		return;
	}

	$args['post__in'] = $viewed_products;
	$args['orderby'] = 'rand';

} else { // popular by rating

	$args['orderby'] = 'meta_value_num';
	$args['order'] = 'desc';
	$args['meta_key'] = '_wc_average_rating';
}
$i = 0;
$loop = new WP_Query( $args );
?>

<?php if ( !empty( $_POST['query']['preview'] ) ) : ?>

    <figure>
        <img src="<?php echo THEME_URI; ?>'/img/gutenberg-preview/popular.png'" alt="Preview" style="width:100%;">
    </figure>

<?php else : ?>
	
	<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<div class="container">

			<?php if ( $title ) : ?>
				<h2 class="products__title"><?php echo $title; ?></h2>
			<?php endif; ?>
			
			<?php if ( $loop->have_posts() ) : ?>
				<div class="products-slider cards swiper">
					<div class="swiper-wrapper">

						<?php 
						while ( $loop->have_posts() ) : $loop->the_post(); 
							global $product; 
							$GLOBALS['product'] = wc_get_product( $loop->post->ID ); 
							get_template_part( 
								'woocommerce/content', 
								'product',
								[
									'count' => $i,
								]
							);
							$i++;
						endwhile; 
						?>

					</div>
					<div class="swiper-button-prev cards__prev"></div>
					<div class="swiper-button-next cards__next"></div>
				</div>
			<?php endif; wp_reset_postdata(); ?>

		</div>
	</section>

<?php endif; ?>