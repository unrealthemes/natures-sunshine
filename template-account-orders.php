<?php
/*
 * Template Name: Личный кабинет (Мои заказы)
 *
 * */

get_header( 'profile' );
?>

	<main class="main">
		<div class="profile-wrapper">
			<div class="container">
				<div class="profile-inner profile-inner--orders">
					<?php get_template_part( 'template-parts/profile/profile-sidebar' ); ?>
					<?php get_template_part( 'template-parts/profile/orders/profile-content' ); ?>
				</div>
			</div>
		</div>
	</main>

<?php
get_footer('empty');
