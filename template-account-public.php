<?php
/*
 * Template Name: Личный кабинет (Публичная информация)
 *
 * */

get_header( 'profile' );
?>

	<main class="main">
		<div class="profile-wrapper">
			<div class="container">
				<div class="profile-inner">
					<?php get_template_part( 'template-parts/profile/profile-sidebar' ); ?>
					<?php get_template_part( 'template-parts/profile/public/profile-content' ); ?>
				</div>
			</div>
		</div>
	</main>

<?php
get_footer('empty');
