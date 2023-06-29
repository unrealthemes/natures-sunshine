<?php

	namespace App\Controllers;

	class BodyClassController {
		public function __construct() {
			add_filter( 'body_class', [ $this, 'removeBodyClasses' ] );
		}

		public function removeBodyClasses( $classes ): array {
			if ( is_page( 'login' ) || is_page( 'lost-password' ) || is_page( 'restore-password' ) ) {
				$classes[] = 'login';
			}

			if ( is_page( 'catalog' ) || is_archive() || is_page_template('template-catalog.php') ) {
				$classes[] = 'catalog';
			}

			if ( is_page( 'product' ) || is_page( 'product-empty' ) || is_product() ) {
				$classes[] = 'product';
			}

			if ( is_checkout() || is_page_template( 'template-checkout.php' ) ) {
				$classes[] = 'checkout';
			}

			if ( is_cart() || is_page_template( 'template-cart.php' ) || is_page_template( 'template-cart-joint.php' ) ) {
				$classes[] = 'cart';
			}

			if ( is_home() || is_page_template( 'template-blog.php' ) ) {
				$classes[] = 'blog';
			}

			if ( is_page( 'favorites' ) || is_page_template( 'template-favorites.php' ) ) {
				$classes[] = 'favorites';
			}

			if ( is_page( 'compare' ) || is_page_template( 'template-compare.php' ) ) {
				$classes[] = 'compare';
			}

			return $classes;
		}
	}