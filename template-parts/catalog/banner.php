<?php


$img_id = (isset($args['post_id'])) ? get_field('img_catalog', $args['post_id']) : get_field('img_catalog');

if ( $img_id ) :
	$img_url = wp_get_attachment_url( $img_id, 'full' );
    $link = (isset($args['post_id'])) ? get_field('link_catalog', $args['post_id']) : get_field('link_catalog');
	//$link = get_field('link_catalog');
	$tag = ( $link ) ? 'a' : 'div';
	$href = ( $link ) ? 'href="'. $link .'"' : '';
?>

	<div class="banner">
		<div class="container">
			<<?php echo $tag; ?> class="banner__block banner__block--catalog"
								 <?php echo $href; ?>
								 style="background-image: url('<?php echo $img_url; ?>'); background-size: cover;">
			</<?php echo $tag; ?>>
		</div>
	</div>

<?php endif; ?>
