<?php 
$order = $args['order'];
$user = $order->get_user();
$register_id = get_post_meta( $order->get_id(), '_register_id', true );
$middle_name = get_post_meta( $order->get_id(), '_patronymic', true );
$partner_ids = get_post_meta( $order->get_id(), '_partner_ids', true );
// $partner_ids_str = get_post_meta( $order->get_id(), '_partner_ids', true );
// $partner_ids = ( $partner_ids_str ) ? unserialize($partner_ids_str) : null;
$cart_type = get_post_meta( $order->get_id(), '_cart_type', true );
$delivery_date = get_post_meta( $order->get_id(), '_delivery_date', true );
$delivery_time = get_post_meta( $order->get_id(), '_delivery_time', true );
$cart_type_txt = ( $cart_type ) ? __('Joint', 'natures-sunshine') : __('Personal', 'natures-sunshine');
$wp_is_mobile = get_post_meta( $order->get_id(), '_wp_is_mobile', true );
$type_mobile_txt = ( $wp_is_mobile ) ? __('Via device', 'natures-sunshine') : __('Via website', 'natures-sunshine');
$total_pv = 0;
$my_total_pv = 0; 
$notes = wc_get_order_notes( 
    [
        'order_id' => $order->get_id(),
    ] 
);
$order_statuses = wc_get_order_statuses();

if ( $order->get_status() == 'completed' ) {
    $order_class = 'color-green';
    $line_class = 'orders-result__info--success';
} else if ( 
    $order->get_status() == 'pending' || 
    $order->get_status() == 'processing' || 
    $order->get_status() == 'confirm' || 
    $order->get_status() == 'on-hold' 
) {
    $order_class = 'color-warning';
    $line_class = 'orders-result__info--proccess';
} else if ( 
    $order->get_status() == 'cancelled' || 
    $order->get_status() == 'refunded' 
) {
    $order_class = 'color-alert';
    $line_class = 'orders-result__info--cancel';
}
?>

