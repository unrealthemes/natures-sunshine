<?php 
$show = '';
$products = $args['products'];
$days_list = [ 
    __('day 1', 'natures-sunshine'),
    __('day 2', 'natures-sunshine'),
    __('day 3', 'natures-sunshine')
];
$drops_list = [ 
    __('drop 1', 'natures-sunshine'),
    __('drop 2', 'natures-sunshine'),
    __('drop 3', 'natures-sunshine')
];
$capsules_list = [ 
    __('capsule 1', 'natures-sunshine'),
    __('capsule 2', 'natures-sunshine'),
    __('capsule 3', 'natures-sunshine')
];
$emulsions_list = [ 
    __('emulsions 1', 'natures-sunshine'),
    __('emulsions 2', 'natures-sunshine'),
    __('emulsions 3', 'natures-sunshine')
];
$sticks_list = [ 
    __('sticks 1', 'natures-sunshine'),
    __('sticks 2', 'natures-sunshine'),
    __('sticks 3', 'natures-sunshine')
];
$portions_list = [ 
    __('portion 1', 'natures-sunshine'),
    __('portion 2', 'natures-sunshine'),
    __('portion 3', 'natures-sunshine')
];
$tablets_list = [ 
    __('tablet 1', 'natures-sunshine'),
    __('tablet 2', 'natures-sunshine'),
    __('tablet 3', 'natures-sunshine')
];

if ( empty($products) ) {
	$show = 'style="display: none;"';
}
?>

