<?php
/*
 * Template Name: Page
 *
 * */

get_header();
?>

<div class="wrapper">
	<main class="main">

		<section class="post">
			<div class="container">
				<div class="post-content">

					<?php
					while ( have_posts() ) :
						the_post();
						the_title('<h1 class="post-title">', '</h1>');
						the_content();
					endwhile; // End of the loop.
					?>

				</div>
			</div>
		</section>

	</main>
</div>

<?php get_footer(); ?>