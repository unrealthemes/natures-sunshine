<?php

/**
 * advantages Block Template.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'advantages-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'front';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}
?>

<?php if ( !empty( $_POST['query']['preview'] ) ) : ?>

    <figure>
        <img src="<?php echo THEME_URI; ?>'/img/gutenberg-preview/advantages.png'" alt="Preview" style="width:100%;">
    </figure>

<?php else : ?>
 
	<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<div class="container">

			<?php if ( have_rows('adv') ): ?>
				
				<div class="front-advantages advantages">
					<ul class="advantages-list" role="list">
						
                        <?php 
						while ( have_rows('adv') ): the_row(); 
							$img_id = get_sub_field('icon_adv');
							$title = get_sub_field('title_adv'); 
							$desc = get_sub_field('desc_adv');  
							$link = get_sub_field('link_adv');  
						?>

							<li class="advantages-list__item list-item">
								<a href="<?php echo $link; ?>" 
                                   class="list-item__link" 
                                   title="<?php echo $title; ?>">

                                    <?php if ( $img_id ) : ?>
                                        <div class="list-item__icon">
                                            <img src="<?php echo wp_get_attachment_url( $img_id, 'full' ); ?>" 
                                                 width="24" 
                                                 height="24" 
                                                 loading="eager" 
                                                 decoding="async" 
                                                 alt="">
                                        </div>
                                    <?php endif; ?>

									<div class="list-item__content">

                                        <?php if ( $title ) : ?>
                                            <p class="list-item__content-title">
                                                <?php echo $title; ?>	
                                            </p>
                                        <?php endif; ?>

                                        <?php if ( $desc ) : ?>
										    <span class="list-item__content-text">
                                                <?php echo $desc; ?>
                                            </span>
                                        <?php endif; ?>

									</div>
								</a>
							</li>
						
                        <?php endwhile; ?>

					</ul>
				</div>

			<?php endif; ?>

		</div>
	</section>

<?php endif; ?>