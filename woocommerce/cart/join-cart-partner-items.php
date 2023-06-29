<?php 
$partner_ids = WC()->session->get('partner_ids');
// WC()->session->set('partner_ids', null);
// echo '<pre>';
// print_r( $partner_ids );
// echo '</pre>';
?>

<?php if ( $partner_ids ) : ?>

    <?php foreach ( (array)$partner_ids as $partner_id => $full_name ) : ?>

        <?php
        get_template_part( 
            'woocommerce/cart/join-cart-partner-item', 
            null, 
            [
                'full_name' => $full_name,
                'partner_id' => $partner_id,
            ] 
        );
        ?>

    <?php endforeach; ?>

<?php endif; ?>