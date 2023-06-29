<?php 
$show = '';
$product_ids = [];
$products = $args['products'];

if ( empty($products) ) {
	$show = 'style="display: none;"';
}

foreach ( $products as $product ) {
	$product_ids[] = $product->get_id();
}
$product_ids_str = implode(',', $product_ids);

$alert_show = ( count($products) == 1 ) ? 'style="display: inline-flex;"' : '';
?>
 
<div class="compare-sticky" <?php echo $show; ?>>
	<div class="compare-bar">
		<div class="container">
			<div class="compare-inner">
				<div class="compare-wrapper">
					<div class="inline-scroll">
						<div class="inline-scroll__content">

							<span class="compare-bar__alert alert" <?php echo $alert_show; ?>>
								<svg class="alert__icon" width="24" height="24">
									<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#warning'; ?>"></use>
								</svg>
								<span class="alert__text alert__text--desktop"><?php _e('Not enough products to compare', 'natures-sunshine'); ?></span>
								<span class="alert__text alert__text--mobile"><?php _e('Not enough products', 'natures-sunshine'); ?></span>
							</span>

							<?php if ( ! is_page_template('template-share-compare.php') ) : ?>

								<a class="compare-bar__button compare-bar__button--green btn btn-icon btn-white" 
								   href="<?php echo ut_get_permalik_by_template('template-catalog.php'); ?>">
									<svg class="btn__icon" width="24" height="24">
										<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#plus'; ?>"></use>
									</svg>
									<span class="btn__text"><?php _e('Add product', 'natures-sunshine'); ?></span>
								</a>

								<button class="compare-bar__button btn btn-icon btn-white btn-mobile copy_compare_link" 
										data-url="<?php echo ut_get_permalik_by_template('template-share-compare.php') . '?products=' . $product_ids_str; ?>"
										data-copy-text="<?php _e('Copied', 'natures-sunshine'); ?>"
										type="button">
									<svg class="btn__icon" width="24" height="24">
										<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#account-id-copy'; ?>"></use>
									</svg>
									<span class="btn__text"><?php _e('Share list', 'natures-sunshine'); ?></span>
								</button>

								<button class="compare-bar__button btn btn-icon btn-white btn-mobile remove_all_compare" type="button">
									<svg class="btn__icon" width="24" height="24">
										<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#trash'; ?>"></use>
									</svg>
									<span class="btn__text"><?php _e('Delete', 'natures-sunshine'); ?></span>
								</button>

							<?php endif; ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="compare-slider">
		<div class="container">
			<div class="compare-inner">
				<div class="compare-wrapper">
					<div class="compare-swiper cards swiper">
						<div class="swiper-wrapper">

							<?php 
							foreach ( (array)$products as $product ) :
								$GLOBALS['product'] = $product; 
								get_template_part( 'woocommerce/content', 'product-compare', [] );
							endforeach; 
							?>
							
						</div>
						<div class="swiper-button-prev cards__prev"></div>
						<div class="swiper-button-next cards__next"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>