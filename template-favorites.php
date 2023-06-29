<?php
/*
 * Template Name: Favorites
 *
 * */

get_header(); 

$user_wishlists = YITH_WCWL()->get_current_user_wishlists();
?>

<div class="wrapper">
	<main class="main">

		<section class="favorites page">
			<div class="container">
				<div class="page-inner">
					<form class="form form-favorites">

						<div class="favorites-block">

							<?php the_title('<h1 class="page-title">', '</h1>'); ?>

							<?php if ( !is_user_logged_in() ) : ?>
								<div class="notice favorites-notice">
									<h2 class="notice__title"><?php _e( 'Save your wishlists', 'natures-sunshine' ); ?></h2>
									<p class="notice__text">
										<?php 
											echo sprintf( 
												__('This list will not be saved. <a href="%s">Register or Login</a> to the system to save your list.', 'natures-sunshine'),
												ut_get_permalik_by_template('template-login.php'),
											);
										?>
									</p>
								</div>
							<?php endif; ?>

							<?php if ( is_user_logged_in() ) : ?>
								<button class="favorites-button btn btn-green" data-fancybox="" data-src="#add_list">
									<?php _e( 'Add new list', 'natures-sunshine' ); ?>
								</button>
							<?php endif; ?>

						</div>
						
						<?php 
						foreach ( $user_wishlists as $wishlist ) : 
							get_template_part( 
								'template-parts/favorites/content', 
								'wishlist', 
								[
									'wishlist' => $wishlist,
									'class_active' => '',
								] 
							);
						endforeach; 
						?>

					</form>
				</div>
			</div>
		</section>

	</main>
</div>

<div class="wrapper">
	<?php
		get_template_part( 'template-parts/front-page/popular' );
		get_footer();
	?>
</div>