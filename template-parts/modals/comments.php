<?php 
global $product;

$text = get_field('txt_bottom_comments', 'option');
$img_url = get_the_post_thumbnail_url( $product->get_id(), 'thumbnail' );

if ( !$img_url ) {
    $img_url = wc_placeholder_img_src();
}

if ( is_user_logged_in() ) {
    $class_show = 'hide';
    $user_id = get_current_user_id();
    $user_info = get_userdata( $user_id );
    $first_name = $user_info->first_name;
    $last_name = $user_info->last_name;
    $user_email = $user_info->user_email;
} else {
    $class_show = '';
    $user_id = 0;
    $first_name = '';
    $last_name = '';
    $user_email = '';
}
?>

<div class="popup" id="comment-form">
    <div class="popup__header">
        <h2 class="popup__title"><?php _e('My review', 'natures-sunshine'); ?></h2>
        <button class="popup__close js-close-popup">
            <svg width="24" height="24">
                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
            </svg>
        </button>
    </div>
    <div class="popup__body">
        <div class="product-preview">
            <div class="product-preview__image">
                <img src="<?php echo $img_url; ?>" loading="lazy" decoding="async" alt="">
            </div>
            <div class="product-preview__info">
                <p class="product-preview__title text"><?php echo $product->get_name(); ?></p>

                <?php if ( $product->get_sale_price() ) : ?>
                    <span class="product-preview__price card__price-current">
                        <?php echo strip_tags( wc_price( $product->get_sale_price() ) ); ?>
                    </span>
                    <span class="product-preview__old card__price-old">
                        <?php echo strip_tags( wc_price( $product->get_regular_price() ) ); ?>
                    </span>
                <?php else : ?>
                    <span class="product-preview__price card__price-current">
                        <?php echo strip_tags( wc_price( $product->get_regular_price() ) ); ?>
                    </span>
                <?php endif; ?>

            </div>
        </div>
        <form class="form popup__form form-comment" action="" method="POST">
            <div class="ut-loader"></div>
            <input type="hidden" name="comment_id" value="">
            <input type="hidden" name="product_id" value="<?php echo $product->get_id(); ?>">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <div class="popup__block <?php echo $class_show; ?>">
                <div class="popup__row form__row">
                    <label for="comment_name"><?php _e('Name', 'natures-sunshine'); ?></label>
                    <div class="form__input">
                        <input type="text" 
                               name="comment_name" 
                               id="comment_name" 
                               value="<?php echo $first_name . ' ' . $last_name; ?>"
                               placeholder="<?php _e('Example, Iljya', 'natures-sunshine'); ?>" 
                               required>
                        <div class="form__input-require">
                            <svg width="24" height="24" class="eye">
                                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#star-req'; ?>"></use>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="popup__row form__row">
                    <label for="comment_email"><?php _e('Email', 'natures-sunshine'); ?></label>
                    <div class="form__input">
                        <input type="email" 
                               name="comment_email" 
                               id="comment_email" 
                               value="<?php echo $user_email; ?>"
                               placeholder="<?php _e('example@domain.com', 'natures-sunshine'); ?>" 
                               require>
                        <div class="form__input-require">
                            <svg width="24" height="24" class="eye">
                                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#star-req'; ?>"></use>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="popup__row form__row">
                <label for="comment_message"><?php _e('Your feedback', 'natures-sunshine'); ?></label>
                <div class="form__input">
                    <textarea name="comment_message" id="comment_message" rows="5" placeholder="<?php _e('Your feedback...', 'natures-sunshine'); ?>"></textarea>
                </div>
            </div>

            <div class="popup__row form__row" style="margin-bottom: 0px;">
                <div class="filter__input">
                    <input type="checkbox" name="want_answer" id="want_answer">
                    <label for="want_answer">
                        <?php _e('I want a response to a comment', 'natures-sunshine'); ?>
                    </label>
                </div>
            </div>

            <button type="submit" class="popup__submit btn btn-green"><?php _e('Post a review', 'natures-sunshine'); ?></button>

            <?php if ( $text ) : ?>
                <div class="form__notice text">
                    <?php echo $text; ?>
                </div>
            <?php endif; ?>

            <div class="form__notice text response"></div>
        </form>
    </div>
</div>