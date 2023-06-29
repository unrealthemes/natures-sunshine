<?php

/**
 * banner Block Template.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'banner-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'section banner';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$img_id = get_field('img_banner');
?>

<?php if ( !empty( $_POST['query']['preview'] ) ) : ?>

    <figure>
        <img src="<?php echo THEME_URI; ?>'/img/gutenberg-preview/banner.png'" alt="Preview" style="width:100%;">
    </figure>

<?php else : ?>

    <?php 
    if ( $img_id ) : 
        $img_url = wp_get_attachment_url( $img_id, 'full' );
        $link = get_field('link_banner');
        $tag = ( $link ) ? 'a' : 'div';
        $href = ( $link ) ? 'href="'. $link .'"' : '';
    ?>
        <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
            <div class="container">
                <<?php echo $tag; ?> class="banner__block" 
                    <?php echo $href; ?>
                     style="background-image: url('<?php echo $img_url; ?>');">
                </<?php echo $tag; ?>>
            </div>
        </div>  
    <?php endif; ?>

<?php endif; ?>