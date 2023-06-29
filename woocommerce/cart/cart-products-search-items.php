<?php 
global $product;

if ( $product->get_image_id() ) { 
    $img_url = wp_get_attachment_url( $product->get_image_id(), 'full' ); 
} else { 
    $img_url = wc_placeholder_img_src(); 
} 
$type_view = get_field('type_view_product_price', 'options');
// outofstock | onbackorder | instock
?>

<li class="cart-products__search-item">

    <a href="<?php echo get_permalink( $product->get_id() ); ?>" class="product-preview__image" title="<?php echo $product->get_name(); ?>">
        <img src="<?php echo $img_url; ?>" 
             loading="lazy" 
             decoding="async" 
             alt="<?php echo $product->get_name(); ?>">
    </a>

    <div class="product-preview__info">

        <?php if ( $product->get_stock_status() == 'instock' ) : ?>
            <span class="product-preview__available product-preview__available--yes">
                <?php __('In stock', 'natures-sunshine'); ?>
            </span>
        <?php elseif ( $product->get_stock_status() == 'outofstock' ) : ?>
            <span class="product-preview__available product-preview__available--no">
                <?php __('Not available', 'natures-sunshine'); ?>
            </span>
        <?php elseif ( $product->get_stock_status() == 'onbackorder' ) : ?>
            <span class="product-preview__available product-preview__available--no">
                <?php __('Pre-order', 'natures-sunshine'); ?>
            </span>
        <?php endif; ?>

        <a href="<?php echo get_permalink( $product->get_id() ); ?>" class="product-preview__title text" title="<?php echo $product->get_name(); ?>">
            <?php echo $product->get_name(); ?>
        </a>

        <?php if ( $type_view == 3 && ! is_user_logged_in() ) : ?>

        <?php elseif ( $type_view == 1 && ! is_user_logged_in() ) : ?>

            <span class="product-preview__price card__price-current">
                <?php echo strip_tags( wc_price( $product->get_regular_price() ) ); ?>
            </span>

        <?php else : ?>

            <span class="product-preview__price card__price-current">
                <?php echo wc_price( $product->get_price() ); ?>
            </span>

        <?php endif; ?>

    </div>
    <div class="product-preview__button">
        
        <?php if ( $product->get_stock_status() == 'instock' ) : ?>
            <button type="button" data-id="<?php echo $product->get_id(); ?>" class="product-preview__action btn btn-secondary add_to_cart_search">
                <?php _e('Add', 'natures-sunshine'); ?>
            </button>
        <?php elseif ( $product->get_stock_status() == 'outofstock' ) : ?>
            <?php 
            get_template_part(
                'template-parts/favorites/icon', 
                'postpone', 
                [
                    'product_id' => $product->get_id(),
                    'is_product_in_wishlist' => ut_help()->wishlist->is_product_in_wishlist( $product->get_id() ),
                ]
            ); 
            ?>
        <?php endif; ?>

    </div>
</li>