<?php 
get_header(); 

$thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
?>

	<div class="wrapper">
		<main class="main">

			<?php do_action( 'echo_kama_breadcrumbs' ); ?>

			<section class="post">
				<div class="container">

					<?php if ( $thumb_url ) : ?>
						<div class="post-header">
							<div class="post-image">
								<img src="<?php echo $thumb_url; ?>" 
									loading="eager" 
									decoding="async"
									alt="<?php the_title() ?>">
							</div>
						</div>
					<?php endif; ?>

					<div class="post-content">
						<?php the_title('<h1 class="post-title">', '</h1>') ?>
						<?php the_content() ?>
					</div>

				</div>
			</section>

		</main>
	</div>

<?php get_footer(); ?>