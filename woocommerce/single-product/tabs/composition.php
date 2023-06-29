<?php
/**
 * Composition tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/composition.php.
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

<div class="tabs-panel__content">
    <div class="composition__content">

        <?php if ( have_rows('blocks_composition') ): ?>

            <ul role="list">

                <?php 
                while ( have_rows('blocks_composition') ): the_row(); 
                    $text = get_sub_field('text_blocks_composition');
                    $value = get_sub_field('value_blocks_composition');

                    if ( !$value ) {
                        $text = '<strong>'. $text .'</strong>';
                    }
                ?>

                    <li>
                        <p class="text"><?php echo $text; ?></p>

                        <?php if ( $value ) : ?>
                            <p class="text"><?php echo $value; ?></p>
                        <?php endif; ?>

                    </li>

                <?php endwhile; ?>

            </ul>

        <?php endif; ?>

    </div>
</div>