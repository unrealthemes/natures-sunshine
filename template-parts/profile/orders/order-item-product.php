<?php 
$item = $args['item'];
$pv = ( $args['pv'] ) ? $args['pv'] : 0;
$product_id = $item->get_product_id();
$product = $item->get_product();
$img_url = get_the_post_thumbnail_url( $product_id, 'thumbnail' );

if ( ! $product ) {
    return false;
}

if ( !$img_url ) {
    $img_url = wc_placeholder_img_src();
}

if ( $product->get_sale_price() ) {
    $price = $product->get_sale_price();
} else {
    $price = $product->get_regular_price();
}
?>

<li class="order-products__list-item order-product">
    <a href="<?php echo get_permalink( $product->get_id() ); ?>" class="order-product__info">
        <div class="order-product__info-image">
            <img src="<?php echo $img_url; ?>" 
                 loading="lazy" 
                 decoding="async" 
                 alt="<?php echo $item->get_name(); ?>">
        </div>
        <p class="order-product__info-title text bold"><?php echo $item->get_name(); ?></p>
    </a>
    <div class="order-product__details">
        <div class="order-product__details-item flex flex-column flex-center">
            <span class="order-info__item-title text text--small color-mono-64"><?php _e('Quantity', 'natures-sunshine'); ?></span>
            <span class="order-info__item-text"><?php echo $item->get_quantity(); ?></span>
        </div>
        <div class="order-product__details-item flex flex-column flex-center">
            <span class="order-info__item-title text text--small color-mono-64"><?php _e('Discount', 'natures-sunshine'); ?></span>
            <span class="order-info__item-text">
                <?php echo wc_price( $pv ); ?>
            </span>
        </div>
        <div class="order-product__details-item flex flex-column flex-center">
            <span class="order-info__item-title text text--small color-mono-64"><?php _e('PV', 'natures-sunshine'); ?></span>
            <span class="order-info__item-text">
                <?php echo $pv . ' ' . PV; ?>
            </span>
        </div>
        <div class="order-product__details-item flex flex-column flex-end">
            <span class="order-info__item-title text text--small color-mono-64"><?php _e('Price', 'natures-sunshine'); ?></span>
            <span class="order-info__item-text">
                <?php echo strip_tags( wc_price( $price ) ); ?>
            </span>
        </div>
    </div>
</li>