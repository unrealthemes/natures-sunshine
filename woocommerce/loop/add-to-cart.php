<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$type_view = get_field('type_view_product_price', 'options');
$class_disabled = ( ! $product->is_in_stock() ) ? ' disabled' : '';
$btn_class = isset( $args['class'] ) ? $args['class'] . $class_disabled : 'button' . $class_disabled;
$btn_txt = ( $product->is_in_stock() ) ? esc_html( __('Buy', 'natures-sunshine') ) : esc_html( __('See more', 'natures-sunshine') );
$btn_class = ( WC()->session->get('partner_ids') ) ? $btn_class .' modal' : $btn_class . ' not-modal';

if ( $type_view == 3 && ! is_user_logged_in() ) :
?>

	<a class="card__controls-buy btn btn-green" href="<?php echo $product->get_permalink(); ?>">
		<?php echo $btn_txt; ?>
	</a>

<?php else : ?>
	
	<?php 
	if ( (is_page_template( 'template-favorites.php') || is_page_template( 'template-compare.php')) && ! WC()->session->get('partner_ids') ) :

		echo apply_filters(
			'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
			sprintf(
				'<a href="%s" data-quantity="%s" class="card__controls-buy btn btn-green loop-btn %s" %s>
					<svg class="btn__icon" width="24" height="24">
						<use
							xlink:href="'. DIST_URI . '/images/sprite/svg-sprite.svg#shop-cart"></use>
					</svg>
					<span class="btn__text">%s</span>
				</a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( $btn_class ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				$btn_txt
			),
			$product,
			$args
		);
	
	elseif ( (is_page_template( 'template-favorites.php') || is_page_template( 'template-compare.php')) && WC()->session->get('partner_ids') ) :
		$btn_class = str_replace('ajax_add_to_cart', '', $btn_class); 
		echo apply_filters(
			'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
			sprintf(
				'<button type="button" data-fancybox="" data-src="#select_cart" data-quantity="%s" value="%s" data-variation-id="%s" class="card__controls-buy btn btn-green loop-btn %s" %s>
					<svg class="btn__icon" width="24" height="24">
						<use
							xlink:href="'. DIST_URI . '/images/sprite/svg-sprite.svg#shop-cart"></use>
					</svg>
					<span class="btn__text">%s</span>
				</button>',
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				$product->get_id(),
				0,
				esc_attr( $btn_class ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				$btn_txt
			),
			$product,
			$args
		);

	elseif ( WC()->session->get('partner_ids') ) :
		$btn_class = str_replace('ajax_add_to_cart', '', $btn_class); 
		echo apply_filters(
			'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
			sprintf(
				'<button type="button" data-fancybox="" data-src="#select_cart" data-quantity="%s" value="%s" data-variation-id="%s" class="card__controls-buy btn btn-green loop-btn %s" %s>
					<svg class="card__controls-buy-hover" width="24" height="24">
						<use xlink:href="'. DIST_URI . '/images/sprite/svg-sprite.svg#plus"></use>
					</svg>
					%s
					<svg class="card__controls-buy-mobile" width="24" height="24">
						<use xlink:href="'. DIST_URI . '/images/sprite/svg-sprite.svg#shop-cart"></use>
					</svg>
				</button>',
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				$product->get_id(),
				0,
				esc_attr( $btn_class ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				$btn_txt
			),
			$product,
			$args
		);

	else :

		echo apply_filters(
			'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
			sprintf(
				'<a href="%s" data-quantity="%s" class="card__controls-buy btn btn-green loop-btn %s" %s>
					<svg class="card__controls-buy-hover" width="24" height="24">
						<use xlink:href="'. DIST_URI . '/images/sprite/svg-sprite.svg#plus"></use>
					</svg>
					%s
					<svg class="card__controls-buy-mobile" width="24" height="24">
						<use xlink:href="'. DIST_URI . '/images/sprite/svg-sprite.svg#shop-cart"></use>
					</svg>
				</a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( $btn_class ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				$btn_txt
			),
			$product,
			$args
		);

	endif;
	?>

<?php endif; ?>