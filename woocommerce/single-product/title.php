<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

$desc_blocks = get_field('list_short_desc', $product->get_id());
?>

<div class="product-info__details">
    <?php the_title( '<h1 class="product-info__title">', '</h1>' ); ?>
 
    <?php if ( $desc_blocks ): ?>

        <div class="product-info__list card__list">
            <ul>
                <?php foreach ( $desc_blocks as $key => $desc_block ) : ?>

                    <?php if ($desc_block['txt_list_short_desc']) : ?>
                        <li>
                            <?php echo $desc_block['txt_list_short_desc']; ?>
                        </li>
                    <?php endif; ?>

                <?php endforeach; ?>
            </ul>
        </div>

    <?php else : ?>

        <div class="product-info__list card__list">
            <?php the_excerpt(); ?>
        </div>

    <?php endif; ?>

    <div class="product-info__line">
    
        <?php 
            get_template_part(
                'woocommerce/single-product/dose',
                null,
                ['product_id' => $product->get_id()]
            ); 
        ?>
        
        <?php ut_help()->product->best_before( $product->get_id() ); ?>

    </div>
</div>