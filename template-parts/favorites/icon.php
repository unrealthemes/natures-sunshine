<?php 
$product_id = $args['product_id'];
$is_product_in_wishlist = $args['is_product_in_wishlist'];
?>

<?php if ( isset($is_product_in_wishlist['wishlist_id']) && !empty($is_product_in_wishlist['wishlist_id']) ) : ?>

    <button class="card__controls-btn btn header__controls-filled add_product_to_wishlist added" 
            type="button"
            data-wishlist-id="<?php echo esc_attr( $is_product_in_wishlist['wishlist_id'] ); ?>"
            data-product-id="<?php echo esc_attr( $product_id ); ?>">
        <!-- <div class="ut-loader"></div> -->
        <svg width="24" height="24" class="icon-filled">
            <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#heart-filled'; ?>"></use>
        </svg>
    </button>

<?php else : ?>

    <button class="card__controls-btn btn add_product_to_wishlist" 
            type="button"
            data-wishlist-id=""
            data-product-id="<?php echo esc_attr( $product_id ); ?>">
        <!-- <div class="ut-loader"></div> -->
        <svg width="24" height="24">
            <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#heart'; ?>"></use>
        </svg>
    </button>

<?php endif; ?>