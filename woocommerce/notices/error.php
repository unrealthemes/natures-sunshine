<?php
/**
 * Show error messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/error.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $notices ) {
	return;
}

?>

<div class="form-checkout__inner">

    <?php 
    foreach ( $notices as $notice ) : 

        if ( isset($notice['data']['id']) && $notice['data']['id'] == 'billing_nova_poshta_city' ) {
            continue;
        }
        
        if ( isset($notice['data']['id']) && $notice['data']['id'] == 'ukrposhta_shippping_city' ) {
            continue;
        }
        
        if ( isset($notice['data']['id']) && $notice['data']['id'] == 'nova_poshta_warehouse' ) {
            $notice['notice'] = __('<b>Warehouse select field</b> is a required', 'natures-sunshine');
        }
        
        if ( isset($notice['data']['id']) && $notice['data']['id'] == 'billing_nova_poshta_warehouse' ) {
            $notice['notice'] = __('<b>Poshtomat select field</b> is a required', 'natures-sunshine');
        }
        
        // if ( isset($notice['data']['id']) && $notice['data']['id'] == 'justin_warehouse' ) {
        //     $notice['notice'] = __('<b>Warehouse select field</b> is a required', 'natures-sunshine');
        // }
        
        if ( isset($notice['data']['id']) && $notice['data']['id'] == 'ukrposhta_shippping_warehouse' ) {
            $notice['notice'] = __('<b>Warehouse select field</b> is a required', 'natures-sunshine');
        }
        
        if ( isset($notice['data']['id']) && $notice['data']['id'] == 'billing_mrkvnp_street' ) {
            $notice['notice'] = __('<b>Street field</b> is a required', 'natures-sunshine');
        }
        
        if ( isset($notice['data']['id']) && $notice['data']['id'] == 'billing_mrkvnp_house' ) {
            $notice['notice'] = __('<b>House field</b> is a required', 'natures-sunshine');
        }
        
        if ( isset($notice['data']['id']) && $notice['data']['id'] == 'billing_mrkvnp_patronymics' ) {
            $notice['notice'] = __('<b>Patronymics field</b> is a required', 'natures-sunshine');
        }
    ?>

        <div class="form__row">
            <span class="form__alert">
                <svg class="error" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10 1.75C5.44365 1.75 1.75 5.44365 1.75 10C1.75 14.5563 5.44365 18.25 10 18.25C14.5563 18.25 18.25 14.5563 18.25 10C18.25 5.44365 14.5563 1.75 10 1.75ZM0.25 10C0.25 4.61522 4.61522 0.25 10 0.25C15.3848 0.25 19.75 4.61522 19.75 10C19.75 15.3848 15.3848 19.75 10 19.75C4.61522 19.75 0.25 15.3848 0.25 10Z" fill="#EE6A5E"/>
                    <path d="M12.4393 13.5C12.7322 13.7929 13.207 13.7929 13.4999 13.5C13.7928 13.2071 13.7928 12.7322 13.4999 12.4393L11.0606 10L13.5 7.56066C13.7928 7.26777 13.7928 6.7929 13.5 6.5C13.2071 6.20711 12.7322 6.20711 12.4393 6.5L9.99995 8.93935L7.5606 6.5C7.26771 6.20711 6.79284 6.20711 6.49994 6.5C6.20705 6.7929 6.20705 7.26777 6.49994 7.56066L8.93929 10L6.49995 12.4393C6.20706 12.7322 6.20706 13.2071 6.49995 13.5C6.79285 13.7929 7.26772 13.7929 7.56061 13.5L9.99995 11.0607L12.4393 13.5Z" fill="#EE6A5E"/>
                </svg>
                <span><?php echo wc_kses_notice( $notice['notice'] ); ?></span>
            </span>
        </div>

    <?php endforeach; ?>

</div>