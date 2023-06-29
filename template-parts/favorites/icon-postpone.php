<?php 
$product_id = $args['product_id'];
$is_product_in_wishlist = $args['is_product_in_wishlist'];
?>

<?php if ( $is_product_in_wishlist ) : ?>

    <button class="cart-products__button cart-product__book btn btn-white add_product_to_wishlist_postpone added"
            type="button"
            data-wishlist-id="<?php echo esc_attr( $is_product_in_wishlist ); ?>"
            data-product-id="<?php echo esc_attr( $product_id ); ?>">
        <!-- <div class="ut-loader"></div> -->
        <span class="full"><?php _e('Postponed', 'natures-sunshine'); ?></span>
        <span class="short"><?php _e('Postponed', 'natures-sunshine'); ?></span>
    </button>

<?php else : ?>

    <button class="cart-products__button cart-product__book btn btn-white add_product_to_wishlist_postpone"
            type="button"
            data-wishlist-id=""
            data-product-id="<?php echo esc_attr( $product_id ); ?>">
        <!-- <div class="ut-loader"></div> -->
        <span class="full"><?php _e('Postpone for later', 'natures-sunshine'); ?></span>
        <span class="short"><?php _e('Postpone', 'natures-sunshine'); ?></span>
    </button>

<?php endif; ?>