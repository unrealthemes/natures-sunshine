<?php 
$user_id = get_current_user_id();
$register_id = get_user_meta( $user_id, 'register_id', true );
$partner_ids = WC()->session->get('partner_ids');
?>

<?php if ( $partner_ids ) : ?>

    <div class="popup popup--small" id="select_cart">
        <div class="ut-loader"></div>
        <div class="popup__header">
            <h2 class="popup__title"><?php _e( 'Select cart', 'natures-sunshine' ); ?></h2>
            <button class="popup__close js-close-popup">
                <svg width="24" height="24">
                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
                </svg>
            </button>
        </div>
        <label for="carts" hidden><?php _e('Carts', 'natures-sunshine'); ?></label>
        <div class="form__input select">
            <input type="hidden" name="type" value="">
            <input type="hidden" name="product_id" value="">
            <input type="hidden" name="quantity" value="">
            <input type="hidden" name="variation_id" value="">
            <select name="carts" id="carts">

                <!-- <option value="0">
                    <?php _e('Select cart', 'natures-sunshine'); ?>
                </option> -->
                <option value="0">
                    <?php _e('Your cart', 'natures-sunshine'); echo $register_id ? ' - ' . $register_id : ''; ?>
                </option>

                <?php foreach ( (array)$partner_ids as $partner_id => $full_name ) : ?>

                    <option value="<?php echo $partner_id; ?>">
                        <?php 
                        if ( $full_name ) :
                            echo sprintf(
                                '%1s - %2s',
                                $full_name,
                                $partner_id
                            );
                        else :
                            echo __('Cart', 'natures-sunshine') . ' - ' . $partner_id;
                        endif;
                        ?>
                    </option>

                <?php endforeach; ?>
                
            </select>
        </div>
    </div>

<?php endif; ?>