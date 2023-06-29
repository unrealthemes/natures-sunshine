<?php

class UT_Order {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        if ( wp_doing_ajax() ) {
            add_action( 'wp_ajax_repeat_order', [$this, 'repeat_order'] );
            add_action( 'wp_ajax_nopriv_repeat_order', [$this, 'repeat_order'] );
        }

        add_action( 'woocommerce_admin_order_items_after_line_items', [$this, 'admin_order_items_after_line_items'] );
        add_action( 'woocommerce_checkout_update_order_meta', [$this, 'checkout_field_update_order_meta'] );
        add_action( 'woocommerce_admin_order_data_after_billing_address', [$this, 'get_order_meta_in_admin_order_page'], 10, 1 );
        add_action( 'woocommerce_order_status_changed', [$this, 'order_status_changed' ], 10, 3 );
        
    }

    public function get_filter_args( $data ) {

        $user_id = get_current_user_id();
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $args = [
            'paged' => $paged,
            'post_status' => wc_get_order_statuses(), 
            'post_type' => 'shop_order',
            'orderby' => 'date',
            'order' => 'DESC',
            // 'customer_id' => $user_id, 
            'meta_key' => '_customer_user',
            'meta_value' => $user_id,
        ]; 

        if ( isset($_GET['search']) && ! empty($_GET['search']) ) {
            $args['meta_query'][] = [ 
				'relation' => 'OR',
				[
					'key' => '_billing_first_name',
					'value' => $_GET['search'],
					'compare' => 'LIKE',
				],
				[
					'key' => '_billing_last_name',
					'value' => $_GET['search'],
					'compare' => 'LIKE',
				],
				[
					'key' => '_patronymic',
					'value' => $_GET['search'],
					'compare' => 'LIKE',
				],
				[
					'key' => '_partner_ids',
					'value' => $_GET['search'],
					'compare' => 'LIKE',
				],
				[
					'key' => '_order_id',
					'value' => $_GET['search'],
					'compare' => '=',
				],
            ];
        }

        if ( isset($_GET['status']) && ! empty($_GET['status']) ) {
            $args['post_status'] = $_GET['status'];
        }
        
        if ( isset($_GET['type']) && ! empty($_GET['type']) ) {
            $type = ( $_GET['type'] == 'joint' ) ? '1' : null;
            $args['meta_query'][] = [
                [
                    'key' => '_cart_type',
                    'value' => $type,
                    'compare' => '='
                ]
            ];
        }

        if ( isset($_GET['date_range']) && empty($_GET['date_range']) && isset($_GET['date']) && $_GET['date'] == 'today' ) {
            $today = getdate();
            $args['year'] = $today['year'];
            $args['monthnum'] = $today['mon'];
            $args['day'] = $today['mday'];
        }
        
        if ( isset($_GET['date_range']) && empty($_GET['date_range']) && isset($_GET['date']) && $_GET['date'] == 'yesterday' ) {
            $today = getdate();
            $args['year'] = $today['year'];
            $args['monthnum'] = $today['mon'];
            $args['day'] = $today['mday'] - 1;
        }
        
        if ( isset($_GET['date_range']) && empty($_GET['date_range']) && isset($_GET['date']) && $_GET['date'] == 'week' ) {
            $week = date('W');
            $year = date('Y');
            $args['year'] = $year;
            $args['w'] = $week;
        }

        if ( isset($_GET['date_range']) && empty($_GET['date_range']) && isset($_GET['date']) && $_GET['date'] == 'month' ) {
            $args['date_query'] = [
                'after' => '1 months ago',
                [
                    'dayofweek' => [ 1, 5 ],
                    'compare' => 'BETWEEN',
                ],
            ];
        }

        if ( isset($_GET['date_range']) && empty($_GET['date_range']) && isset($_GET['date']) && $_GET['date'] == 'quarter' ) {
            $args['date_query'] = [
                'after' => '3 months ago',
                [
                    'dayofweek' => [ 1, 5 ],
                    'compare' => 'BETWEEN',
                ],
            ];
        }
       
        if ( isset($_GET['date_range']) && empty($_GET['date_range']) && isset($_GET['date']) && $_GET['date'] == 'year' ) {
            $year = date('Y');
            $args['year'] = $year;
        }

        if ( isset($_GET['date_range']) && ! empty($_GET['date_range']) ) {
            $date_array = explode(' - ', $_GET['date_range']);
            $start_date = strtotime($date_array[0]);
            $end_date = strtotime($date_array[1]);
            $args['date_query'] = [
                [
                    'after' => [
                        'year' => date('Y', $start_date),
                        'month' => date('m', $start_date),
                        'day' => date('d', $start_date),
                    ],
                    'before' => [
                        'year' => date('Y', $end_date),
                        'month' => date('m', $end_date),
                        'day' => date('d', $end_date),
                    ],
                    'inclusive' => true,
                ],
            ];
        }

        return $args;
    }

    public function admin_order_items_after_line_items( $order_id ) {

        $order = wc_get_order( $order_id );
        $line_items = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );
        $join_products = [];
        $register_id = get_post_meta( $order->get_id(), '_register_id', true );
        $partner_ids = get_post_meta( $order->get_id(), '_partner_ids', true );
        // $partner_ids_str = get_post_meta( $order->get_id(), '_partner_ids', true );
        // $partner_ids = ( $partner_ids_str ) ? unserialize($partner_ids_str) : null;
        $cart_type = get_post_meta( $order->get_id(), '_cart_type', true );
        $cart_type_txt = ( $cart_type ) ? __('Joint', 'natures-sunshine') : __('Personal', 'natures-sunshine');

        if ( $cart_type ) {

            echo '<tr class="item join-item" style="background-color: #f0f0f1;">';
                echo '<td></td>';
                echo '<td class="name" style="width: 100%; font-weight: 700;">';
                    echo __('Personal cart', 'natures-sunshine') . ' – '. $register_id; 
                echo '</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
            echo '</li>';

            if ( $partner_ids ) {
                foreach ( (array)$partner_ids as $partner_id => $full_name ) {
                    foreach ( $order->get_items() as $item_id => $item ) {
                        $cart_item_key = $item->get_meta( '_cart_item_key' );
                        $field_key = '_' . $order->get_id() . '_' . $cart_item_key . '_' . $partner_id;
                        $product_in_join_cart = get_post_meta( $order->get_id(), $field_key, true );
                        if ( $product_in_join_cart ) {
                            $join_products[] = $item_id;
                        }
                    }
                }
            }

            foreach ( $line_items as $item_id => $item ) {

                if ( ! in_array($item_id, $join_products) ) {
                    get_template_part( 
                        'woocommerce/admin/html-order-item', 
                        null, 
                        [
                            'item_id' => $item_id,
                            'item' => $item,
                            'order' => $order,
                        ] 
                    );
                    // include ABSPATH . 'wp-content/plugins/woocommerce/includes/admin/meta-boxes/view/html-order-item.php';
                }
            }

            if ( $partner_ids ) {
                foreach ( (array)$partner_ids as $partner_id => $full_name ) {

                    echo '<tr class="item join-item" style="background-color: #f0f0f1;">';
                        echo '<td></td>';
                        echo '<td class="name" style="width: 100%; font-weight: 700;">';
                        if ( $full_name ) {
                            echo sprintf(
                                '%1s - %2s',
                                $full_name,
                                $partner_id
                            );
                        } else {
                            echo $partner_id;
                        }
                        echo '</td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                    echo '</li>';

                    foreach ( $line_items as $item_id => $item ) {
                        $cart_item_key = $item->get_meta( '_cart_item_key' );
                        $field_key = '_' . $order->get_id() . '_' . $cart_item_key . '_' . $partner_id;
                        $product_in_join_cart = get_post_meta( $order->get_id(), $field_key, true );
                        if ( $product_in_join_cart ) {
                            get_template_part( 
                                'woocommerce/admin/html-order-item', 
                                null, 
                                [
                                    'item_id' => $item_id,
                                    'item' => $item,
                                    'order' => $order,
                                ] 
                            );
                        }
                    }
                }
            }
        } else {

            echo '<tr class="item join-item" style="background-color: #f0f0f1;">';
                echo '<td></td>';
                echo '<td class="name" style="width: 100%; font-weight: 700;">';
                    echo __('Personal cart', 'natures-sunshine') . ' - ' . $register_id; 
                echo '</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
            echo '</li>';

            foreach ( $line_items as $item_id => $item ) {
                get_template_part( 
                    'woocommerce/admin/html-order-item', 
                    null, 
                    [
                        'item_id' => $item_id,
                        'item' => $item,
                        'order' => $order,
                    ] 
                );
            }
        }
    }

    public function checkout_field_update_order_meta( $order_id ) {

        if ( isset($_POST['callback']) ) {
            update_post_meta( $order_id, 'callback', esc_attr($_POST['callback']) );
        }
    }

    public function get_order_meta_in_admin_order_page($order) {

        $callback = get_post_meta( $order->get_id(), "callback", true );
        $warehouse = get_post_meta( $order->get_id(), '_warehouse', true );
        $billing_address_3 = get_post_meta( $order->get_id(), '_billing_address_3', true );
        $delivery_date = get_post_meta( $order->get_id(), '_delivery_date', true );
        $delivery_time = get_post_meta( $order->get_id(), '_delivery_time', true );
        $warehouse_code = get_post_meta( $order->get_id(), '_main_warehouse_code', true );
      
        if ( $billing_address_3 ) {
            echo '<p><strong style="margin-right: 5px;">' . __('Flat', 'natures-sunshine') . ':</strong>' . $billing_address_3 . '</p>';
        }
        
        if ( $callback ) {
            echo '<p><strong style="margin-right: 5px;">'. __('Manager`s call', 'natures-sunshine') .' ✅</strong></p>';
        }
        
        if ( $warehouse ) {
            echo '<p><strong style="margin-right: 5px;">' . __('Warehouse', 'natures-sunshine') . ':</strong>' . $warehouse . '</p>';
        }

        if ( $delivery_date ) {
            $date_f = date_i18n("j F", strtotime($delivery_date));
            echo '<p><strong style="margin-right: 5px;">' . __('Delivery date', 'natures-sunshine') . ':</strong>' . $date_f . '</p>';
        }
        
        if ( $delivery_time ) {
            echo '<p><strong style="margin-right: 5px;">' . __('Time of delivery', 'natures-sunshine') . ':</strong>' . $delivery_time . '</p>';
        }
        
        if ( $warehouse_code ) {
            echo '<p><strong style="margin-right: 5px;">' . __('Warehouse code', 'natures-sunshine') . ':</strong>' . $warehouse_code . '</p>';
        }
    } 

    public function order_status_changed( $order_id, $old_status, $new_status ) {

        $order = wc_get_order( $order_id );
        $middle_name = get_post_meta( $order->get_id(), '_patronymic', true );
        $full_name = $order->get_billing_last_name() . ' ' . $order->get_billing_first_name() . ' ' . $middle_name;
        $order_statuses = wc_get_order_statuses();
        $data = [
            'status' => $order_statuses[ 'wc-' . $new_status ],
            'order_id' => $order->get_id(),
            'full_name' => $full_name,
            'user_email' => $order->get_billing_email(),
        ];  
        ut_help()->esputnik->send_change_order_status_message( $data );
    }

    public function repeat_order() {

        check_ajax_referer( 'order_nonce', 'ajax_nonce' );
        $order_id = $_POST['order_id'];
        $order = wc_get_order( $order_id );

        if ( $order ) {
            // WC()->cart->empty_cart();
            $cart_type = get_post_meta( $order->get_id(), '_cart_type', true );
            $partner_ids = get_post_meta( $order->get_id(), '_partner_ids', true );
            // $partner_ids_str = get_post_meta( $order->get_id(), '_partner_ids', true );
            // $partner_ids = ( $partner_ids_str ) ? unserialize($partner_ids_str) : null;

            if ( $cart_type ) {
                $join_products = [];
                WC()->session->set( 'joint_order_mode' , true );
                if ( $partner_ids ) {
                    foreach ( (array)$partner_ids as $partner_id => $full_name ) {
                        foreach ( $order->get_items() as $item_id => $item ) {
                            $cart_item_key = $item->get_meta( '_cart_item_key' );
                            $field_key = '_' . $order->get_id() . '_' . $cart_item_key . '_' . $partner_id;
                            $product_in_join_cart = get_post_meta( $order->get_id(), $field_key, true );
                            if ( $product_in_join_cart ) {
                                $join_products[] = $item_id;
                            }
                        }
                        $partner_ids[ $partner_id ] = $full_name;
                    }
                }
                
                foreach ( $order->get_items() as $item_id => $item ) {          
                    if ( ! in_array($item_id, $join_products) && $item->get_product() ) {
                        WC()->cart->add_to_cart( 
                            $item->get_product_id(), 
                            $item->get_quantity() 
                        );
                    }
                }

                if ( $partner_ids ) {
                    foreach ( (array)$partner_ids as $partner_id => $full_name ) {
                        foreach ( $order->get_items() as $item_id => $item ) {
                            $cart_item_key = $item->get_meta( '_cart_item_key' );
                            $field_key = '_' . $order->get_id() . '_' . $cart_item_key . '_' . $partner_id;
                            $product_in_join_cart = get_post_meta( $order->get_id(), $field_key, true );
                            if ( $product_in_join_cart && $item->get_product() ) {
                                WC()->cart->add_to_cart( 
                                    $item->get_product_id(), 
                                    $item->get_quantity(), 
                                    0, 
                                    [], 
                                    [
                                        'partner_id' => $partner_id
                                    ] 
                                );
                            }
                        }
                    }
                }
                WC()->session->set('partner_ids', $partner_ids);

            } else {

                WC()->session->set( 'joint_order_mode' , false );
                foreach ( $order->get_items() as $item_id => $item ) {
                    if ( $item->get_product() ) {
                        WC()->cart->add_to_cart( 
                            $item->get_product_id(), 
                            $item->get_quantity() 
                        );
                    }
                }
            }

            wp_send_json_success([
                'redirect_url' => wc_get_cart_url(),
            ]);
        }

        wp_send_json_error();
    }

}  