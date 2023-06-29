<?php 
$product_id = $args['product_id'];
$quantity = (isset($args['quantity'])) ? $args['quantity'] : 1;

$type = get_field('application', $product_id);
$qty = get_field('qty_type', $product_id) * $quantity;
$portions = get_field('porcii_type', $product_id) * $quantity;
$days = get_field('days_type', $product_id) * $quantity;

$days_list = [ 
    __('day 1', 'natures-sunshine'),
    __('day 2', 'natures-sunshine'),
    __('day 3', 'natures-sunshine')
];
$capsules_list = [ 
    __('capsule 1', 'natures-sunshine'),
    __('capsule 2', 'natures-sunshine'),
    __('capsule 3', 'natures-sunshine')
];
$drops_list = [ 
    __('drop 1', 'natures-sunshine'),
    __('drop 2', 'natures-sunshine'),
    __('drop 3', 'natures-sunshine')
];
$sticks_list = [ 
    __('sticks 1', 'natures-sunshine'),
    __('sticks 2', 'natures-sunshine'),
    __('sticks 3', 'natures-sunshine')
];
$portions_list = [ 
    __('portion 1', 'natures-sunshine'),
    __('portion 2', 'natures-sunshine'),
    __('portion 3', 'natures-sunshine')
];
$tablets_list = [ 
    __('tablet 1', 'natures-sunshine'),
    __('tablet 2', 'natures-sunshine'),
    __('tablet 3', 'natures-sunshine')
];
$days_txt = ut_num_decline( $days, $days_list );

if ( $type == 'capsules' ) : 
    $capsules_txt = ut_num_decline( $qty, $capsules_list );
    $portions_txt = ut_num_decline( $portions, $portions_list );
?>
    <span class="cart-product__info-desc">
        <?php 
            echo sprintf(
                '%1s <span class="product-info__text"> - %2s %3s %4s </span>',
                $capsules_txt,
                $portions_txt,
                __('on','natures-sunshine'),
                $days_txt
            ); 
        ?>
    </span>

<?php 
elseif ( $type == 'tablets' ) : 
    $tablets_txt = ut_num_decline( $qty, $tablets_list );
    $portions_txt = ut_num_decline( $portions, $portions_list );
?>

    <span class="cart-product__info-desc">
        <?php 
            echo sprintf(
                '%1s <span class="product-info__text"> - %2s %3s %4s </span>',
                $tablets_txt,
                $portions_txt,
                __('on','natures-sunshine'),
                $days_txt
            ); 
        ?>
    </span>

<?php 
elseif ( $type == 'powders' ) : 
    $portions_txt = ut_num_decline( $portions, $portions_list );
?>

    <span class="cart-product__info-desc">
        <?php 
            echo sprintf(
                '%1s %2s <span class="product-info__text"> - %3s %4s %5s </span>',
                $qty,
                __('gr','natures-sunshine'),
                $portions_txt,
                __('on','natures-sunshine'),
                $days_txt
            ); 
        ?>
    </span>

<?php 
elseif ( $type == 'emulsions' ) : 
    $drops_txt = ut_num_decline( $qty, $drops_list );
    $portions_txt = ut_num_decline( $portions, $portions_list );
?>

    <span class="cart-product__info-desc">
        <?php 
            echo sprintf(
                '%1s <span class="product-info__text"> - %2s %3s %4s </span>',
                $drops_txt,
                $portions_txt,
                __('on','natures-sunshine'),
                $days_txt
            ); 
        ?>
    </span>

<?php 
elseif ( $type == 'sticks' ) : 
    $sticks_txt = ut_num_decline( $qty, $sticks_list );
    $portions_txt = ut_num_decline( $portions, $portions_list );
?>

    <span class="cart-product__info-desc">
        <?php 
            echo sprintf(
                '%1s <span class="product-info__text"> - %2s %3s %4s </span>',
                $sticks_txt,
                $portions_txt,
                __('on','natures-sunshine'),
                $days_txt
            ); 
        ?>
    </span>

<?php endif; ?>