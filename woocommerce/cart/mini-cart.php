<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

$type_cart = WC()->session->get('joint_order_mode');
$total_pv = 0;
$total_products = 0;
$total_price = 0;
$page_id = get_option( 'woocommerce_cart_page_id' );
$tooltip_txt = get_field('tooltip_mini_cart', $page_id);
$type_view = get_field('type_view_product_price', 'options');

do_action( 'woocommerce_before_mini_cart' ); 
?>

<div class="mini-cart-wrapper">
	<div class="ut-loader"></div>

	<?php if ( ! WC()->cart->is_empty() ) : ?>

		<div class="header-cart__body">
			<ul class="cart-products__list-items">

				<?php
				do_action( 'woocommerce_before_mini_cart_contents' );

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :

					if ( $type_cart && isset($cart_item['partner_id']) ) {
						continue;
					}

					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
						$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						// $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						$img_url = get_the_post_thumbnail_url( $_product->get_id(), 'thumbnail' );
						$pv = get_post_meta( $cart_item['product_id'], '_pv', true );
						$total_pv += floatval($pv) * $cart_item['quantity'];
						$total_products++;
						$total_price += $_product->get_price() * $cart_item['quantity'];

						if ( !$img_url ) {
							$img_url = wc_placeholder_img_src();
						}
						?>

						<li class="cart-products__list-item cart-product"
							data-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
							<div class="cart-product__info">
								<div class="cart-product__info-image">
									<img class="card__image-default" 
										src="<?php echo $img_url; ?>" 
										loading="lazy" 
										decoding="async" 
										alt="<?php echo $product_name; ?>">
								</div>
								<div class="cart-product__info-details">
									<div class="cart-product__info-top">
										<span class="cart-product__info-sku"><?php echo _e('SKU', 'natures-sunshine') . ' ' . $_product->get_sku(); ?></span>
										
										<?php if ( is_user_logged_in() || $type_view != 1 && $type_view != 3 && $_product->get_sale_price() ) : ?>
											<span class="cart-product__info-old card__price-old">
												<?php echo strip_tags( wc_price( $_product->get_regular_price() ) ); ?>
											</span>
										<?php endif; ?>

									</div>
									<div class="cart-product__info-title">
										<h2 class="card__title">
											<a href="<?php echo esc_url( $product_permalink ); ?>" class="card__title-link" title="<?php echo $product_name; ?>">
												<?php echo $product_name; ?>
											</a>
										</h2>

										<?php if ( $type_view == 3 && ! is_user_logged_in() ) : ?>

										<?php elseif ( $type_view == 1 && ! is_user_logged_in() ) : ?>

											<span class="cart-product__info-price text">
												<?php echo strip_tags( wc_price( $_product->get_regular_price() ) ); ?>
											</span>

										<?php else : ?>

											<?php if ( $_product->get_sale_price() ) : ?>
												<span class="cart-product__info-price text">
													<?php echo strip_tags( wc_price( $_product->get_sale_price() ) ); ?>
												</span>
											<?php else : ?>
												<span class="cart-product__info-price text">
													<?php echo strip_tags( wc_price( $_product->get_regular_price() ) ); ?>
												</span>
											<?php endif; ?>

										<?php endif; ?>

									</div>

									<div class="cart-product__info-bottom">
										<?php get_template_part('woocommerce/cart/cart', 'dose', ['product_id' => $product_id]); ?>
									</div>

								</div>
							</div>
							<div class="cart-product__controls">

								<?php 
								woocommerce_quantity_input( 
									[ 
										'location' => 'mini_cart',
										'input_value' => $cart_item['quantity'],
									], 
									$_product, 
									true 
								); 
								?>

								<?php if ( $pv ) : ?>
									<div class="cart-product__controls-points card__price-points">
										<img src="<?= DIST_URI . '/images/icons/nsp-logo.svg'; ?>" loading="lazy" decoding="async" alt="PV">
										<?php echo $pv . ' ' . PV; ?>
									</div>
								<?php endif; ?>

								<?php 
								get_template_part(
									'template-parts/favorites/icon', 
									'postpone', 
									[
										'product_id' => $product_id,
										'is_product_in_wishlist' => ut_help()->wishlist->is_product_in_wishlist( $product_id ),
									]
								); 
								?>

								<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove_from_cart_button cart-products__button cart-product__delete btn btn-square" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"><svg width="24" height="24"><use xlink:href="'. DIST_URI . '/images/sprite/svg-sprite.svg#trash"></use></svg></a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_attr__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $cart_item_key ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
								?>
							</div>
						</li>
					
					<?php 
					endif;

				endforeach;

				do_action( 'woocommerce_mini_cart_contents' );

				$subtotal_txt = ut_num_decline( 
					$total_products, 
					[ 
						__('product 1', 'natures-sunshine'),
						__('product 2', 'natures-sunshine'),
						__('product 3', 'natures-sunshine')
					] 
				);
				?>

			</ul>
		</div>

		<div class="header-cart__bottom">

			<div class="cart-points w-100">
				<div class="card__price-points">
					<img src="<?php echo DIST_URI; ?>/images/icons/nsp-logo.svg" loading="lazy" decoding="async" alt="">
					<span class="hidden-mobile">
						<?php 
							echo sprintf( 
								__('Let`s accrue %1s %2s', 'natures-sunshine'), 
								$total_pv, 
								PV 
							); 
						?>
					</span>
					<span class="hidden-desktop">
						<?php 
							echo sprintf( 
								__('+%s points per order', 'natures-sunshine'), 
								$total_pv
							); 
						?>
					</span>
				</div>

				<?php if ( $tooltip_txt ) : ?>
					<div class="tooltip" data-tooltip="<?php echo nl2br($tooltip_txt); ?>">
						<svg width="20" height="20">
							<use xlink:href="<?php echo DIST_URI; ?>/images/sprite/svg-sprite.svg#info-16"></use>
						</svg>
					</div>
				<?php endif; ?>

			</div>

			<div class="cart-total">
				<div class="cart-price">
					<span class="text color-mono-64"><?php echo $subtotal_txt; ?> <?php _e('for the amount', 'natures-sunshine'); ?> </span>
					<span class="cart-price__total"><?php echo wc_price( $total_price ); ?></span>
				</div>
				<div class="cart-total__buttons">
					<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-cart__button btn btn-secondary">
						<?php _e('Go to cart', 'natures-sunshine'); ?>
					</a>
					<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="header-cart__button btn btn-green">
						<?php _e('Checkout', 'natures-sunshine'); ?>
					</a>
				</div>
			</div>
		</div>

	<?php else : ?>

		<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'woocommerce' ); ?></p>

	<?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
