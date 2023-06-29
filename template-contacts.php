<?php
/*
 * Template Name: Contacts
 *
 * */

get_header(); 
?>

	<div class="wrapper">
		<main class="main">

			<?php do_action( 'echo_kama_breadcrumbs' ); ?>

			<section class="contacts">
				<div class="container">
					<div class="contacts__inner">

						<?php
							get_template_part( 'template-parts/contacts/content' );
							get_template_part( 'template-parts/contacts/form' );
						?>

					</div>
				</div>
			</section>

		</main>
	</div> 

<?php
get_footer();