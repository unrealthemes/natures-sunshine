<?php 
$i = 1;
$languages = apply_filters( 'wpml_active_languages', NULL, array( 'skip_missing' => 0 ) );
?>

<?php if ( ! empty( $languages ) ) : ?>

    <div class="header__langs langs">

        <?php foreach ( $languages as $language ) : ?>

            <?php if ( $i > 1 ) : ?>
                <span class="langs__divider"></span>
            <?php endif; ?>

            <?php if ( $language['active'] ) : ?>

                <span class="langs__item langs__item--current">
                    <?php echo $language['translated_name']; ?>
                </span>

            <?php 
            else : 
                $lang_url = ut_lang_url($language);
            ?>

                <a href="<?php echo esc_url($lang_url); ?>" class="langs__item" data-code="<?php echo $language['code']; ?>">
                    <?php echo $language['translated_name']; ?>
                </a>

            <?php endif; ?>

        <?php $i++; endforeach; ?>

    </div>

<?php endif; ?>