<?php 
global $product;

$type_view = get_field('type_view_product_price', 'options');
$post_thumbnail_id = $product->get_image_id();
$post_thumbnail_src = ( $post_thumbnail_id ) ? wp_get_attachment_url( $post_thumbnail_id, 'thumbnail' ) : wc_placeholder_img_src( 'woocommerce_single' );
?>

<div class="product-panel">
    <div class="container">
        <div class="product-panel__inner">
            <div class="product-panel__inner-item product-item">
                <div class="product-item__image">
                    <img src="<?php echo $post_thumbnail_src; ?>" 
                         loading="eager" 
                         decoding="async" 
                         alt="<?php echo $product->get_name(); ?>">
                </div>
                <div class="product-item__content">
                    <p class="product-item__content-title text bold"><?php echo $product->get_name(); ?></p>
                    <div class="product-item__content-tabs">
                        <a href="#description" class="text text--small color-mono-64 tabs-link active"><?php _e( 'Description', 'woocommerce' ); ?></a>
                        <a href="#composition" class="text text--small color-mono-64 tabs-link"><?php _e( 'Composition', 'natures-sunshine' ); ?></a>
                        <a href="#contraindications" class="text text--small color-mono-64 tabs-link"><?php _e( 'Contraindications', 'natures-sunshine' ); ?></a>
                        <a href="#notes" class="text text--small color-mono-64 tabs-link"><?php _e( 'Notes', 'natures-sunshine' ); ?></a>
                        <a href="#certificates" class="text text--small color-mono-64 tabs-link"><?php _e( 'Certificates', 'natures-sunshine' ); ?></a>
                        <a href="#reviews" class="text text--small color-mono-64 tabs-link"><?php _e( 'Reviews', 'natures-sunshine' ); ?></a>
                    </div>
                </div>
            </div>
            <div class="product-panel__inner-buy product-buy">

                <?php if ( $type_view == 3 && ! is_user_logged_in() ) : ?>

                <?php elseif ( $type_view == 1 && ! is_user_logged_in() ) : ?>

                    <span class="product-buy__price text bold color-green"><?php echo strip_tags( wc_price( $product->get_regular_price() ) ); ?></span>

                <?php else : ?>
                    
                    <?php if ( $product->get_sale_price() ) : ?>
                        <span class="product-buy__price text bold color-green">
	                        <?php echo strip_tags( wc_price( $product->get_sale_price() ) ); ?>
	                        <span class="card__price-old"><?php echo strip_tags( wc_price( $product->get_regular_price() ) ); ?></span>
                        </span>
                    <?php else : ?>
                        <span class="product-buy__price text bold color-green"><?php echo strip_tags( wc_price( $product->get_regular_price() ) ); ?></span>
                    <?php endif; ?>

                <?php endif; ?>

                <?php 
                if ( $type_view == 3 && ! is_user_logged_in() ) :
                    $redirect_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $login_url = ut_get_permalik_by_template('template-login.php') . '?redirect_url=' . $redirect_url; 
                ?>
                    <a class="card__controls-buy btn btn-green" href="<?php echo $login_url; ?>">
                        <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
                    </a>
                <?php else : ?>
                    <button type="button" 
                            name="add-to-cart" 
                            value="<?php echo esc_attr( $product->get_id() ); ?>" 
                            class="card__controls-buy btn btn-green single_add_to_cart_button">
                            <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
                    </button>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>