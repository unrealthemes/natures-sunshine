<?php
/**
 * Certificates tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/certificates.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

global $post;
?>

<?php if ( have_rows('blocks_certificates') ): ?>

    <div class="certificates-slider cards swiper">
        <div class="swiper-wrapper">

            <?php 
            while ( have_rows('blocks_certificates') ): the_row(); 
                $file = get_sub_field('img_blocks_certificates');
                $title = get_sub_field('title_blocks_certificates');

                if ( $file['mime_type'] == 'application/pdf' ) { // pdf file
                    $preview = $file['icon'];
                } else {
                    $preview = $file['sizes']['medium'];
                }
            ?>

                <div class="swiper-slide certificates-slider__item cards__item">
                    <a href="<?php echo $file['url']; ?>" data-fancybox="certs" class="certificates-slider__link">
                        <div class="certificates-slider__image">
                            <img src="<?php echo $preview; ?>" loading="lazy" decoding="async" alt="">
                        </div>
                        <p class="certificates-slider__text text"><?php echo $title; ?></p>
                    </a>
                </div>
            
            <?php endwhile; ?>

        </div>
        <div class="swiper-button-prev cards__prev"></div>
        <div class="swiper-button-next cards__next"></div>
    </div>

<?php endif; ?>