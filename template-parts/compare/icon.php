<?php 
$product_id = $args['product_id'];
$product = wc_get_product($product_id);
$product_ids = $args['products_list'] ?? [];
$category_ids = $product->get_category_ids();
// 26, 118 - Bady
if ( in_array( 26, $category_ids ) || in_array( 118, $category_ids ) ) :
?>

    <?php if ( in_array( $product_id, $product_ids ) ) : ?>

        <a class="card__controls-btn btn add_product_to_compare added"
           href="<?php echo esc_url(ut_get_permalik_by_template('template-compare.php')); ?>"
           style="color: #00a88f;"
           type="button"
           disabled
           data-product-id="<?php echo esc_attr( $product_id ); ?>">
            <!-- <div class="ut-loader"></div> -->
            <svg width="24" height="24">
                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chart'; ?>"></use>
            </svg>
        </a>

    <?php else : ?>

        <button class="card__controls-btn btn add_product_to_compare"
                type="button"
                data-product-id="<?php echo esc_attr( $product_id ); ?>">
            <!-- <div class="ut-loader"></div> -->
            <svg width="24" height="24">
                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chart'; ?>"></use>
            </svg>
        </button>

    <?php endif; ?>

<?php endif; ?>