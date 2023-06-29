<?php

/**
 * banners Block Template.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'banners-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'section banners';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}
?>

<?php if ( !empty( $_POST['query']['preview'] ) ) : ?>

    <figure>
        <img src="<?php echo THEME_URI; ?>'/img/gutenberg-preview/banners.png'" alt="Preview" style="width:100%;">
    </figure>

<?php else : ?>

    <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
        <div class="container">

            <?php if ( have_rows('blocks_banners') ): ?>
                <div class="banners-slider swiper row-slider">
                    <div class="swiper-wrapper">

                        <?php 
						while ( have_rows('blocks_banners') ): the_row(); 
							$img_id = get_sub_field('img_blocks_banners');
                            $img_url = wp_get_attachment_url( $img_id, 'full' );
                            $link = get_sub_field('link_blocks_banners');
                            $tag = ( $link ) ? 'a' : 'div';
                            $href = ( $link ) ? 'href="'. $link .'"' : ''; 
						?>

                            <<?php echo $tag; ?> class="swiper-slide banners-slider__item" 
                                <?php echo $href; ?>
                                style="background-image: url('<?php echo $img_url; ?>');">
                            </<?php echo $tag; ?>>
                        
                        <?php endwhile; ?>

                    </div>
                    <div class="swiper-button-prev banners-slider__prev row-slider__prev"></div>
                    <div class="swiper-button-next banners-slider__next row-slider__next"></div>
                </div> 
            <?php endif; ?>

        </div>
    </div>

<?php endif; ?>