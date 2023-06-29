<?php

/**
 * health-topics Block Template.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'health-topics-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'themes';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$title = get_field('title_ht');
$terms = get_field('terms_ht');
?>

<?php if ( !empty( $_POST['query']['preview'] ) ) : ?>

    <figure>
        <img src="<?php echo THEME_URI; ?>'/img/gutenberg-preview/health-topics.png'" alt="Preview" style="width:100%;">
    </figure>

<?php else : ?>

    <section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
        <div class="container">

            <?php if ( $title ) : ?>
				<h2 class="themes__title text-center"><?php echo $title; ?></h2>
			<?php endif; ?>

            <?php if ( $terms ) : ?>
                <div class="themes-carousel swiper row-slider">
                    <div class="swiper-wrapper">

                        <?php 
						foreach  ( $terms as $term ) : 
							$icon_id = get_field( 'icon_ht', $term ); 
							$thumb_id = get_field( 'img_ht', $term ); 
							$thumb_url = ( $thumb_id ) ? wp_get_attachment_url( $thumb_id, 'full' ) : wc_placeholder_img_src();
							// $link = get_term_link( (int)$term->term_id, 'health-topics' );
                            $link = home_url('/catalog/ht-in-'. $term->slug .'/pc-in-bady/');
                            $count_product_txt = ut_num_decline( $term->count, ['препарат', 'препарата', 'препаратов'] );
						?>
                            <div class="swiper-slide themes-carousel__slide">
                                <a href="<?php echo $link; ?>" class="themes-carousel__link" title="<?php echo $term->name; ?>">
                                    <img class="themes-carousel__image" 
                                         src="<?php echo $thumb_url; ?>" 
                                         loading="lazy" 
                                         decoding="async" 
                                         alt="<?php echo $term->name; ?>">

                                    <?php if ( $icon_id ) : ?> 
                                        <!-- <img class="themes-carousel__icon" 
                                            src="<?php // echo wp_get_attachment_url( $icon_id, 'full' ); ?>" 
                                            loading="lazy" 
                                            decoding="async" 
                                            alt="<?php // echo $term->name; ?>"> -->
                                            <?php echo file_get_contents( wp_get_attachment_url( $icon_id, 'full' ) ); ?>
                                    <?php endif; ?>
                                    
                                    <div class="themes-carousel__content">
                                        <p class="themes-carousel__theme"><?php echo $term->name; ?></p>
                                        <span class="themes-carousel__count"><?php echo $count_product_txt; ?></span>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>

                    </div>
                    <div class="swiper-button-prev themes-carousel__prev row-slider__prev"></div>
                    <div class="swiper-button-next themes-carousel__next row-slider__next"></div>
                </div>
            <?php endif; ?>

        </div>
    </section>

<?php endif; ?>