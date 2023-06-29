<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */

 global $product;
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>

    <section class="description">
        <div class="container">
            <div class="tabs">

                <?php 
                $i = 1;
                foreach ( $product_tabs as $key => $product_tab ) : 
                    $active = ( $i == 1 ) ? 'active' : '';

                    if ( $key == 'description' && empty($product->get_description()) ) {
                        continue;
                    } else if ( $key == 'composition' && ! have_rows('blocks_composition') ) {
                        continue;
                    } else if ( $key == 'contraindications' && empty(get_field('txt_contraindications')) ) {
                        continue;
                    } else if ( $key == 'notes' && empty(get_field('txt_notes')) ) {
                        continue;
                    } else if ( $key == 'certificates' && ! have_rows('blocks_certificates') ) {
                        continue;
                    }
                ?>

                    <a href="#<?php echo esc_attr( $key ); ?>" class="text tabs-link <?php echo $active; ?>">
                        <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                    </a> 

                <?php $i++; endforeach; ?>

            </div>
            <div class="tabs-panels">

                <?php 
                $j = 1;
                foreach ( $product_tabs as $key => $product_tab ) : 
                    $active = ( $j == 1 ) ? 'active' : '';
                    $class = ( $key == 'reviews' ) ? 'hidden-desktop' : '';

                    if ( $key == 'description' && empty($product->get_description()) ) {
                        continue;
                    } else if ( $key == 'composition' && ! have_rows('blocks_composition') ) {
                        continue;
                    } else if ( $key == 'contraindications' && empty(get_field('txt_contraindications')) ) {
                        continue;
                    } else if ( $key == 'notes' && empty(get_field('txt_notes')) ) {
                        continue;
                    } else if ( $key == 'certificates' && ! have_rows('blocks_certificates') ) {
                        continue;
                    }
                ?>  

                    <div class="tabs-panel desc <?php echo $active; ?>" id="<?php echo esc_attr( $key ); ?>">
                        <h2 class="tabs-panel__title <?php echo esc_attr($class); ?>">
                            <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                            <svg width="24" height="24">
                                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
                            </svg>
                        </h2>
                        <div class="tabs-panel__collapse">
                            <?php
                            if ( isset( $product_tab['callback'] ) ) :
                                call_user_func( $product_tab['callback'], $key, $product_tab );
                            endif;
                            ?>
                        </div>
                    </div>

                <?php $j++; endforeach; ?>

                <?php do_action( 'woocommerce_product_after_tabs' ); ?>

            </div>
        </div>
    </section>

<?php endif; ?>
