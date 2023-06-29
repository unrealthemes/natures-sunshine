<?php 
$args = ut_help()->order->get_filter_args( $_GET );
$query = new WP_Query( $args );
?>

<div class="profile-content">
	<div class="profile-header">
		<?php the_title('<h1 class="profile-header__title">', '</h1>'); ?>
	</div>
	<form class="form" id="order_form" action="" method="GET">
	    <div class="orders">

			<?php get_template_part( 'template-parts/profile/orders/order', 'filter' ); ?>

		    <div class="orders__results">

				<?php if ( $query->have_posts() ) :  ?>

					<div class="orders__results-block orders-results">
						<!-- <h2 class="orders-results__title">Сегодня</h2> -->
						<ul class="orders-results__list">

							<?php 
							while ( $query->have_posts() ) : $query->the_post();
								$order = wc_get_order( $post->ID );
								get_template_part( 'template-parts/profile/orders/order', 'item', ['order' => $order] );
							endwhile; 
							?>

						</ul>
					</div>

				<?php else : ?>

					<h2><?php _e('Orders not found', 'natures-sunshine'); ?></h2>

				<?php endif; ?>

		    </div>

			<div class="js-pagination">
				<?php
				$GLOBALS['wp_query'] = $query; // for custom template
				the_posts_pagination( [
					'prev_text' => '<svg width="24" height="24">
										<use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-left"></use>
									</svg>',
					'next_text' => '<svg width="24" height="24">
										<use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-right"></use>
									</svg>',
					// 'base' => $url . 'p-%#%/',
				] );
				wp_reset_query();
				?>
			</div>

	    </div>
    </form>
</div>