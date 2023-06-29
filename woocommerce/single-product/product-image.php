<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$post_thumbnail_id = $product->get_image_id();
$post_thumbnail_src = ( $post_thumbnail_id ) ? wp_get_attachment_url( $post_thumbnail_id, 'full' ) : wc_placeholder_img_src( 'woocommerce_single' );
$attachment_ids = $product->get_gallery_image_ids();
?>

<?php do_action( 'woocommerce_product_thumbnails' ); ?>

<div class="product-slider swiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide product-slider__item">
            <div class="product-slider__card">
                <div class="product-slider__card-image panzoom">
                    <img src="<?php echo $post_thumbnail_src; ?>" 
                         class="panzoom__content"
                         loading="eager" 
                         decoding="async" 
                         alt="">
                </div>

                <?php get_template_part( 'woocommerce/global/card', 'badges', [ 'span_class' => 'card__badge--text' ] ); ?>

                <?php get_template_part( 'woocommerce/global/card', 'icons', ['number' => null] ); ?>

            </div>
        </div>

        <?php if ( $attachment_ids ) : ?>

            <?php 
            foreach ( $attachment_ids as $attachment_id ) : 
                $youtube_url = get_field('youtube_url', $attachment_id);
            ?>

                <div class="swiper-slide product-slider__item">
                    <div class="product-slider__card">
                        <div class="product-slider__card-image panzoom">
                            
                            <?php if ($youtube_url) : ?>
                                
                                <?php echo $youtube_url; ?>

                            <?php else : ?>

                                <img src="<?php echo wp_get_attachment_url( $attachment_id, 'full' ); ?>"
                                    class="panzoom__content"
                                    loading="eager" 
                                    decoding="async" 
                                    alt="Product image">

                            <?php endif; ?>

                        </div>
                    </div>
                </div>
        
            <?php endforeach; ?>

        <?php endif; ?>

    </div>
</div>
