<?php

class UT_Checkout {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        
        if ( wp_doing_ajax() ) {
            add_action( 'wp_ajax_add_coupon', [$this, 'add_coupon'] );
            add_action( 'wp_ajax_nopriv_add_coupon', [$this, 'add_coupon'] );
            
            add_action( 'wp_ajax_remove_coupon', [$this, 'remove_coupon'] );
            add_action( 'wp_ajax_nopriv_remove_coupon', [$this, 'remove_coupon'] );
            
            add_action( 'wp_ajax_register_id_auth', [$this, 'register_id_auth'] );
            add_action( 'wp_ajax_nopriv_register_id_auth', [$this, 'register_id_auth'] );
            
            add_action( 'wp_ajax_edit_id', [$this, 'edit_id'] );
            add_action( 'wp_ajax_nopriv_edit_id', [$this, 'edit_id'] );
        }

        remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 ); 
        remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
        add_action( 'woocommerce_review_order_submit_natures', 'woocommerce_checkout_payment', 20 );
        
        add_filter( 'woocommerce_order_button_html', [$this, 'custom_button_html'] );
        add_filter( 'woocommerce_checkout_fields' , [$this, 'remove_checkout_fields'] ); 
        add_action( 'woocommerce_checkout_process', [$this, 'checkout_field_process'], 40, 2 );
        add_filter( 'woocommerce_update_order_review_fragments', [$this, 'update_order_review_fragments_filter'] );

        // add_action( 'woocommerce_checkout_create_order', [$this, 'before_checkout_create_order'], 20, 2 );
        add_action( 'woocommerce_checkout_update_order_meta', [$this, 'before_checkout_create_order'], 20, 2 );
        add_action( 'woocommerce_checkout_create_order_line_item', [$this, 'save_cart_item_key_as_custom_order_item_metadata'], 10, 4 );
        add_action( 'woocommerce_checkout_order_created', [$this, 'checkout_order_created'], 10 );

        if ( isset($_GET['partnerID']) && ! empty($_GET['partnerID']) ) {
            wc_setcookie( 'register_id_auth', $_GET['partnerID'] );
        }
    }

    public function add_coupon() {

        check_ajax_referer( 'checkout_nonce', 'ajax_nonce' );
        $result = false;

        if ( ! isset($_POST['coupon']) || empty($_POST['coupon']) ) {
            // $error = __( 'Field Promo code is required', 'natures-sunshine' );
            $error = __( 'Field is required', 'natures-sunshine' );
            wp_send_json_error( array(
                // 'message' => $this->coupon_notice_error($error),
                'message' => $error,
            ) );
        } 
        
        $coupon_code = sanitize_text_field($_POST['coupon']);
        $result = WC()->cart->add_discount($coupon_code);

        if ( $result ) {
            wp_send_json_success();
        } else {
            $error = sprintf( __( 'Coupon <b>"%s"</b> does not added', 'natures-sunshine' ), $coupon_code );
            // wc_clear_notices();
            wp_send_json_error( array(
                // 'message' => $this->coupon_notice_error($error),
                'message' => $error,
            ) );
        }
    }

    public static function remove_coupon() {

		check_ajax_referer( 'checkout_nonce', 'ajax_nonce' );
		$coupon = isset( $_POST['coupon'] ) ? wc_format_coupon_code( wp_unslash( $_POST['coupon'] ) ) : false; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( empty($coupon) ) {
			// wc_add_notice( __( 'Sorry there was a problem removing this coupon.', 'natures-sunshine' ), 'error' );
            wp_send_json_error( array(
                'message' => __( 'Sorry there was a problem removing this coupon.', 'natures-sunshine' ),
            ) );
		} else {
			WC()->cart->remove_coupon( $coupon );
			// wc_add_notice( __( 'Coupon has been removed.', 'natures-sunshine' ) );
            wp_send_json_success();
		}

		// wc_print_notices();
		// wp_die();
	}

    public function coupon_notice_error( $text ) {

        $html = '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">
                    <div class="form-checkout__inner">
                        <div class="form__row">
                            <span class="form__alert">
                                <span>
                                    ' . $text . '
                                </span>
                            </span>
                        </div>
                    </div>
                </div>';

        return $html;
    }
    
    public static function register_id_auth() {

		check_ajax_referer( 'checkout_nonce', 'ajax_nonce' );
		
        if ( empty( $_POST['register_id'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'register_id',
                'message' => __('Field Order by ID is required', 'natures-sunshine'),
            ) );
        }   

        WC()->session->set( 'register_id_auth', sanitize_text_field($_POST['register_id']) );
        wp_send_json_success();
	}
    
    public function edit_id() { 

		check_ajax_referer( 'checkout_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );
        $register_id = sanitize_text_field($form['register_id']);
		
        if ( empty($register_id) ) {
            wp_send_json_error( array(
                'name_field' => 'register_id',
                'message' => __('Field ID is required', 'natures-sunshine'),
            ) );
        }   

        WC()->session->set( 'register_id_auth', $register_id );
        wp_send_json_success();
	}

    public function checkout_totals_coupon_html( $coupon ) {

        if ( is_string( $coupon ) ) {
            $coupon = new WC_Coupon( $coupon );
        }
    
        $discount_amount_html = '';
        $amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
        $discount_amount_html = '-' . wc_price( $amount );
    
        if ( $coupon->get_free_shipping() && empty( $amount ) ) {
            $discount_amount_html = __( 'Free shipping coupon', 'woocommerce' );
        }
    
        $discount_amount_html = apply_filters( 'woocommerce_coupon_discount_amount_html', $discount_amount_html, $coupon );
        $coupon_html = $discount_amount_html;
    
        echo wp_kses( apply_filters( 'woocommerce_cart_totals_coupon_html', $coupon_html, $coupon, $discount_amount_html ), array_replace_recursive( wp_kses_allowed_html( 'post' ), array( 'a' => array( 'data-coupon' => true ) ) ) ); // phpcs:ignore PHPCompatibility.PHP.NewFunctions.array_replace_recursiveFound
    }

    public function custom_button_html( $button_html ) {

        $disabled = ( is_user_logged_in() || WC()->session->get('register_id_auth') ) ? '' : 'disabled';
        $button_txt = esc_html__('The order is confirmed', 'natures-sunshine');
        $button_html = '
            <button 
                    type="submit" 
                    class="button alt form-checkout__submit btn btn-green w-100" 
                    name="woocommerce_checkout_place_order" 
                    id="place_order" 
                    '. esc_attr( $disabled ) .'
                    value="'. $button_txt .'" 
                    data-value="'. $button_txt .'">
                        '. $button_txt .'
            </button>';

        return $button_html;
    }

    public function remove_checkout_fields( $fields ) { 

        $shipping_method = preg_replace("/[^A-Za-z_ ]/", '', $_POST['shipping_method'][0]);

        unset($fields['billing']['billing_company']); 
        unset($fields['billing']['billing_postcode']); 
        unset($fields['billing']['billing_state']); 

        $fields['billing']['billing_nova_poshta_warehouse']['required'] = false;
        $fields['billing']['billing_nova_poshta_city']['required'] = false;
        $fields['billing']['billing_mrkvnp_house']['required'] = false;
        $fields['billing']['billing_mrkvnp_street']['required'] = false;
        $fields['billing']['billing_mrkvnp_patronymics']['required'] = false;
        $fields['billing']['ukrposhta_shippping_city']['required'] = false;
        $fields['billing']['ukrposhta_shippping_warehouse']['required'] = false;
        $fields['billing']['billing_address_1']['required'] = false;

        if ( $shipping_method == 'nova_poshta_shipping_method' ) {
            $fields['billing']['billing_nova_poshta_city']['required'] = true;
            $fields['billing']['nova_poshta_warehouse']['required'] = true;
        } else if ( $shipping_method == 'nova_poshta_shipping_method_poshtomat' ) {
            $fields['billing']['billing_nova_poshta_city']['required'] = true;
            $fields['billing']['billing_nova_poshta_warehouse']['required'] = true;
            // $fields['billing']['nova_poshta_poshtomat']['required'] = true;
        } else if ( $shipping_method == 'npttn_address_shipping_method' ) {
            $fields['billing']['billing_nova_poshta_city']['required'] = true;
            $fields['billing']['billing_mrkvnp_house']['required'] = true;
            $fields['billing']['billing_mrkvnp_street']['required'] = true;
            $fields['billing']['billing_mrkvnp_patronymics']['required'] = true;
        } else if ( $shipping_method == 'ukrposhta_shippping' ) {
            $fields['billing']['ukrposhta_shippping_city']['required'] = true;
            $fields['billing']['ukrposhta_shippping_warehouse']['required'] = true;
            
        } /*else if ( $shipping_method == 'justin_shipping_method:11' ) {
            $fields['billing']['billing_nova_poshta_city']['required'] = true;
            $fields['billing']['justin_warehouse']['required'] = true;
        }*/
        
        return $fields; 
    }

    public function checkout_field_process() {

        if ( 
            ( $_POST['billing_city'] == 'Киев' || $_POST['billing_city'] == 'Київ' ) &&
            empty($_POST['delivery_date']) &&
            $_POST['shipping_method'][0] == 'free_shipping:16'
        ) {
            wc_add_notice( __('Delivery date is a required field', 'natures-sunshine'), 'error' );
        }
        
        if ( 
            ( $_POST['billing_city'] == 'Киев' || $_POST['billing_city'] == 'Київ' ) &&
            empty($_POST['delivery_time']) &&
            $_POST['shipping_method'][0] == 'free_shipping:16'
        ) {
            wc_add_notice( __('Time of delivery is a required field', 'natures-sunshine'), 'error' );
        }

        if ( 
            isset($_POST['shipping_method'][0]) && 
            $_POST['shipping_method'][0] == 'free_shipping:16' &&
            (
                empty($_POST['billing_address_1']) ||
                empty($_POST['billing_address_2']) 
                // empty($_POST['billing_address_3']) 
            )
        ) {
            wc_add_notice( __('Fill in the delivery address fields', 'natures-sunshine'), 'error' );
        }
        
        if ( ! $_POST['billing_city'] ) {
            wc_add_notice( __('<b>City select field</b> is a required field', 'natures-sunshine'), 'error' ); 
        }

        if ( isset($_POST['promo']) && empty($_POST['code']) ) {
            wc_add_notice( __( 'Field Promo code is required', 'natures-sunshine' ), 'error' );
        }

    }

    public function update_order_review_fragments_filter( $fragments ) {

        ob_start();
        wc_get_template_part( 'checkout/review-order' );
        $fragments['.form-checkout__sidebar'] = ob_get_clean();

        unset( $fragments['.woocommerce-checkout-review-order-table'] );

        return $fragments;
    }

    public function before_checkout_create_order( $order_id, $data ) {

        $order = wc_get_order( $order_id );
        $partner_ids = WC()->session->get('partner_ids'); // ( ! empty(WC()->session->get('partner_ids')) ) ? serialize(WC()->session->get('partner_ids')) : null;
        $cart_type = WC()->session->get('joint_order_mode');
        $join_products = [];

        if ( is_user_logged_in() ) {
            $register_id = get_user_meta( $order->get_user_id(), 'register_id', true ); 
        } else {
            $register_id = WC()->session->get('register_id_auth');
        }

        if ( $cart_type && $partner_ids ) {
            foreach ( (array)$partner_ids as $partner_id => $full_name ) {
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    if ( isset($cart_item['partner_id']) && $cart_item['partner_id'] == $partner_id ) {
                        $field_key = '_' . $order->get_id() . '_' . $cart_item_key . '_' . $partner_id;
                        $join_products[] = $cart_item_key;
                        update_post_meta( $order->get_id(), $field_key, $cart_item['product_id'] );
                    } 
                }
            }
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {  
                if ( ! in_array($cart_item_key, $join_products) ) {
                    $field_key = '_my_product_' . $order->get_id() . '_' . $cart_item_key;
                    update_post_meta( $order->get_id(), $field_key, $cart_item['product_id'] );
                }
            }
        } 

        update_post_meta( $order->get_id(), '_order_id', $order->get_id() );
        update_post_meta( $order->get_id(), '_register_id', $register_id ); //
        update_post_meta( $order->get_id(), '_partner_ids', $partner_ids );
        update_post_meta( $order->get_id(), '_cart_type', $cart_type );
        update_post_meta( $order->get_id(), '_wp_is_mobile', wp_is_mobile() );
        // address
        update_post_meta( $order->get_id(), '_billing_city', sanitize_text_field($_POST['billing_city']) );
        update_post_meta( $order->get_id(), '_city_code', sanitize_text_field($_POST['city_code']) );
        update_post_meta( $order->get_id(), '_warehouse', sanitize_text_field($_POST['warehouse']) );
        update_post_meta( $order->get_id(), '_billing_address_3', sanitize_text_field($_POST['billing_address_3']) );
        update_post_meta( $order->get_id(), '_patronymic', sanitize_text_field($_POST['patronymic']) );
        update_post_meta( $order->get_id(), '_delivery_date', sanitize_text_field($_POST['delivery_date']) );
        WC()->session->set('delivery_date', sanitize_text_field($_POST['delivery_date']));
        update_post_meta( $order->get_id(), '_delivery_time', sanitize_text_field($_POST['delivery_time']) );
        WC()->session->set('delivery_time', sanitize_text_field($_POST['delivery_time']));
        // city, warehouse and poshtomat codes
        update_post_meta( $order->get_id(), '_nova_poshta_city_code', sanitize_text_field($_POST['nova_poshta_city_code']) );
        update_post_meta( $order->get_id(), '_ukr_city_code', sanitize_text_field($_POST['ukr_city_code']) );
        update_post_meta( $order->get_id(), '_main_warehouse_code', sanitize_text_field($_POST['main_warehouse_code']) );

        ut_help()->address->save_address_after_create_order($order);
    }

    public function checkout_order_created($order) {

        update_user_meta( $order->get_customer_id(), 'billing_phone', sanitize_text_field($_POST['old_billing_phone']) );
        update_user_meta( $order->get_customer_id(), 'shipping_phone', sanitize_text_field($_POST['old_billing_phone']) );
    }

    public function save_cart_item_key_as_custom_order_item_metadata( $item, $cart_item_key, $values, $order ) {
        // Save the cart item key as hidden order item meta data
        $item->update_meta_data( '_cart_item_key', $cart_item_key );
    }
    
} 