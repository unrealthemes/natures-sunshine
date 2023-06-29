<?php

/**
 * seo Block Template.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'seo-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'seo';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$terms = get_field('terms_seo');
?>

<?php if ( !empty( $_POST['query']['preview'] ) ) : ?>

    <figure>
        <img src="<?php echo THEME_URI; ?>'/img/gutenberg-preview/seo.png'" alt="Preview" style="width:100%;">
    </figure>

<?php else : ?>

	<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<div class="container">

			<?php if ( $terms ) : ?>
				<ul class="seo-list" role="list">

					<?php 
					foreach  ( $terms as $term ) : 
						$thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true ); 
						$img_url = ( $thumb_id ) ? wp_get_attachment_url( $thumb_id, 'full' ) : wc_placeholder_img_src();
						// $link = get_term_link( (int)$term->term_id, 'product_cat' );
						$link = ut_get_permalik_by_template('template-catalog.php') . 'pc-in-'. $term->slug .'/';
					?>
						<li class="seo-list__item">
							<a href="<?php echo $link; ?>" class="seo-list__link" title="<?php echo $term->name; ?>">
								<h2 class="seo-list__title"><?php echo $term->name; ?></h2>
								<div class="seo-list__image">
									<img src="<?php echo $img_url; ?>" 
										 loading="lazy" 
										 decoding="async" 
										 alt="<?php echo $term->name; ?>">
								</div>
							</a>
						</li>
					<?php endforeach; ?>

				</ul>
			<?php endif; ?>

		</div>
	</div> 

<?php endif; ?>