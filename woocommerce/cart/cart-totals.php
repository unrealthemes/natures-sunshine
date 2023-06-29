<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

$type_cart = WC()->session->get('joint_order_mode');
$total_txt = ut_num_decline( 
    WC()->cart->get_cart_contents_count(), 
    [ 
        __('product 1', 'natures-sunshine'),
        __('product 2', 'natures-sunshine'),
        __('product 3', 'natures-sunshine')
    ] 
); 
$total_pv = 0;
$my_total_price = 0;
$my_total_pv = 0;
$page_id = get_option( 'woocommerce_cart_page_id' );
$tooltip_txt = get_field('tooltip_cart', $page_id);

if ( $type_cart ) : 
    $user_id = get_current_user_id();
    $register_id = get_user_meta( $user_id, 'register_id', true );
    $partner_ids = WC()->session->get('partner_ids');
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :         
        if ( isset($cart_item['partner_id']) ) :
            continue;
        endif;
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
            $my_total_price += $_product->get_price() * $cart_item['quantity'];
            $pv = get_post_meta( $cart_item['product_id'], '_pv', true );
            $my_total_pv += $pv * $cart_item['quantity'];
        endif;
    endforeach;
?> 

    <div class="cart-sidebar">
        
        <?php get_template_part( 'woocommerce/cart/cart-switcher', null, ['type_cart' => $type_cart] ); ?>

        <div class="cart-total <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">
            <p class="cart-total__title text active">
                <?php _e('Total', 'natures-sunshine'); ?>
                <svg class="cart-total__title-icon" width="24" height="24">
                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
                </svg>
            </p>
            <div class="cart-collapse active">
                <div class="cart-results">

                    <div class="cart-results__item">
                        <span class="text color-mono-64 bold">
                            <?php _e('Your cart', 'natures-sunshine'); echo $register_id ? ' - ' . $register_id : ''; ?>
                        </span>
                        <span class="text"><?php echo wc_price( $my_total_price ); ?></span>
                    </div>

                    <?php if ( $partner_ids ) : ?>
                        <?php foreach ( (array)$partner_ids as $partner_id => $full_name ) : ?>

                            <?php 
                            $partner_total_price = 0;
                            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                                if ( isset($cart_item['partner_id']) && $cart_item['partner_id'] == $partner_id ) :
                                    $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
                                        $partner_total_price += (int)$_product->get_price() * (int)$cart_item['quantity'];
                                    endif;
                                endif;
                            endforeach;
                            ?>

                            <div class="cart-results__item">
                                <span class="text color-mono-64">
                                    <?php 
                                        if ( $full_name ) :
                                            echo sprintf(
                                                '%1s - %2s',
                                                $full_name,
                                                $partner_id
                                            );
                                        else :
                                            echo $partner_id;
                                        endif;
                                    ?>
                                </span>
                                <span class="text"><?php echo wc_price( $partner_total_price ); ?></span>
                            </div>

                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
            
            <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

            <div class="cart-price">
                <span class="text color-mono-64">
                    <?php _e('In cart now', 'natures-sunshine'); ?> 
                    <?php echo $total_txt; ?>
                </span>
                <span class="cart-price__total">
                    <!-- <span class="card__price-old">1590 ₴</span> -->
                    <?php echo WC()->cart->get_cart_subtotal(); ?>
                </span>
            </div>

            <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

            <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>

            <?php do_action( 'woocommerce_after_cart_totals' ); ?>
            
        </div>
        <div class="cart-total-joint w-100">
            <p class="cart-total__title text active">
                <img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="">
                <?php _e('Total', 'natures-sunshine'); ?>
                <svg class="cart-total__title-icon" width="24" height="24">
                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
                </svg>
            </p>
            <div class="cart-collapse active">
                <div class="cart-results">

                    <div class="cart-results__item">
                        <span class="text">
                            <?php _e('Your cart', 'natures-sunshine'); ?> – <?php echo $register_id; ?>
                        </span>
                        <span class="text"><?php echo $my_total_pv . ' ' . PV; ?></span>
                    </div>

                    <?php if ( $partner_ids ) : ?>
                        <?php foreach ( (array)$partner_ids as $partner_id => $full_name ) : ?>

                            <?php 
                            $partner_total_pv = 0;
                            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                                if ( isset($cart_item['partner_id']) && $cart_item['partner_id'] == $partner_id ) :
                                    $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
                                        $pv = (float)str_replace(",", ".", get_post_meta( $cart_item['product_id'], '_pv', true ) );
                                        $partner_total_pv += $pv * (int)$cart_item['quantity'];
                                    endif;
                                endif;
                            endforeach;
                            $total_pv += $partner_total_pv;
                            ?>

                            <div class="cart-results__item">
                                <span class="text">
                                    <?php 
                                        if ( $full_name ) :
                                            echo sprintf(
                                                '%1s - %2s',
                                                $full_name,
                                                $partner_id
                                            );
                                        else :
                                            echo $partner_id;
                                        endif;
                                    ?>
                                </span>
                                <span class="text"><?php echo $partner_total_pv . ' ' . PV; ?></span>
                            </div>

                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php $total_pv = $total_pv + $my_total_pv; ?>
                    
                </div>
            </div>
            <div class="cart-total-joint__points">
                <span class="text"><?php _e('PV by carts', 'natures-sunshine'); ?></span>
                <span class="cart-total-joint__total bold"><?php echo $total_pv . ' ' . PV; ?></span>
            </div>
        </div>
    </div>

