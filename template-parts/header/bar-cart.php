<li class="header__controls-item cart-count">
    <!-- <a href="<?php // echo esc_url( wc_get_cart_url() ); ?>" class="header__controls-link"> -->
    <a href="<?php echo home_url('/cart/'); ?>" class="header__controls-link">
        <div class="header__controls-icon">
            <svg width="24" height="24">
                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#shop-cart'; ?>"></use>
            </svg>
	        <?php if (ut_help()->cart->get_count_cart() > 0) : ?>
	            <span class="header__controls-icon-counter">
	                <?php echo ut_help()->cart->get_count_cart(); ?>
	            </span>
	        <?php endif; ?>
        </div>
        <span class="header__controls-text"><?php _e('Cart', 'natures-sunshine'); ?></span>
    </a>
</li> 