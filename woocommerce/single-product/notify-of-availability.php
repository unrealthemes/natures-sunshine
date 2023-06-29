<?php 
global $product;
?>

<?php if ( $product->get_stock_status() == 'outofstock' && is_user_logged_in() ) : ?>

    <form id="back_in_stock_notifier" class="form product-info__buy product-info__note card__controls" action="" method="post">
        <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
        <div class="ut-loader"></div>
        <div class="product-info__input form__input">
            <input type="email" name="note_email" id="note_email" placeholder="Example@domain.com" required>
        </div>
        <button class="card__controls-buy card__controls-note btn btn-secondary" type="submit">
            <?php _e('Notify me when available', 'natures-sunshine'); ?>
        </button>

        <?php
        get_template_part(
            'template-parts/favorites/icon',
            null, [
                'product_id' => $product->get_id(),
                'is_product_in_wishlist' => ut_help()->wishlist->is_product_in_wishlist( $product->get_id() ),
            ]
        );
        ?>

    </form>

<?php endif; ?>