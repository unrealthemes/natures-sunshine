<?php 
global $product;

$info_block = get_post_meta( $product->get_id(), '_info_block', true );

if ( ! $info_block ) {
    return false;
}

$shipping_txt = get_post_meta( $product->get_id(), '_shipping_txt', true );

if ( !$product->get_sale_price() && !$product->get_regular_price() && !$shipping_txt ) {
    return false;
}

$discount_price = (int)$product->get_regular_price() - (int)$product->get_sale_price();
$discount_percent = $discount_price * 100 / (int)$product->get_regular_price();
$discount_percent = number_format($discount_percent, 0, '.', '');

$type_view = get_field('type_view_product_price', 'options');
?>

<div class="product-info__sale">

    <?php if ( ($type_view == 3 || $type_view == 1) && ! is_user_logged_in() ) : ?>

    <?php else : ?>

        <?php if ( $product->get_sale_price() && $product->get_regular_price() ) : ?>
            <div class="product-info__calculate">
                <div class="product-info__calculate-item product-info__calculate-result">
                    <span class="product-info__calculate-text"><?php _e('Partner price', 'natures-sunshine'); ?></span>
                    <p class="product-info__calculate-price"><?php echo strip_tags( wc_price( $product->get_sale_price() ) ); ?></p>
                </div>
                <div class="product-info__calculate-divider">
                    <svg width="24" height="24">
                        <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#equal'; ?>"></use>
                    </svg>
                </div>
                <div class="product-info__calculate-item product-info__calculate-start">
                    <span class="product-info__calculate-text"><?php _e('Retail price', 'natures-sunshine'); ?></span>
                    <p class="product-info__calculate-price"><?php echo strip_tags( wc_price( $product->get_regular_price() ) ); ?></p>
                </div>
                <div class="product-info__calculate-divider">
                    <svg width="24" height="24">
                        <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#minus'; ?>"></use>
                    </svg>
                </div>
                <div class="product-info__calculate-item product-info__calculate-sale">
                    <span class="product-info__calculate-text"><?php echo __('Discount', 'natures-sunshine') . ' ' . $discount_percent; ?>%</span>
                    <p class="product-info__calculate-price"><?php echo strip_tags( wc_price( $discount_price ) ); ?></p>
                </div>
                <span class="product-info__calculate-badge card__badge card__badge--ghost">
                    <img class="card__badge-icon" src="<?= DIST_URI . '/images/icons/percent.svg'; ?>" width="24" height="24" loading="eager" decoding="async" alt="">
                </span>
            </div>
        <?php endif; ?>

    <?php endif; ?>

    <?php 
    if ( $shipping_txt ) : 
        $shipping_info = get_post_meta( $product->get_id(), '_shipping_info', true );
    ?>
        <div class="product-info__delivery">
            <svg class="product-info__delivery-icon" width="24" height="24">
                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#motocycle'; ?>"></use>
            </svg>
            <p class="product-info__delivery-text text"><?php echo $shipping_txt; ?></p>

            <?php if ( $shipping_info ) : ?>
	            <div class="product-info__delivery-notice">
		            <div class="tooltip">
                        <div class="tooltip-text"><?php echo nl2br($shipping_info); ?></div>
			            <svg width="20" height="20">
				            <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info'; ?>"></use>
			            </svg>
		            </div>
	            </div>
            <?php endif; ?>
            
        </div>
    <?php endif; ?>

</div>