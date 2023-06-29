
<?php // if ( is_user_logged_in() ) : ?>

    <li class="header__controls-item">
        <a href="<?php echo ut_get_permalik_by_template('template-favorites.php'); ?>" class="header__controls-link">
            <div class="header__controls-icon">
                <svg width="24" height="24" class="icon-empty">
                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#heart'; ?>"></use>
                </svg>
            </div>
            <span class="header__controls-text">
                <?php _e('Favorites', 'natures-sunshine'); ?>
            </span>
        </a>
    </li>

<?php // endif; ?>