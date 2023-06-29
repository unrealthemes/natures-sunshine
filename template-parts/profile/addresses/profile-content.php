<?php 
$user_id = get_current_user_id();
$user = get_userdata( $user_id );
$taxonomy = 'type';
$delivery_types = get_terms( [
	'hide_empty' => 1,
	'orderby' => 'name',
	'order' => 'ASC',
	'taxonomy' => $taxonomy,
] );
$filter_cities = ut_help()->address->get_filter_cities();
?>

<div class="profile-addresses"> 

	<div class="profile-header">
		<?php the_title('<h1 class="profile-header__title">', '</h1>'); ?>
		<button type="button" data-fancybox data-src="#add-address" class="profile-header__button btn btn-square btn-green js-add-address">
			<svg width="24" height="24">
				<use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#plus'; ?>"></use>
			</svg>
		</button>
	</div>

	<form id="accaunt_form" action="" class="form address-form" method="post">

	    <div class="profile-row">
		    <div class="profile-filter">

			    <div class="profile-filter__select select">
				    <label for="filter_city" hidden><?php _e('City', 'natures-sunshine'); ?></label>
				    <select name="filter_city" id="filter_city">
					    <option value=""  hidden><?php _e('City', 'natures-sunshine'); ?></option>

						<?php if ( $filter_cities ) : ?>
							<?php foreach ( $filter_cities as $filter_city ) : ?>

					    		<option value="<?php echo esc_attr($filter_city); ?>">
									<?php echo $filter_city; ?>
								</option>

							<?php endforeach; ?>
						<?php endif; ?>

				    </select>
			    </div>

			    <div class="profile-filter__select select">
				    <label for="filter_type" hidden><?php _e('Type', 'natures-sunshine'); ?></label>
				    <select name="filter_type" id="filter_type">
						<option value="" hidden><?php _e('Type', 'natures-sunshine'); ?></option>
					    <option value="all"><?php _e('All', 'natures-sunshine'); ?></option>
					    <option value="pickup"><?php _e('Pickup', 'natures-sunshine'); ?></option>
					    <option value="delivery"><?php _e('Delivery', 'natures-sunshine'); ?></option>
				    </select>
			    </div>

			    <button type="button" 
						data-fancybox data-src="#add-address" 
						class="profile-filter__button btn btn-icon btn-green js-add-address">
				    <svg class="btn__icon" width="24" height="24">
					    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#plus'; ?>"></use>
				    </svg>
				    <span class="btn__text"><?php _e('Add new', 'natures-sunshine'); ?></span>
			    </button>

		    </div>
	    </div>

		<?php 
		foreach ( $delivery_types as $delivery_type ) : 
			$query = new WP_Query( [ 
				'author' =>  $user_id,
				'post_type' => 'address', 
				'post_status' => 'publish', 
				'posts_per_page' => -1, 
				'tax_query' => [
					[
						'taxonomy' => $taxonomy,
						'field' => 'slug',
						'terms' => $delivery_type->slug
					]
				]
			] ); 
		?>

			<div class="profile-row <?php echo $delivery_type->slug; ?>">
				<h2 class="profile-title profile-title--20"><?php echo $delivery_type->name; ?></h2>
				<div class="profile-places">

					<?php if ( $query->have_posts() ) : ?>

						<?php while ( $query->have_posts() ) : $query->the_post(); ?>

							<?php get_template_part( 'template-parts/profile/addresses/content', 'address', ['type' => $delivery_type->slug] ); ?>

						<?php endwhile; ?>

					<?php 
					endif; 
					wp_reset_postdata(); 
					?>

				</div>
			</div>

	    <?php 
		endforeach;
		?>

    </form>

</div>