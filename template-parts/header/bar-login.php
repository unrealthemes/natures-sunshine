<?php 
$redirect_url = get_home_url() . '/account';
$login_url = ut_get_permalik_by_template('template-login.php') . '?redirect_url=' . $redirect_url;

if ( ! is_user_logged_in() ) :
?>

    <li class="header__controls-item">
        <a href="<?php echo $login_url; ?>" class="header__controls-link">
            <div class="header__controls-icon">
                <svg width="24" height="24">
                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#person'; ?>"></use>
                </svg>
            </div>
            <span class="header__controls-text">
                <?php _e('Login', 'natures-sunshine'); ?>
            </span>
        </a>
    </li> 

<?php endif; ?>