<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

if ( $order ) :
?>

    <div class="profile-wrapper">
        <div class="container">

            <div class="page page__error-404">
                <section class="error-404 not-found">
                    <h1 class="error-404__title">
                        <?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </h1>
                    <span class="error-404__desc text color-mono-64">
                        <?php esc_html_e( 'You can monitor the status in your personal account and by notifications - they will be sent to the specified number and mail', 'natures-sunshine' ); ?>
                    </span>
                    <a href="<?= esc_url(home_url('/')); ?>" class="error-404__link btn btn-green">
                        <?php esc_html_e( 'Continue Shopping', 'natures-sunshine' ); ?>
                    </a>
                </section>
            </div>

            <div class="profile-content">
                <div class="orders">
                    <div class="orders__results">
                        <div class="orders__results-block orders-results">
                            <ul class="orders-results__list">
                                <?php get_template_part( 'template-parts/profile/orders/order', 'item-thankyou', ['order' => $order] ); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php endif; ?>