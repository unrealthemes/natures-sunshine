<?php 
// if ( is_user_logged_in() ) : 
    $products = $args['products_list'];
?>

    <li class="header__controls-item compare">
        <a href="<?php echo ut_get_permalik_by_template('template-compare.php'); ?>" class="header__controls-link">
            <div class="header__controls-icon">
                <svg width="24" height="24">
                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chart'; ?>"></use>
                </svg>

                <?php if ( $products ) : ?>
                    <span class="header__controls-icon-counter">
                        <?php echo count( $products ); ?>
                    </span>
                <?php endif; ?>

            </div>
            <span class="header__controls-text">
                <?php _e('Compare', 'natures-sunshine'); ?>
            </span>
        </a>
    </li> 

<?php // endif; ?>