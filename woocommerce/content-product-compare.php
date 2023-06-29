<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$eng_name = get_post_meta( $product->get_id(), '_eng_name', true );
$hover_img_id = get_field('img_hover_product', $product->get_id());
$img_url = get_the_post_thumbnail_url( $product->get_id(), 'full' );
$type_view = get_field('type_view_product_price', 'options');
$class_img = ($hover_img_id) ? 'card__image-default' : '';

if ( !$img_url ) {
    $img_url = wc_placeholder_img_src();
}
?>

<div <?php wc_product_class( 'swiper-slide cards__item compare-card', $product ); ?> data-id="<?php echo $product->get_id(); ?>">
    <div class="card favorites-card">
        <div class="card__preview">
            <div class="card__image">
                <img class="<?php echo $class_img; ?>" 
                     src="<?php echo $img_url; ?>" 
                     loading="lazy" 
                     decoding="async" 
                     alt="">

                <?php if ( $hover_img_id ) : ?>
                    <img class="card__image-hover" 
                        src="<?php echo wp_get_attachment_url( $hover_img_id, 'full' ); ?>" 
                        loading="lazy" 
                        decoding="async" 
                        alt="">
                <?php endif; ?>
            </div>
        </div>
        <div class="card__info">

            <?php if ( $eng_name ) : ?>
                <p class="card__type">
                    <span class="card__type-name">
                        <?php echo $eng_name; ?>
                    </span>
                </p>
            <?php endif; ?>

            <h2 class="card__title">
                <a href="<?php echo get_permalink( $product->get_id() ); ?>" class="card__title-link" title="<?php echo $product->get_name(); ?>">
                    <?php echo $product->get_name(); ?>
                </a>
            </h2>
            
            <div class="card__price">
                <div class="card__price-block">

                    <?php if ( $type_view == 3 && ! is_user_logged_in() ) : ?>

                    <?php elseif ( $type_view == 1 && ! is_user_logged_in() ) : ?>

                        <span class="card__price-current"><?php echo strip_tags( wc_price( $product->get_regular_price() ) ); ?></span>
                        
                    <?php else : ?>

                        <?php if ( $product->get_sale_price() ) : ?>
                            <span class="card__price-current"><?php echo strip_tags( wc_price( $product->get_sale_price() ) ); ?></span>
                            <span class="card__price-old"><?php echo strip_tags( wc_price( $product->get_regular_price() ) ); ?></span>
                        <?php else : ?>
                            <span class="card__price-current"><?php echo strip_tags( wc_price( $product->get_regular_price() ) ); ?></span>
                        <?php endif; ?>

                    <?php endif; ?>

                </div>
            </div>

        </div>
        <div class="card__controls">
            
            <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

            <?php if ( ! is_page_template('template-share-compare.php') ) : ?>

                <?php 
                    // get_template_part('template-parts/favorites/icon', null, ['product_id' => $product->get_id()]); 
                    get_template_part(
                        'template-parts/favorites/icon', 
                        null, [
                            'product_id' => $product->get_id(),
                            'is_product_in_wishlist' => ut_help()->wishlist->is_product_in_wishlist( $product->get_id() ),
                        ]
                    ); 
                ?>
                    
                <a href="javascript:;" class="card__controls-btn btn remove_from_compare">
                    <svg width="24" height="24">
                        <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#trash'; ?>"></use>
                    </svg>
                </a>

            <?php endif; ?> 

        </div>
    </div>
</div>