<li class="orders-results__list-item orders-result">
    <div class="ut-loader"></div>
    <div class="orders-result__info <?php echo esc_attr($line_class); ?>">
        <div class="orders-result__info-head collapse-link flex-start">
            <div class="flex flex-column flex-start">
                <span class="text text--small color-mono-64">
                    <?php
                        echo sprintf(
                            __('Order #%s', 'natures-sunshine'),
                            $order->get_id()
                        );
                    ?>
                </span>
                
                <span class="text <?php echo esc_attr($order_class); ?>">
                    <?php echo $order_statuses[ 'wc-' . $order->get_status() ]; ?>
                </span>

            </div>
            <svg class="collapse-icon" width="24" height="24">
                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
            </svg>
        </div>
        <div class="orders-result__info-body collapse-panel">
            <div class="order">

                <div class="order__row">
                    <div class="order__row-title collapse-link active">
                        <?php _e('Recipient data', 'natures-sunshine'); ?>
                        <svg class="collapse-icon" width="24" height="24">
                            <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
                        </svg>
                    </div>
                    <div class="order__row-content collapse-panel active">
                        <ul class="order-info">
                            <li class="order-info__item flex flex-column">
                                <span class="order-info__item-title text text--small color-mono-64"><?php _e('Sunshine ID', 'natures-sunshine'); ?></span>
                                <span class="order-info__item-text">
                                    <?php echo $register_id; ?>
                                </span>
                            </li>
                            <li class="order-info__item flex flex-column">
                                <span class="order-info__item-title text text--small color-mono-64"><?php _e('Full name', 'natures-sunshine'); ?></span>
                                <span class="order-info__item-text">
                                    <?php 
                                    echo sprintf( 
                                        '%1s %2s %3s',
                                        $order->get_billing_last_name(),
                                        $order->get_billing_first_name(),
                                        $middle_name
                                    ); 
                                    ?>
                                </span>
                            </li>
                            <li class="order-info__item flex flex-column">
                                <span class="order-info__item-title text text--small color-mono-64"><?php _e('Phone', 'natures-sunshine'); ?></span>
                                <span class="order-info__item-text"><?php echo $order->get_billing_phone(); ?></span>
                            </li>
                            <li class="order-info__item flex flex-column">
                                <span class="order-info__item-title text text--small color-mono-64"><?php _e('Email', 'natures-sunshine'); ?></span>
                                <span class="order-info__item-text"><?php echo $order->get_billing_email(); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="order__row">
                    <div class="order__row-title collapse-link active">
                        <?php _e('Delivery', 'natures-sunshine'); ?>
                        <svg class="collapse-icon" width="24" height="24">
                            <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
                        </svg>
                    </div>
                    <div class="order__row-content collapse-panel active">
                        <ul class="order-info">
                            <li class="order-info__item flex flex-column">
                                <span class="order-info__item-title text text--small color-mono-64"><?php _e('Type of delivery', 'natures-sunshine'); ?></span>
                                <span class="order-info__item-text">
                                    <?php echo $order->get_shipping_method(); ?>
                                </span>
                            </li>
                            <li class="order-info__item flex flex-column">
                                <span class="order-info__item-title text text--small color-mono-64"><?php _e('Delivery address', 'natures-sunshine'); ?></span>
                                <span class="order-info__item-text">
                                    <?php 
                                    if ( $order->has_shipping_method('free_shipping') ) :
                                        $billing_address_3 = get_post_meta( $order->get_id(), '_billing_address_3', true );
                                        $billing_address_3 = ( $billing_address_3 ) ? 'кв. ' . $billing_address_3 :'' ;
                                        echo sprintf(
                                            '%1s %2s %3s',
                                            $order->get_billing_address_1(),
                                            $order->get_billing_address_2(),
                                            $billing_address_3
                                        );
                                    else :
                                        echo get_post_meta( $order->get_id(), '_warehouse', true );
                                    endif;
                                    ?>
                                </span>
                            </li>

                            <?php 
                            if ( $delivery_date ) : 
                                $date_f = date_i18n("j F", strtotime($delivery_date));
                            ?>
                                <li class="order-info__item flex flex-column">
                                    <span class="order-info__item-title text text--small color-mono-64"><?php _e('Delivery date', 'natures-sunshine'); ?></span>
                                    <span class="order-info__item-text">
                                        <?php 
                                        esc_html_e($date_f); 

                                        if ( $delivery_time ) :
                                            echo '<br>';
                                            esc_html_e($delivery_time); 
                                        endif;
                                        ?>
                                    </span>
                                </li>
                            <?php endif; ?>
                            
                            <li class="order-info__item flex flex-column">
                                <span class="order-info__item-title text text--small color-mono-64"><?php _e('Amount', 'natures-sunshine'); ?></span>
                                <span class="order-info__item-text">
                                    <?php echo wc_price( $order->get_shipping_total() ); ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="order__row">
                    <div class="order__row-title collapse-link active">
                        <?php _e('Information on order', 'natures-sunshine'); ?>
                        <svg class="collapse-icon" width="24" height="24">
                            <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
                        </svg>
                    </div>
                    <div class="order__row-content collapse-panel active">
                        <ul class="order-info">
                            <li class="order-info__item flex flex-column">
                                <span class="order-info__item-title text text--small color-mono-64"><?php _e('Order processing', 'natures-sunshine'); ?></span>
                                <span class="order-info__item-text">
                                    <?php echo $type_mobile_txt; ?>
                                </span>
                            </li>
                            <li class="order-info__item flex flex-column">
                                <span class="order-info__item-title text text--small color-mono-64"><?php _e('Order type', 'natures-sunshine'); ?></span>
                                <span class="order-info__item-text">
                                    <?php echo $cart_type_txt; ?>
                                </span>
                            </li>
                            <li class="order-info__item flex flex-column">
                                <span class="order-info__item-title text text--small color-mono-64"><?php _e('Payment method', 'natures-sunshine'); ?></span>
                                <span class="order-info__item-text">
                                    <?php 
                                    echo $order->get_payment_method_title(); 
                                    
                                    if ( $order->get_payment_method() == 'bacs' ) :
                                        do_action('woocommerce_thankyou_bacs', $order->get_id()); 
                                    endif;
                                    ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="order__row">
                    <div class="order__row-title collapse-link active">
                    <?php _e('Processing history', 'natures-sunshine'); ?>
                        <svg class="collapse-icon" width="24" height="24">
                            <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
                        </svg>
                    </div>
                    <div class="order__row-content collapse-panel active">

                        <?php if ( $notes ) : ?>
                            
                            <ul class="order-info order-info--column">

                                <?php foreach ( $notes as $note ) : ?>

                                    <li class="order-info__item flex flex-column">
                                        <span class="order-info__item-title text text--small color-mono-64">
                                            <?php echo wpautop( wptexturize( wp_kses_post( $note->content ) ) ); // @codingStandardsIgnoreLine ?>
                                        </span>
                                        <span class="order-info__item-text">
                                            <?php echo $note->date_created->date_i18n( 'd F H:i' ); ?>
                                        </span>
                                    </li>
                                
                                <?php endforeach; ?>

                            </ul>

                        <?php endif; ?>

                    </div>
                </div>

                <div class="order__row">
                    <div class="order__row-title collapse-link active">
                        <?php _e('Products', 'natures-sunshine'); ?>
                        <svg class="collapse-icon" width="24" height="24">
                            <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
                        </svg>
                    </div>
                    <div class="order__row-content collapse-panel active">

                        <?php if ( $cart_type ) : ?>

                            <div class="order-products">
                                <p class="order-products__title text bold hidden-mobile">
                                    <?php _e('Your cart', 'natures-sunshine'); ?> – <?php echo $register_id; ?>
                                </p>
                                <ul class="order-products__list">

                                    <?php
                                    $join_products = [];

                                    if ( $partner_ids ) : 
                                        foreach ( (array)$partner_ids as $partner_id => $full_name ) : 
                                            foreach ( $order->get_items() as $item_id => $item ) :
                                                $cart_item_key = $item->get_meta( '_cart_item_key' );
                                                $field_key = '_' . $order->get_id() . '_' . $cart_item_key . '_' . $partner_id;
                                                $product_in_join_cart = get_post_meta( $order->get_id(), $field_key, true );
                                                if ( $product_in_join_cart ) :
                                                    $join_products[] = $item_id;
                                                endif;
                                            endforeach;
                                        endforeach;
                                    endif;  
                                    
                                    foreach ( $order->get_items() as $item_id => $item ) :  
                                        $cart_item_key = $item->get_meta( '_cart_item_key' );
                                        $field_key = '_my_product_' . $cart_item_key . '_' . $item['product_id'];
                                        if ( ! in_array($item_id, $join_products) ) :
                                            $pv = (int)get_post_meta( $item->get_product_id(), '_pv', true );
                                            $my_total_pv += $pv * (int)$item->get_quantity();
                                            get_template_part( 
                                                'template-parts/profile/orders/order', 
                                                'item-product', 
                                                [
                                                    'item' => $item,
                                                    'pv' => $pv,
                                                ] 
                                            );
                                        endif;
                                    endforeach;
                                    ?>

                                </ul>
                            </div>

                            <?php if ( $partner_ids ) : ?>
                                <?php foreach ( (array)$partner_ids as $partner_id => $full_name ) : ?>

                                    <div class="order-products">
                                        <p class="order-products__title text bold hidden-mobile">
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
                                        </p>
                                        <ul class="order-products__list">

                                            <?php
                                            $partner_total_pv = 0;
                                            foreach ( $order->get_items() as $item_id => $item ) :
                                                $cart_item_key = $item->get_meta( '_cart_item_key' );
                                                $field_key = '_' . $order->get_id() . '_' . $cart_item_key . '_' . $partner_id;
                                                $product_in_join_cart = get_post_meta( $order->get_id(), $field_key, true );
                                                if ( $product_in_join_cart ) :
                                                    $pv = (int)get_post_meta( $item->get_product_id(), '_pv', true );
                                                    $partner_total_pv += $pv * (int)$item->get_quantity();
                                                    get_template_part( 
                                                        'template-parts/profile/orders/order', 
                                                        'item-product', 
                                                        [
                                                            'item' => $item,
                                                            'pv' => $pv,
                                                        ] 
                                                    );
                                                endif;
                                            endforeach;
                                            $total_pv += $partner_total_pv;
                                            ?>

                                        </ul>
                                    </div>

                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                        <?php else : ?>

                            <div class="order-products">
                                <p class="order-products__title text bold hidden-mobile">
                                    <?php _e('Your cart', 'natures-sunshine'); ?> – <?php echo $register_id; ?>
                                </p>
                                <ul class="order-products__list">

                                    <?php
                                    foreach ( $order->get_items() as $item_id => $item ) :
                                        $pv = (int)get_post_meta( $item->get_product_id(), '_pv', true );
                                        $my_total_pv += $pv * (int)$item->get_quantity();
                                        get_template_part( 
                                            'template-parts/profile/orders/order', 
                                            'item-product', 
                                            [
                                                'item' => $item,
                                                'pv' => $pv,
                                            ] 
                                        );
                                    endforeach;
                                    ?>

                                </ul>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php $total_pv = $total_pv + $my_total_pv; ?>

    <div class="orders-result__total orders-total flex">
        <button type="button" class="orders-total__button btn btn-secondary repeat-order-js" data-id="<?php echo esc_attr($order->get_id()); ?>">
            <?php _e('Will repeat the order', 'natures-sunshine'); ?>
        </button>
        <div class="orders-total__info">
            <div class="orders-total__info-item flex flex-column">
                <span class="hidden-mobile color-moonstone"><?php _e('Total points', 'natures-sunshine'); ?></span>
                <span class="hidden-desktop color-moonstone"><?php _e('Amount of PV', 'natures-sunshine'); ?></span>
                <span class="color-green bold"><?php echo $total_pv . ' ' . PV; ?></span>
            </div>
            <div class="orders-total__info-item flex flex-column flex-end">
                <span class="color-mono-64"><?php _e('Total', 'natures-sunshine'); ?></span>
                <span class="color-mono bold">
                    <?php echo wc_price( $order->get_total() ); ?>
                </span>
            </div>
        </div>
    </div>
</li>