<div class="compare-content" <?php echo $show; ?>>
	<div class="container">
		<div class="compare-inner">
			<div class="compare-wrapper">
				<div class="compare-specs">

					<!-- Short description -->
					<div class="compare-specs__item">
						<a href="javascript:" class="compare-specs__link collapse-link"> 
							<?php _e('Description', 'natures-sunshine'); ?> 
							<span class="collapse-icon">
								<svg width="24" height="24">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
								</svg>
							</span> 
						</a>
						<div class="compare-specs__panel collapse-panel">
							<div class="compare-specs__slider compare-swiper cards swiper">
								<div class="swiper-wrapper">

									<?php 
									foreach ( (array)$products as $product ) : 
										$excerpt = $product->get_short_description();
										$desc_blocks = get_field('list_short_desc', $product->get_id());
									?>

										<div class="swiper-slide cards__item compare-card">
											
											<?php if ( $desc_blocks ): ?>
												<ul class="card__list">
													<?php foreach ( $desc_blocks as $key => $desc_block ) : ?>
														<li class="card__list-item">
															<?php echo $desc_block['txt_list_short_desc']; ?>
														</li>
													<?php endforeach; ?>
												</ul>
											<?php elseif ( $excerpt ) : ?>
												<?php 
												// $length = strlen( $excerpt );
												$excerpt_parts = get_excerpt_parts( $excerpt );
												?>

												<div class="card__list">
													<div class="card__list-item">
														<?php // the_excerpt(); ?>
														<?php echo $excerpt_parts['first']; ?>
													</div>
												</div> 
												
												<div class="card__collapse">
													<div class="card__list">
														<div class="card__list-item">
															<p><?php echo $excerpt_parts['last']; ?></p>
														</div>
													</div>
												</div>

											<?php endif; ?>

										</div>
									
									<?php endforeach; ?>
									
								</div>
							</div>
						</div>
					</div>

					<!-- Composition -->
					<div class="compare-specs__item">
						<a href="javascript:" class="compare-specs__link collapse-link"> 
							<?php _e('Composition', 'natures-sunshine'); ?> 
							<span class="collapse-icon">
								<svg width="24" height="24">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
								</svg>
							</span> 
						</a>
						<div class="compare-specs__panel collapse-panel">
							<div class="compare-specs__slider compare-swiper cards swiper">
								<div class="swiper-wrapper">

									<?php foreach ( (array)$products as $product ) : ?>

										<div class="swiper-slide cards__item compare-card">
											
											<?php if ( have_rows('blocks_composition', $product->get_id()) ): ?>

												<?php 
												while ( have_rows('blocks_composition', $product->get_id()) ): the_row(); 
													$text = get_sub_field('text_blocks_composition');
													$value = get_sub_field('value_blocks_composition');

													if ( !$value ) {
														$text = '<strong>'. $text .'</strong>';
													}
												?>

													<div>
														<p class="text">
															<?php echo $text; ?>

															<?php if ( $value ) : ?>
																<?php echo ' - ' . $value; ?>
															<?php endif; ?>
														</p>
													</div>

												<?php endwhile; ?>

											<?php endif; ?>

										</div>
									
									<?php endforeach; ?>

								</div>
							</div>
						</div>
					</div>

					<!-- Type -->
					<div class="compare-specs__item">
						<a href="javascript:" class="compare-specs__link collapse-link"> 
							<?php _e('Type', 'natures-sunshine'); ?> 
							<span class="collapse-icon">
								<svg width="24" height="24">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
								</svg>
							</span> 
						</a>
						<div class="compare-specs__panel collapse-panel">
							<div class="compare-specs__slider compare-swiper cards swiper">
								<div class="swiper-wrapper">

									<?php 
									foreach ( (array)$products as $product ) : 
										$type = get_field('application', $product->get_id());
									?>

										<div class="swiper-slide cards__item compare-card">

											<?php 
											if ( $type == 'capsules' ) : 
												echo ut_num_decline( 2, $capsules_list, false );
											elseif ( $type == 'tablets' ) : 
												echo ut_num_decline( 2, $tablets_list, false );
											elseif ( $type == 'powders' ) : 
												echo ut_num_decline( 2, $portions_list, false );
											elseif ( $type == 'emulsions' ) : 
												echo ut_num_decline( 2, $emulsions_list, false );
											elseif ( $type == 'sticks' ) : 
												echo ut_num_decline( 2, $sticks_list, false );
											endif;
											?>

										</div>

									<?php endforeach; ?>

								</div>
							</div>
						</div>
					</div>

					<!-- Quantity -->
					<div class="compare-specs__item">
						<a href="javascript:" class="compare-specs__link collapse-link"> 
							<?php _e('Quantity', 'natures-sunshine'); ?> 
							<span class="collapse-icon">
								<svg width="24" height="24">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
								</svg>
							</span> 
						</a>
						<div class="compare-specs__panel collapse-panel">
							<div class="compare-specs__slider compare-swiper cards swiper">
								<div class="swiper-wrapper">
									
									<?php 
									foreach ( (array)$products as $product ) : 
										$type = get_field('application', $product->get_id());
										$qty = get_field('qty_type', $product->get_id());
									?>

										<div class="swiper-slide cards__item compare-card">
											<?php 
											if ( $type == 'capsules' ) : 
    											echo ut_num_decline( $qty, $capsules_list ); 
											elseif ( $type == 'tablets' ) : 
												echo ut_num_decline( $qty, $tablets_list );
											elseif ( $type == 'powders' ) : 
												echo sprintf( '%1s %2s', $qty, __('gr','natures-sunshine') ); 
											elseif ( $type == 'emulsions' ) : 
												echo ut_num_decline( $qty, $drops_list );
											elseif ( $type == 'sticks' ) : 
												echo ut_num_decline( $qty, $sticks_list );
											endif;
											?>
										</div>

									<?php endforeach; ?>

								</div>
							</div>
						</div>
					</div>

					<!-- Portion -->
					<div class="compare-specs__item">
						<a href="javascript:" class="compare-specs__link collapse-link"> 
							<?php _e('Portion', 'natures-sunshine'); ?>  
							<span class="collapse-icon">
								<svg width="24" height="24">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
								</svg>
							</span> 
						</a>
						<div class="compare-specs__panel collapse-panel">
							<div class="compare-specs__slider compare-swiper cards swiper">
								<div class="swiper-wrapper">

									<?php 
									foreach ( (array)$products as $product ) : 
										$portions = get_field('porcii_type', $product->get_id());
									?>

										<div class="swiper-slide cards__item compare-card">
											<?php echo ut_num_decline( $portions, $portions_list ); ?>
										</div>

									<?php endforeach; ?>

								</div>
							</div>
						</div>
					</div>

					<!-- Health topics -->
					<div class="compare-specs__item">
						<a href="javascript:" class="compare-specs__link collapse-link"> 
							<?php _e('Health topics', 'natures-sunshine'); ?>
							<span class="collapse-icon">
								<svg width="24" height="24">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
								</svg>
							</span> 
						</a>
						<div class="compare-specs__panel collapse-panel">
							<div class="compare-specs__slider compare-swiper cards swiper">
								<div class="swiper-wrapper">
									
									<?php 
									foreach ( (array)$products as $product ) : 
										$terms = wp_get_object_terms( 
											$product->get_id(),
											'health-topics',
											[ 
												'meta_query' => [
													[
														'key' => 'icon_ht',
														'value'   => [''],
														'compare' => 'NOT IN'
													]
												]
											]
										);
									?>

										<div class="swiper-slide cards__item compare-card">
											<ul class="compare-specs__list specs-list">

												<?php 
												foreach ( $terms as $term ) :  
													$icon_id = get_field( 'icon_ht', $term );
            										$icon_url = wp_get_attachment_url( $icon_id, 'full' );
												?>

													<li class="specs-list__item">
														<span class="btn btn-icon">
															<?php echo file_get_contents( $icon_url );  ?>
															<?php echo $term->name; ?>
														</span>
													</li>

												<?php endforeach; ?>

											</ul>
										</div>

									<?php endforeach; ?>

								</div>
							</div>
						</div>
					</div>

					<!-- Body system -->
					<div class="compare-specs__item">
						<a href="javascript:" class="compare-specs__link collapse-link"> 
							<?php _e('Body systems', 'natures-sunshine'); ?> 
							<span class="collapse-icon">
								<svg width="24" height="24">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#chevron-down'; ?>"></use>
								</svg>
							</span> 
						</a>
						<div class="compare-specs__panel collapse-panel">
							<div class="compare-specs__slider compare-swiper cards swiper">
								<div class="swiper-wrapper">

									<?php 
									foreach ( (array)$products as $product ) : 
										$terms = wp_get_object_terms( 
											$product->get_id(),
											'body-systems',
											[]
										);
									?>

										<div class="swiper-slide cards__item compare-card">

											<?php foreach ( $terms as $term ) :  ?>
												<?php echo $term->name; ?>
											<?php endforeach; ?>

										</div>

									<?php endforeach; ?>

								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>