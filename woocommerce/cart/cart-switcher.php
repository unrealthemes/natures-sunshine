<?php 
$active = ( $args['type_cart'] ) ? 'on' : '';

if ( is_user_logged_in() ) :
?>

    <div class="cart-switcher">
        <div class="cart-switcher__mobile">
            <svg class="cart-switcher__mobile-icon" width="24" height="24">
                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#warning'; ?>"></use>
            </svg>
            <p class="cart-switcher__mobile-text">Режим совместного заказа доступен только на десктопной версии</p>
        </div>
        <div class="cart-switcher__switch switcher">
            
            <?php if ( $args['type_cart'] ) : ?>

                <a href="#joint_exit" data-fancybox="" class="switcher__label">
                    <?php _e('Joint order mode', 'natures-sunshine'); ?>
                    <span class="switcher__slider <?php echo $active; ?>"></span>
                </a>

            <?php else : ?>

                <label class="switcher__label" for="joint_order_mode">
                    <?php _e('Joint order mode', 'natures-sunshine'); ?>
                    <span class="switcher__slider <?php echo $active; ?>"></span>
                </label>

            <?php endif; ?>

        </div>
        <div class="cart-switcher__collapse">
            <div class="cart-switcher__notice">
                <?php _e('When exiting the joint order mode, all partner baskets will be deleted except for yours', 'natures-sunshine'); ?>
            </div>
        </div>
    </div>

<?php 
endif;
?>