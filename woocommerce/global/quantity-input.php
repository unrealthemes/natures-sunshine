<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

$class = ( $args['location'] == 'mini_cart' ) ? 'quantity' : '';
$btn_class = ($args['location'] == 'mini_cart') ? 'counter__btn_mini' : '';
?>

    <div class="card__controls-counter counter <?php echo $class; ?>">

        <button type="button" class="counter__btn counter-minus <?php echo $btn_class . ($input_value == 1 ? ' disabled' : ''); ?>">
            <svg width="24" height="24">
                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#minus'; ?>"></use>
            </svg>
        </button>

        <?php do_action( 'woocommerce_before_quantity_input_field' ); ?>
        
        <input class="qty" 
               id="<?php echo esc_attr( $input_id ); ?>" 
               name="qty" 
               type="text" 
               step="<?php echo esc_attr( $step ); ?>"
               min="1"
               max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
               name="<?php echo esc_attr( $input_name ); ?>"
               value="<?php echo esc_attr( $input_value ); ?>"
               pattern="[0-9.]+"
               title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ); ?>"
			   size="4"
               readonly
			   placeholder="<?php echo esc_attr( $placeholder ); ?>"
			   inputmode="<?php echo esc_attr( $inputmode ); ?>">

        <?php do_action( 'woocommerce_after_quantity_input_field' ); ?>

        <button type="button" class="counter__btn counter-plus <?php echo $btn_class; ?>">
            <svg width="24" height="24">
                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#plus'; ?>"></use>
            </svg>
        </button>

    </div>

<?php