<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$args = [
    'posts_per_page' => get_option('posts_per_page'),
    'orderby' => 'rand',
    'order' => 'desc',
];
$related_products = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['posts_per_page'], null ) ), 'wc_products_array_filter_visible' );
$related_products = wc_products_array_orderby( $related_products, $args['orderby'], $args['order'] );

if ( $related_products ) : 
    $heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) );
?>

    <section class="section products">
        <div class="container">

            <?php if ( $heading ) : ?>
                <h2 class="products__title"><?php echo esc_html( $heading ); ?></h2>
            <?php endif; ?>

            <div class="products-slider cards swiper">
                <div class="swiper-wrapper">

                
                <?php woocommerce_product_loop_start(); ?>

                <?php foreach ( $related_products as $related_product ) : ?>

                        <?php
                        $post_object = get_post( $related_product->get_id() );

                        setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

                        wc_get_template_part( 'content', 'product' );
                        ?>

                <?php endforeach; ?>

                <?php woocommerce_product_loop_end(); ?>


                </div>
                <div class="swiper-button-prev cards__prev"></div>
                <div class="swiper-button-next cards__next"></div>
            </div>
        </div>
    </section>

	<?php
endif;

wp_reset_postdata();
