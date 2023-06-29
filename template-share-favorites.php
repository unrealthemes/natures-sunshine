<?php
/*
 * Template Name: Share Favorites
 *
 * */

get_header(); 

if ( !isset($_GET['wishlist_id']) || empty($_GET['wishlist_id']) ) {
    return false;
}

$wishlist = YITH_WCWL_Wishlist_Factory::get_wishlist( $_GET['wishlist_id'] );

if ( ! $wishlist ) {
	return false;
}
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

							</div>
							
							<?php 
                            get_template_part( 
                                'template-parts/favorites/content-share', 
                                'wishlist', 
                                [
                                    'wishlist' => $wishlist,
                                    'class_active' => 'active',
                                ] 
                            ); 
							?>

						</form>
					</div>
				</div>
			</section>

			<?php get_template_part( 'template-parts/front-page/popular' ); ?>

		</main>
	</div>

<?php
get_footer();