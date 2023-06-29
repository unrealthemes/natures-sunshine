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

$count = ( isset($args['count']) ) ? $args['count'] : null;
$eng_name = get_post_meta( $product->get_id(), '_eng_name', true );
$desc_blocks = get_field('list_short_desc', $product->get_id());
$hover_img_id = get_field('img_hover_product', $product->get_id());

if ( $product->get_upsell_ids() ) {
    $img_id = get_field('img_hover_c_product', $product->get_id());
    $img_url = wp_get_attachment_url( $img_id, 'full' );
} else {
    $img_url = get_the_post_thumbnail_url( $product->get_id(), 'full' );
}

$class_img = ($hover_img_id) ? 'card__image-default' : '';

if ( !$img_url ) {
    $img_url = wc_placeholder_img_src();
}

$cat_ids = ( isset($args['cat_ids']) ) ? 'data-ids="'. $args['cat_ids'] .'"' : '';
$class_wrapp = ( isset($args['class_wrapp']) ) ? $args['class_wrapp'] : 'swiper-slide cards__item';
$complex_class = ( $product->get_upsell_ids() ) ? 'cards__item--complex' : '';
$class_disabled = ( ! $product->is_in_stock() ) ? 'disabled' : '';
$products_list = ut_help()->compare->products_list();
$excerpt = get_the_excerpt();
?>

<div <?php wc_product_class( [$class_wrapp, $complex_class, $class_disabled], $product ); ?> <?php echo $cat_ids; ?> data-count="<?php echo $count; ?>">
    <div class="card">
        <a href="<?php echo get_permalink( $product->get_id() ); ?>" class="card__preview" title="<?php echo $product->get_name(); ?>">
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

            <?php get_template_part( 'woocommerce/global/card', 'badges' ); ?>

        </a>

	    <?php get_template_part( 'woocommerce/global/card', 'icons', ['number' => 4] ); ?>

        <a href="<?php echo get_permalink( $product->get_id() ); ?>" class="card__info" title="<?php echo $product->get_name(); ?>">
            <p class="card__type">

                <?php // if ( $eng_name ) : ?>
                    <span class="card__type-name">
                        <?php echo $eng_name; ?>
                    </span>
                <?php // endif; ?>
                
                <?php // if ( $product->get_sku() ) : ?>
                    <span class="card__type-sku">
                        <?php echo _e('SKU', 'natures-sunshine') . ' ' . $product->get_sku(); ?>
                    </span>
                <?php // endif; ?>

            </p>
            <h2 class="card__title">
                <span class="card__title-link">
                    <?php echo $product->get_name(); ?>
                </span>
            </h2>

            <?php if ( $desc_blocks ): ?>

                <ul class="card__list">
                    <?php 
                    foreach ( $desc_blocks as $key => $desc_block ) : 
                        if (!$desc_block['txt_list_short_desc']) { break; }
                        if ($key == 2) { break; }
                    ?>
                        <li class="card__list-item">
                            <?php echo $desc_block['txt_list_short_desc']; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
 
                <?php if ( count($desc_blocks) > 2 ): ?>
                    <div class="card__collapse">
                        <ul class="card__list">
                            <?php foreach ( $desc_blocks as $key => $desc_block ) :  ?>

                                <?php if ($key >= 2) : ?>
                                    <li class="card__list-item">
                                        <?php echo $desc_block['txt_list_short_desc']; ?>
                                    </li>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            
            <?php elseif ( $excerpt ) : ?>

                <?php 
                // $length = strlen( $excerpt );
                $excerpt_parts = get_excerpt_parts( $excerpt );
                ?>

                <div class="card__list">
                    <div class="card__list-item">
                        <?php // the_excerpt(); ?>
                        <?php echo $excerpt_parts['first']; ?>
                    </div>
                </div> 
                
                <div class="card__collapse">
                    <div class="card__list">
                        <div class="card__list-item">
                            <p><?php echo $excerpt_parts['last']; ?></p>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

            <?php woocommerce_template_loop_price(); ?>
            
        </a>

        <div class="card__controls">

            <?php if ( /*is_page_template('template-catalog.php') &&*/ $product->get_upsell_ids() && $benefit = get_post_meta( $product->get_id(), '_benefit_complex', true ) ) : ?> 

                <span class="card__controls-notice">
                    <?php 
                    echo sprintf( 
                        __('Benefits of buying a set %1s %2s', 'natures-sunshine'),
                        $benefit,
                        get_woocommerce_currency_symbol()
                    ); 
                    ?>
                </span>

            <?php endif; ?>
            
            <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

            <?php 
            get_template_part(
                'template-parts/favorites/icon', 
                null, [
                    'product_id' => $product->get_id(),
                    'is_product_in_wishlist' => ut_help()->wishlist->is_product_in_wishlist( $product->get_id() ),
                ]
            ); 
            ?>

            <?php 
            if ( ! $product->get_upsell_ids() ) :
                get_template_part(
                    'template-parts/compare/icon', 
                    null, 
                    [
                        'product_id' => $product->get_id(),
                        'products_list' => $products_list,
                    ]
                ); 
            endif;
            ?>
            
        </div>

    </div>
</div>