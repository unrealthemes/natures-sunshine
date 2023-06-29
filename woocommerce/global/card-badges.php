<?php 
global $product;

$bestseller = get_post_meta( $product->get_id(), '_bestseller', true );
$discount = get_post_meta( $product->get_id(), '_discount', true );
?>

<div class="card__badges">

    <?php 
    if ( $bestseller ) : 
        $span_class = ( isset($args['span_class']) ) ? $args['span_class'] : '';
        $span_text = ( isset($args['span_class']) ) ? __('Best-seller', 'natures-sunshine') : '';
    ?>
        <span class="card__badge card__badge--green <?php echo $span_class; ?>">
            <?php echo $span_text; ?>
            <img class="card__badge-icon" src="<?= DIST_URI . '/images/icons/fire.svg'; ?>" width="24" height="24" loading="lazy" decoding="async" alt="fire">
        </span>
    <?php endif; ?>
    
    <?php if ( $discount ) :  ?>
        <span class="card__badge card__badge--white">
            <img class="card__badge-icon" src="<?= DIST_URI . '/images/icons/percent.svg'; ?>" width="24" height="24" loading="eager" decoding="async" alt="percent">
        </span>
    <?php endif; ?>

</div>