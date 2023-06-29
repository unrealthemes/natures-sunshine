<?php 
$type_cart = WC()->session->get('joint_order_mode');
$type_view = get_field('type_view_product_price', 'options');
?>

<div class="cart-products__list">
    <div class="ut-loader"></div>
    <h2 class="cart-products__title text bold"><?php _e('Product list', 'natures-sunshine'); ?></h2>
    <ul class="cart-products__list-items">

        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

        <?php
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
 
            if ( $type_cart && isset($cart_item['partner_id']) ) {
                continue;
            }

            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
                $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                $img_url = get_the_post_thumbnail_url( $_product->get_id(), 'thumbnail' );
                $pv = get_post_meta( $cart_item['product_id'], '_pv', true );

                if ( !$img_url ) {
                    $img_url = wc_placeholder_img_src();
                }
                ?>

                <li class="cart-products__list-item cart-product" 
                    data-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                    <div class="ut-loader"></div>
                    <div class="cart-product__info">
                        <div class="cart-product__info-image">
                            <img class="card__image-default" 
                                    src="<?php echo $img_url; ?>" 
                                    loading="lazy" 
                                    decoding="async" 
                                    alt="<?php echo $product_name; ?>">
                        </div>
                        <div class="cart-product__info-details">
                            <div class="cart-product__info-top">
                                <span class="cart-product__info-sku">
                                    <?php echo _e('SKU', 'natures-sunshine') . ' ' . $_product->get_sku(); ?>
                                </span>

                                <?php if ( is_user_logged_in() || $type_view != 1 && $type_view != 3 && $_product->get_sale_price() ) : ?>
                                    <span class="cart-product__info-old card__price-old">
                                        <?php echo strip_tags( wc_price( $_product->get_regular_price() ) ); ?>
                                    </span>
                                <?php endif; ?>

                            </div>
                            <div class="cart-product__info-title">

                                <h2 class="card__title">
                                    <a href="<?php echo esc_url( $product_permalink ); ?>" class="card__title-link" title="<?php echo $product_name; ?>">
                                        <?php echo $product_name; ?>
                                    </a>
                                </h2>

                                <?php if ( $type_view == 3 && ! is_user_logged_in() ) : ?>

                                <?php elseif ( $type_view == 1 && ! is_user_logged_in() ) : ?>

                                    <span class="cart-product__info-price text">
                                        <?php echo strip_tags( wc_price( $_product->get_regular_price() ) ); ?>
                                    </span>

                                <?php else : ?>

                                    <?php if ( $_product->get_sale_price() ) : ?>
                                        <span class="cart-product__info-price text">
                                            <?php echo strip_tags( wc_price( $_product->get_sale_price() ) ); ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="cart-product__info-price text">
                                            <?php echo strip_tags( wc_price( $_product->get_regular_price() ) ); ?>
                                        </span>
                                    <?php endif; ?>

                                <?php endif; ?>

                            </div>
                            <div class="cart-product__info-bottom">
                                <?php get_template_part('woocommerce/cart/cart', 'dose', ['product_id' => $product_id, 'quantity' => $cart_item['quantity']]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="cart-product__controls">
                        
                        <?php 
                        woocommerce_quantity_input( 
                            [ 
                                'location' => 'mini_cart',
                                'input_value' => $cart_item['quantity'],
                            ], 
                            $_product, 
                            true 
                        ); 
                        ?>

                        <?php if ( $pv ) : ?>
                            <div class="cart-product__controls-points card__price-points">
                                <img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="">
                                <?php echo $pv . ' ' . PV; ?>
                            </div>
                        <?php endif; ?>

                        <?php 
                        get_template_part(
                            'template-parts/favorites/icon', 
                            'postpone', 
                            [
                                'product_id' => $product_id,
                                'is_product_in_wishlist' => ut_help()->wishlist->is_product_in_wishlist( $product_id ),
                            ]
                        ); 
                        ?>

                        <?php
                        echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                                '<a href="%s" class="remove_from_cart_button cart-products__button cart-product__delete btn btn-square" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"><svg width="24" height="24"><use xlink:href="'. DIST_URI . '/images/sprite/svg-sprite.svg#trash"></use></svg></a>',
                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                esc_attr__( 'Remove this item', 'woocommerce' ),
                                esc_attr( $product_id ),
                                esc_attr( $cart_item_key ),
                                esc_attr( $_product->get_sku() )
                            ),
                            $cart_item_key
                        );
                        ?>

                    </div>
                </li>

            <?php
            endif;

        endforeach;
        ?>

        <?php do_action( 'woocommerce_cart_contents' ); ?>

        <?php if ( wc_coupons_enabled() ) { ?>
            <!-- <div class="coupon">
                <label for="coupon_code"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
                <?php do_action( 'woocommerce_cart_coupon' ); ?>
            </div> -->
        <?php } ?>

        <!-- <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button> -->

        <?php do_action( 'woocommerce_cart_actions' ); ?>

        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

        <?php do_action( 'woocommerce_after_cart_contents' ); ?>

    </ul>
</div>