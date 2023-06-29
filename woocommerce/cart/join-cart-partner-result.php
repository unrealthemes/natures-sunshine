<?php 
$total_products = $args['total_products'];
$total_pv = $args['total_pv'];
$total_price = $args['total_price'];
?>

<div class="join-cart-result">

    <?php 
    if ( $total_pv ) : 
        $total_txt = ut_num_decline( 
            $total_products, 
            [ 
                __('product 1', 'natures-sunshine'),
                __('product 2', 'natures-sunshine'),
                __('product 3', 'natures-sunshine')
            ] 
        ); 
    ?>

        <div class="cart-points">
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
                <span class="hidden-desktop text">
                    <?php 
                        echo sprintf( 
                            __('+%s points per order', 'natures-sunshine'), 
                            $total_pv
                        ); 
                    ?>
                </span>
            </div>
            <!-- <div class="tooltip">
                <svg width="20" height="20">
                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#info-16'; ?>"></use>
                </svg>
                <span class="tooltip__content tooltip__content--bottom">Some content</span>
            </div> -->
        </div>

    <?php endif; ?>

    <div class="cart-results__item">
        <span class="text color-mono-64">
            <?php
                echo sprintf( 
                    __('There are %s in the cart for the amount', 'natures-sunshine'), 
                    $total_txt
                ); 
            ?> 
        </span>
        <span class="text">
            <?php echo wc_price( $total_price ); ?>
        </span>
    </div>

    <!-- <div class="cart-results__item">
        <span class="text color-mono-64">Скидка</span>
        <span class="text color-green">189 ₴</span>
    </div> -->

    <div class="cart-price">
        <span class="text"><?php _e('Subtotal', 'natures-sunshine'); ?></span>
        <span class="cart-price__total">
            <?php echo wc_price( $total_price ); ?>
        </span>
    </div>

</div>