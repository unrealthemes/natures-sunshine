<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$pv = get_post_meta( $product->get_id(), '_pv', true );
$type_view = get_field('type_view_product_price', 'options');
?>

<div class="product-info__price card__price">
    <div class="card__price-block">

        <?php if ( $type_view == 3 && ! is_user_logged_in() ) : ?>

        <?php elseif ( $type_view == 1 && ! is_user_logged_in() ) : ?>

            <span class="card__price-current"><?php echo strip_tags( wc_price( $product->get_regular_price() ) ); ?></span>
            <span class="card__price-notice"><?php _e('Affiliate price', 'natures-sunshine') ;?></span>

        <?php else : ?>
            
            <?php if ( $product->get_sale_price() ) : ?>
                <span class="card__price-current"><?php echo strip_tags( wc_price( $product->get_sale_price() ) ); ?></span>
                <span class="card__price-old"><?php echo strip_tags( wc_price( $product->get_regular_price() ) ); ?></span>
            <?php else : ?>
                <span class="card__price-current"><?php echo strip_tags( wc_price( $product->get_regular_price() ) ); ?></span>
            <?php endif; ?>

            <?php if ( $product->get_upsell_ids() && $benefit = get_post_meta( $product->get_id(), '_benefit_complex', true ) ) : ?> 

                <div class="benefit-wrapper">
                    <span class="card__price-notice"><?php _e('Retail price', 'natures-sunshine') ;?> - </span>
                    <span class="card__controls-notice">
                        <?php 
                        echo sprintf( 
                            __('Benefits of buying a set %1s %2s', 'natures-sunshine'),
                            $benefit,
                            get_woocommerce_currency_symbol()
                        ); 
                        ?>
                    </span>
                </div>

            <?php else : ?>

                <span class="card__price-notice"><?php _e('Retail price', 'natures-sunshine') ;?></span>

            <?php endif; ?>

        <?php endif; ?>

    </div>
    
    <?php if ( $pv ) : ?>
        <div class="card__price-points">
            <img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="PV">
            <?php echo $pv; ?> PV
        </div>
    <?php endif; ?>

</div>