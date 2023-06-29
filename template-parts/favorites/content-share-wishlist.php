<?php 
$wishlist = $args['wishlist'];
$class = ( $wishlist && $wishlist->has_items() ) ? 'favorites-list--partner' : 'favorites-list--empty';
?>

<div class="favorites-block" data-wishlist-id="<?php echo esc_attr( $wishlist->get_id() ); ?>">
    <div class="ut-loader"></div>
    <div class="favorites-list <?php echo $class; ?>">
        <div class="favorites-header collapse-link <?php echo $args['class_active']; ?>">
            <h2 class="favorites-title">
                <?php echo esc_html( $wishlist->get_formatted_name() ); ?>
            </h2>
            <span class="btn btn-square collapse-icon">
                <svg width="24" height="24">
                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
                </svg>
            </span>

            <?php 
            if ( $wishlist && $wishlist->has_items() ) : 
                $wishlist_items = $wishlist->get_items( 0, 0 );
                $total_txt = ut_num_decline( count( $wishlist_items ), [ 'товар','товара','товаров' ] );
            ?>
                <p class="favorites-header__text">
                    <?php echo __( 'Listed now', 'natures-sunshine' ) . ' ' . $total_txt; ?>
                </p>
            <?php endif; ?>

        </div>
        <div class="favorites-collapse collapse-panel <?php echo $args['class_active']; ?>">
            <div class="favorites-panel">

                <?php if ( $wishlist && $wishlist->has_items() ) : ?>
                    
                    <div class="favorites-slider cards swiper">
                        <div class="swiper-wrapper">

                            <?php
                            $total_price = 0;
                            foreach ( $wishlist_items as $item ) :
                                /**
                                 * Each of the wishlist items
                                 *
                                 * @var $item \YITH_WCWL_Wishlist_Item
                                 */
                                global $product;
                                $product = $item->get_product();

                                if ( $product && $product->exists() ) :
                                    $GLOBALS['product'] = $product; 
                                    $total_price += $product->get_price();
                                    get_template_part( 'woocommerce/content', 'product-wishlist', [] );
                                endif;

                            endforeach;
                            ?>
                            
                        </div>
                        <div class="swiper-button-prev cards__prev"></div>
                        <div class="swiper-button-next cards__next"></div>
                    </div>

                <?php else : ?>

                    <div class="favorites-panel__body">
                        <h2 class="favorites-subtitle color-mono-64">
                            <?php _e( 'There is nothing here yet', 'natures-sunshine' ); ?>
                        </h2>
                        <a href="<?php echo ut_get_permalik_by_template('template-catalog.php'); ?>" class="btn btn-green">
                            <?php _e( 'To catalog', 'natures-sunshine' ); ?>
                        </a>
                    </div>

                <?php endif; ?>

            </div>
        </div>

        <?php if ( $wishlist && $wishlist->has_items() ) : ?>

            <div class="favorites-total">

                <a href="#" class="favorites-total__button btn btn-green buy_all">
                    <?php _e( 'Buy all', 'natures-sunshine' ); ?>
                </a> 

                <span class="favorites-total__price">
                    <span class="favorites-total__count color-mono-64">
                        <?php echo $total_txt; ?>
                        <?php _e('for the amount', 'natures-sunshine'); ?> 
                    </span>
                    <?php echo wc_price( $total_price ); ?>
                </span>

            </div>

        <?php endif; ?>

    </div>
</div>