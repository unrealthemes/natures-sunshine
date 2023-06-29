<?php 
$img_id = get_field('img_b_catalog', 'option');

if ( $img_id ) : 
	$img_url = wp_get_attachment_url( $img_id, 'full' );
	$link = get_field('link_b_catalog', 'option');
	$tag = ( $link ) ? 'a' : 'div';
	$href = ( $link ) ? 'href="'. $link .'"' : '';
?>

    <div class="catalog-banner">
        <div class="banner">
            <<?php echo $tag; ?> class="banner__inner" 
								 <?php echo $href; ?>
								 style="background-image: url('<?php echo $img_url; ?>'); background-size: cover;">
			</<?php echo $tag; ?>>
        </div>
    </div>

<?php endif; ?>