<?php else : ?>

    <div class="cart-sidebar">

        <?php get_template_part( 'woocommerce/cart/cart-switcher', null, ['type_cart' => $type_cart] ); ?>

        <div class="cart-total <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

            <?php do_action( 'woocommerce_before_cart_totals' ); ?>

            <p class="cart-total__title text active">
                <?php _e('Total', 'natures-sunshine'); ?>
                <svg class="cart-total__title-icon" width="24" height="24">
                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
                </svg>
            </p>

            <div class="cart-collapse active">
                <div class="cart-results">

                    <?php
                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
                            $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                            $pv = (float)str_replace(",", ".", get_post_meta( $cart_item['product_id'], '_pv', true ) );
                            $total_pv += $pv * (int)$cart_item['quantity'];
                            ?>

                            <div class="cart-results__item">
                                <span class="text color-mono-64">
                                    <?php echo $cart_item['quantity']; ?> × <?php echo $product_name; ?>
                                </span> 
                                <span class="text">
                                    <?php echo wc_price( $cart_item['line_subtotal'] ); ?>
                                </span>
                            </div>

                        <?php
                        endif;
                        
                    endforeach;
                    ?>

                    <!-- <?php //foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                        <div class="cart-results__item">
                            <span class="text color-mono-64">
                                <?php //wc_cart_totals_coupon_label( $coupon ); ?>
                            </span>
                            <span class="text">
                                <?php //wc_cart_totals_coupon_html( $coupon ); ?>
                            </span>
                        </div>
                    <?php //endforeach; ?> -->

                    <!-- <?php //foreach ( WC()->cart->get_fees() as $fee ) : ?>
                        <div class="cart-results__item">
                            <span class="text color-mono-64">
                                <?php //echo esc_html( $fee->name ); ?>
                            </span>
                            <span class="text">
                                <?php //wc_cart_totals_fee_html( $fee ); ?>
                            </span>
                        </div>
                    <?php //endforeach; ?> -->

                    <!-- <?php
                    // if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
                    //     $taxable_address = WC()->customer->get_taxable_address();
                    //     $estimated_text  = '';

                    //     if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
                    //         $estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
                    //     }

                    //     if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
                    //         foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                                ?>
                                <div class="cart-results__item">
                                    <span class="text color-mono-64">
                                        <?php //echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    </span>
                                    <span class="text">
                                        <?php //echo wp_kses_post( $tax->formatted_amount ); ?>
                                    </span>
                                </div>
                                <?php
                        //     }
                        // } else {
                            ?>
                            <div class="cart-results__item">
                                <span class="text color-mono-64">
                                    <?php //echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </span>
                                <span class="text">
                                    <?php //wc_cart_totals_taxes_total_html(); ?>
                                </span>
                            </div>
                            <?php
                    //     }
                    // }
                    ?> -->

                </div>
            </div>

            <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

            <div class="cart-price">
                <span class="text color-mono-64">
                    <?php _e('In cart now', 'natures-sunshine'); ?> 
                    <?php echo $total_txt; ?>
                </span>
                <span class="cart-price__total">
                    <!-- <span class="card__price-old">1590 ₴</span> -->
                    <?php echo WC()->cart->get_cart_subtotal(); ?>
                </span>
            </div>

            <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

            <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>

            <?php do_action( 'woocommerce_after_cart_totals' ); ?>

        </div>

        <?php if ( $total_pv ) : ?>

            <div class="cart-points form-checkout__points w-100">
                <div class="card__price-points">
                    <img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="">
                    <span class="hidden-mobile">
                        <?php 
                            echo sprintf( 
                                __('Let`s accrue %1s %2s', 'natures-sunshine'), 
                                $total_pv, 
                                PV 
                            ); 
                        ?>
                    </span>
                    <span class="hidden-desktop">
                        <?php 
                            echo sprintf( 
                                __('+%s points per order', 'natures-sunshine'), 
                                $total_pv
                            ); 
                        ?>
                    </span>
                </div>

                <?php if ( $tooltip_txt ) : ?>
                    <div class="tooltip" data-tooltip="<?php echo nl2br($tooltip_txt); ?>">
                        <svg width="20" height="20">
                            <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
                        </svg>
                    </div>
                <?php endif; ?>

            </div>

        <?php endif; ?>

    </div>

<?php endif; ?>