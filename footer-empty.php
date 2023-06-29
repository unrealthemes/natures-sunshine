<?php
/**
 * The template for displaying the empty footer
 *
 * Contains the closing of the .content-area, .site-content and .site divs and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

wp_footer(); 
?>

		<?php 
		if ( is_page_template('template-account.php') ) :
			get_template_part( 'template-parts/modals/delete-account' );
			get_template_part( 'template-parts/modals/change-password' );
			get_template_part( 'template-parts/modals/change-main-phone-email' );
		endif;

		if ( is_page_template('template-account-addresses.php') ) :
			get_template_part( 'template-parts/modals/cities' );
			get_template_part( 'template-parts/modals/add-address' );
		endif;
		?>

	</body>
</html>