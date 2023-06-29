<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$availability = $product->get_availability();
$products_list = ut_help()->compare->products_list();
$eng_name = get_post_meta( $product->get_id(), '_eng_name', true );
?>

<div class="product-info__mobile hidden-desktop">

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
    wc_get_template(
        'single-product/stock.php',
        array(
            'product'      => $product,
            'class'        => $availability['class'],
            'availability' => $availability['availability'],
        )
    );
    ?>

    <?php
    get_template_part(
        'template-parts/compare/icon',
        null,
        [
            'product_id' => $product->get_id(),
            'products_list' => $products_list,
        ]
    );
    ?>

</div>

<div class="product-info__top">

    <?php if ( $product->get_sku() ) : ?>
        <span class="product-info__text product-info__sku">
            <?php echo _e('SKU', 'natures-sunshine') . ' ' . $product->get_sku(); ?>
        </span>
    <?php endif; ?>

    <?php if ( $eng_name ) : ?>
        <span class="product-info__text product-info__desc">
            <?php echo $eng_name; ?>
        </span>
    <?php endif; ?>

    <?php
    wc_get_template(
        'single-product/stock.php',
        array(
            'product'      => $product,
            'class'        => $availability['class'],
            'availability' => $availability['availability'],
        )
    );
    ?>

</div>