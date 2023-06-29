<?php 
global $product;

$childrens = $product->get_upsell_ids();
?>

<?php if ( $childrens ) : ?>

    <section class="section products include">
        <div class="container">
            <h2 class="products__title"><?php _e('The complex includes', 'natures-sunshine'); ?></h2>
            <div class="products-slider cards swiper">
                <div class="swiper-wrapper">

                    <?php 
                    foreach ( $childrens as $children_id ) : 
                        $children = wc_get_product( $children_id );
                        $eng_name = get_post_meta( $children->get_id(), '_eng_name', true );
                        $img_url = get_the_post_thumbnail_url( $children->get_id(), 'full' );

                        if ( !$img_url ) {
                            $img_url = wc_placeholder_img_src();
                        }
                    ?>

                        <div class="swiper-slide cards__item">
                            <div class="card included-card">
                                <div class="card__preview">
                                    <div class="card__image">
                                        <img class="card__image-default" 
                                             src="<?php echo $img_url; ?>" 
                                             loading="lazy" 
                                             decoding="async" 
                                             alt="">
                                        <!-- <img class="card__image-hover" src="" loading="lazy" decoding="async" alt=""> -->
                                    </div>
                                </div>
                                <div class="card__info">

                                    <?php if ( $eng_name ) : ?>
                                        <p class="card__type">
                                            <span class="card__type-name">
                                                <?php echo $eng_name; ?>
                                            </span>
                                        </p>
                                    <?php endif; ?>

                                    <h2 class="card__title">
                                        <a href="<?php echo $children->get_permalink(); ?>" class="card__title-link" title="<?php echo $children->get_name(); ?>">
                                            <?php echo $children->get_name(); ?>
                                        </a>
                                    </h2>

                                    <div class="card__price">
                                        <div class="card__price-block">

                                            <?php if ( $children->get_sale_price() ) : ?>
                                                <span class="card__price-current"><?php echo strip_tags( wc_price( $children->get_sale_price() ) ); ?></span>
                                                <span class="card__price-old"><?php echo strip_tags( wc_price( $children->get_regular_price() ) ); ?></span>
                                            <?php else : ?>
                                                <span class="card__price-current"><?php echo strip_tags( wc_price( $children->get_regular_price() ) ); ?></span>
                                            <?php endif; ?>

                                            <span class="card__price-notice"><?php _e('Affiliate price', 'natures-sunshine') ;?></span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>
                <div class="swiper-button-prev cards__prev"></div>
                <div class="swiper-button-next cards__next"></div>
            </div>
        </div>
    </section>

<?php endif; ?>