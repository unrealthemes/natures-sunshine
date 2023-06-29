<?php

/**
 * slider Block Template.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'slider-' . $block['id'];
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
        <img src="<?php echo THEME_URI; ?>'/img/gutenberg-preview/slider.png'" alt="Preview" style="width:100%;">
    </figure>

<?php else : ?>
 
	<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
		<div class="container">

			<?php if ( have_rows('slides') ): ?>
				
				<div class="front-carousel swiper">
					<div class="swiper-wrapper">

						<?php 
						while ( have_rows('slides') ): the_row(); 
							$img_id = get_sub_field('img_slides');
							$title = get_sub_field('title_slides'); 
							$desc = get_sub_field('desc_slides'); 
							$txt_btn = get_sub_field('txt_btn_slides'); 
							$link_btn = get_sub_field('link_btn_slides'); 
						?>

							<div class="swiper-slide front-carousel__slide">

								<?php if ( $img_id ) : ?>
									<div class="front-carousel__image">
										<img src="<?php echo wp_get_attachment_url( $img_id, 'full' ); ?>" 
											loading="eager" 
											decoding="async" 
											alt="">
									</div>
								<?php endif; ?>

								<div class="front-carousel__content">

								<?php if ( $title ) : ?>
										<h2 class="front-carousel__title h1" style="color: #F5F6FA;">
											<?php echo nl2br($title); ?>	
										</h2>
									<?php endif; ?>

									<?php if ( $desc ) : ?>
										<p class="front-carousel__text" style="color: #F5F6FA;">
											<?php echo nl2br($desc); ?>	
										</p>
									<?php endif; ?>
									
									<?php if ( $txt_btn ) : ?>
										<a href="<?php echo $link_btn; ?>" class="front-carousel__btn btn btn-green">
											<?php echo $txt_btn; ?>
										</a>
									<?php endif; ?>

								</div>
							</div>
							
						<?php endwhile; ?>

					</div>
					<div class="swiper-pagination front-carousel__pagination"></div>
					<div class="swiper-button-prev front-carousel__prev"></div>
					<div class="swiper-button-next front-carousel__next"></div>
				</div>

			<?php endif; ?>

		</div>
	</section>

<?php endif; ?>