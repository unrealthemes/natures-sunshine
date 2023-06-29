<?php 
$show = '';
$show_alert = 'style="display: none;"';
$products = $args['products'];

if ( empty($products) ) {
	$show = 'style="display: none;"';
	$show_alert = 'style="display: inline-flex;"';
}

$count_products = count( $products );
$num_decine = ut_num_decline( 
								$count_products, 
								[ 
									__('product 1', 'natures-sunshine'),
									__('product 2', 'natures-sunshine'),
									__('product 3', 'natures-sunshine')
								] 
							);
?> 

<div class="compare-header">
	<div class="container">

		<div class="compare-inner">
			<h1 class="compare-header__title"><?php echo get_the_title( ut_get_page_id_by_template('template-compare.php') ); ?></h1>

			<p class="compare-header__text color-mono-64" <?php echo $show; ?>>
				<?php echo sprintf( __('There are %s in the list now', 'natures-sunshine'), $num_decine ); ?>
			</p>

			<br>
			<span class="compare-bar__alert alert" <?php echo $show_alert; ?>>
				<svg class="alert__icon" width="24" height="24">
					<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#warning'; ?>"></use>
				</svg>
				<span class="alert__text alert__text--desktop"><?php _e('There are no products to compare. Add products to the comparison of characteristics and choose the product that suits you best.', 'natures-sunshine'); ?></span>
				<span class="alert__text alert__text--mobile"><?php _e('There are no products to compare.', 'natures-sunshine'); ?></span>
			</span>
		</div>

	</div>
</div>