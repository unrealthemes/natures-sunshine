<?php

class UT_Cart {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        
        if ( wp_doing_ajax() ) {
            add_action( 'wp_ajax_quantity_update_mini_cart', [$this, 'quantity_update_mini_cart'] );
            add_action( 'wp_ajax_nopriv_quantity_update_mini_cart', [$this, 'quantity_update_mini_cart'] );

            add_action( 'wp_ajax_quantity_update_cart', [$this, 'quantity_update_cart'] );
            add_action( 'wp_ajax_nopriv_quantity_update_cart', [$this, 'quantity_update_cart'] );
            
            add_action( 'wp_ajax_autocomplete_cart', [$this, 'autocomplete_cart'] );
            add_action( 'wp_ajax_nopriv_autocomplete_cart', [$this, 'autocomplete_cart'] );
            
            add_action( 'wp_ajax_add_to_cart', [$this, 'add_to_cart'] );
            add_action( 'wp_ajax_nopriv_add_to_cart', [$this, 'add_to_cart'] );
            
            add_action( 'wp_ajax_add_to_cart_partner', [$this, 'add_to_cart_partner'] );
            add_action( 'wp_ajax_nopriv_add_to_cart_partner', [$this, 'add_to_cart_partner'] );
            
            add_action( 'wp_ajax_joint_order_mode', [$this, 'joint_order_mode'] );
            add_action( 'wp_ajax_nopriv_joint_order_mode', [$this, 'joint_order_mode'] );
            
            add_action( 'wp_ajax_exit_join_cart', [$this, 'exit_join_cart'] );
            add_action( 'wp_ajax_nopriv_exit_join_cart', [$this, 'exit_join_cart'] );
            
            add_action( 'wp_ajax_check_reg_id', [$this, 'check_reg_id'] );
            add_action( 'wp_ajax_nopriv_check_reg_id', [$this, 'check_reg_id'] );
            
            add_action( 'wp_ajax_add_reg_id', [$this, 'add_reg_id'] );
            add_action( 'wp_ajax_nopriv_add_reg_id', [$this, 'add_reg_id'] );
            
            add_action( 'wp_ajax_remove_partner_cart', [$this, 'remove_partner_cart'] );
            add_action( 'wp_ajax_nopriv_remove_partner_cart', [$this, 'remove_partner_cart'] );
        }

        add_filter( 'woocommerce_add_to_cart_fragments', [$this, 'add_to_cart_fragment'] );
        add_filter( 'woocommerce_add_cart_item_data', [$this, 'force_individual_cart_items'], 10, 2 );
        add_action( 'woocommerce_before_cart', [$this, 'check_for_out_of_stock_products'] );
    }

    public function quantity_update_mini_cart() {

        check_ajax_referer( 'cart_nonce', 'ajax_nonce' );
        $cart_item_key = $_POST['cart_item_key'];
        $qty = $_POST['qty'];

        if ( $cart_item_key && $qty ) {
            WC()->cart->set_quantity( $cart_item_key, $qty, true ); 
        }

        wp_send_json_success();
    }
    
    public function quantity_update_cart() {

        check_ajax_referer( 'cart_nonce', 'ajax_nonce' );
        $cart_item_key = $_POST['cart_item_key'];
        $qty = $_POST['qty'];

        if ( $cart_item_key && $qty ) {
            WC()->cart->set_quantity( $cart_item_key, $qty, true ); 
        }

        wp_send_json_success();
    }

    public function add_to_cart_fragment( $fragments ) {

        // cart count
        ob_start();
        get_template_part('template-parts/header/bar', 'cart');
        $fragments['.cart-count'] = ob_get_clean();

        // mini cart
        // ob_start();
        // wc_get_template_part( 'cart/mini-cart' );
        // unset( $fragments['div.widget_shopping_cart_content'] );
        // $fragments['.mini-cart-wrapper'] = ob_get_clean();

        // if ( is_cart() ) {
            $type_cart = WC()->session->get('joint_order_mode');
            // total cart
            ob_start();
            wc_get_template_part( 'cart/cart-totals' );
            ?>
                    <script>
                        function cartCollapseResize() {
                            document.querySelectorAll('.cart-collapse').forEach(collapse => {
                                if (collapse.classList.contains('active')) {
                                    collapse.style.maxHeight = collapse.scrollHeight + "px";
                                }
                        });
                        }
                        cartCollapseResize();
                    </script>
                <?php
            $fragments['.cart-sidebar'] = ob_get_clean();

            if ( $type_cart ) {

                // product list my join cart
                ob_start();
                echo '<ul class="cart-products__list-items">';
                    wc_get_template_part( 'cart/cart-item' );
                echo '</ul>';
                $fragments['.my-cart .cart-products__list-items'] = ob_get_clean();

                // my cart total
                $total_pv = 0;
                $total_products = 0;
                $total_price = 0;
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    if ( isset($cart_item['partner_id']) ) {
                        continue;
                    }
                    $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        $pv = get_post_meta( $cart_item['product_id'], '_pv', true );
                        $total_pv += $pv * $cart_item['quantity'];
                        $total_products++;
                        $total_price += $_product->get_price() * $cart_item['quantity'];
                    }
                }
                ob_start();
                get_template_part( 
                    'woocommerce/cart/join-cart-result', 
                    null, 
                    [
                        'total_products' => $total_products,
                        'total_pv' => $total_pv,
                        'total_price' => $total_price,
                    ] 
                );
                $fragments['.my-cart-result'] = ob_get_clean();

                // join cart partner total
                $partner_ids = WC()->session->get('partner_ids');
                if ( $partner_ids ) {
                    foreach ( (array)$partner_ids as $partner_id => $full_name ) {
                        $total_pv = 0;
                        $total_products = 0;
                        $total_price = 0;
                        ob_start();
                        echo '<ul class="cart-products__list-items">';
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            if ( isset($cart_item['partner_id']) && $cart_item['partner_id'] == $partner_id ) {
                                $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                    $pv = get_post_meta( $cart_item['product_id'], '_pv', true );
                                    $total_pv += $pv * $cart_item['quantity'];
                                    $total_products++;
                                    $total_price += $_product->get_price() * $cart_item['quantity'];
                                    //
                                    get_template_part( 
                                        'woocommerce/cart/join-cart-partner-items-product', 
                                        null, 
                                        [
                                            'cart_item_key' => $cart_item_key,
                                            'cart_item' => $cart_item,
                                            '_product' => $_product,
                                        ] 
                                    );
                                }
                            }
                        }
                        echo '</ul>';
                        $fragments['.partner-cart-' . $partner_id . ' .cart-products__list-items'] = ob_get_clean();

                        ob_start();
                        get_template_part( 
                            'woocommerce/cart/join-cart-partner-result', 
                            null, 
                            [
                                'total_products' => $total_products,
                                'total_pv' => $total_pv,
                                'total_price' => $total_price,
                            ] 
                        );
                        $fragments['.partner-cart-' . $partner_id . ' .join-cart-result'] = ob_get_clean();
                    }
                }

            } else {

                // product list cart
                ob_start();
                wc_get_template_part( 'cart/cart-items' );
                $fragments['.cart-products__list'] = ob_get_clean();

            }
        // }

        return $fragments;
    }

    public function autocomplete_cart() {

        check_ajax_referer( 'cart_nonce', 'ajax_nonce' );

        if ( ! isset( $_REQUEST['search_txt'] ) ) {
            echo json_encode( [] );
        }

        global $post;
        $search_txt = $_REQUEST['search_txt'];
        $suggestions = [];
        $query = new WP_Query([
            's' => $search_txt,
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'suppress_filters' => false,
            'tax_query' => [
                [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'exclude-from-catalog',
                    'operator' => 'NOT IN',
                ],
            ],
        ]);

        ob_start();
        
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                global $product; 
                $GLOBALS['product'] = wc_get_product(get_the_ID()); 
                get_template_part( 'woocommerce/cart/cart-products-search-items' );
            }
            wp_reset_postdata();
        }
        //search by sku
        $args = [
            'posts_per_page' => -1,
            'post_type' => 'product',
            'post_status' => 'publish',
            'suppress_filters' => false,
            'meta_query' => [
                [
                    'key' => '_sku',
                    'value' => $search_txt,
                    'compare' => 'LIKE'
                ]
            ],
            'tax_query' => [
                [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'exclude-from-catalog',
                    'operator' => 'NOT IN',
                ],
            ],
        ];
        $posts = get_posts($args);

        if ( $posts ) {
            foreach ( $posts as $post ) {
                setup_postdata( $post );
                global $product; 
                $GLOBALS['product'] = wc_get_product(get_the_ID()); 
                get_template_part( 'woocommerce/cart/cart-products-search-items' );
            }
            wp_reset_postdata();
        }
        //search by english name
        $args = [
            'posts_per_page' => -1,
            'post_type' => 'product',
            'post_status' => 'publish',
            'suppress_filters' => false,
            'meta_query' => [
                [
                    'key' => '_eng_name',
                    'value' => $search_txt,
                    'compare' => 'LIKE'
                ]
            ],
            'tax_query' => [
                [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'exclude-from-catalog',
                    'operator' => 'NOT IN',
                ],
            ],
        ];
        $posts = get_posts($args);

        if ( $posts ) {
            foreach ( $posts as $post ) {
                $product = wc_get_product( $post->ID );
                if ( ! $product ) {
                    continue;
                }
                if ( $product->get_image_id() ) {
                    $img_url = wp_get_attachment_url( $product->get_image_id(), 'full' );
                } else {
                    $img_url = wc_placeholder_img_src();
                }
                $suggestions[] = [
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'eng_name' => get_post_meta( $product->get_id(), '_eng_name', true ),
                    'price' => wc_price( $product->get_price() ),
                    'link' => get_permalink( $product->get_id() ),
                    'image' => $img_url,
                ];
            }
        }

        //search by replaces in product
        $args = [
            'posts_per_page' => -1,
            'post_type' => 'product',
            'post_status' => 'publish',
            'suppress_filters' => false,
            'meta_query' => [
                [
                    'key' => 'other_search_names',
                    'value' => $search_txt,
                    'compare' => 'LIKE'
                ]
            ],
            'tax_query' => [
                [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'exclude-from-catalog',
                    'operator' => 'NOT IN',
                ],
            ],
        ];
        $posts = get_posts($args);

        if ( $posts ) {
            foreach ( $posts as $post ) {
                setup_postdata( $post );
                global $product; 
                $GLOBALS['product'] = wc_get_product(get_the_ID()); 
                get_template_part( 'woocommerce/cart/cart-products-search-items' );
            }
            wp_reset_postdata();
        }
        //get synonyms
        $synonyms = get_field('synonyms_for_search', 'options');
        if ( ! empty($synonyms) ) {
            $currentStrings = [];
            $synonymsArray = explode('\n', $synonyms);
            foreach ($synonymsArray as $row) {

                if (empty($row)) {
                    continue;
                }
                $wordsArray = explode(',', $row);
                if ( count($wordsArray) < 2 ) {
                    continue;
                }
                foreach ( $wordsArray as $word ) {
                    if ( stripos(mb_strtolower($search_txt), mb_strtolower($word)) !== false ) {
                        $currentStrings[] = [
                            'word' => $word, 
                            'replaces' => $wordsArray
                        ];
                    }
                }
            }

            $search_txt = mb_strtolower($search_txt);
            foreach ( $currentStrings as $string ) {
                foreach ( $string['replaces'] as $replace ) {
                    if ( mb_strtolower($string['word']) == mb_strtolower($replace) ) {
                        continue;
                    }
                    $newSearchText = str_replace(mb_strtolower($string['word']), $replace, $search_txt);
                    //search by synonym replace
                    $query = new WP_Query([
                        's' => $newSearchText,
                        'post_type' => 'product',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'suppress_filters' => false,
                        'tax_query' => [
                            [
                                'taxonomy' => 'product_visibility',
                                'field'    => 'name',
                                'terms'    => 'exclude-from-catalog',
                                'operator' => 'NOT IN',
                            ],
                        ],
                    ]);

                    if ( $query->have_posts() ) {
                        while ( $query->have_posts() ) {
                            $query->the_post();
                            global $product; 
                            $GLOBALS['product'] = wc_get_product(get_the_ID()); 
                            get_template_part( 'woocommerce/cart/cart-products-search-items' );
                        }
                        wp_reset_postdata();
                    }
                    //search only by synonym
                    $query = new WP_Query([
                        's' => $replace,
                        'post_type' => 'product',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'suppress_filters' => false,
                        'tax_query' => [
                            [
                                'taxonomy' => 'product_visibility',
                                'field'    => 'name',
                                'terms'    => 'exclude-from-catalog',
                                'operator' => 'NOT IN',
                            ],
                        ],
                    ]);

                    if ( $query->have_posts() ) {
                        while ( $query->have_posts() ) {
                            $query->the_post();
                            global $product; 
                            $GLOBALS['product'] = wc_get_product(get_the_ID()); 
                            get_template_part( 'woocommerce/cart/cart-products-search-items' );
                        }
                        wp_reset_postdata();
                    }

                }
            }
        } 

        $products_html = ob_get_clean();

        wp_send_json_success([
            'text_not_found' => __( 'We could not find any results', 'natures-sunshine' ),
            'products' => $products_html,
        ]);
    }

    public function add_to_cart() {

        check_ajax_referer( 'cart_nonce', 'ajax_nonce' );
        $product_id = $_POST['product_id'];
        WC()->cart->add_to_cart( $product_id );

        wp_send_json_success();
    }
    
    public function add_to_cart_partner() {

        check_ajax_referer( 'cart_nonce', 'ajax_nonce' );
        $product_id = $_POST['product_id'];
        $partner_id = $_POST['partner_id'];
        WC()->cart->add_to_cart( $product_id, 1, 0, [], ['partner_id' => $partner_id] );
        wp_send_json_success();
    }

    public function joint_order_mode() {

        check_ajax_referer( 'cart_nonce', 'ajax_nonce' );
        $checked = $_POST['checked'];

        if ( $checked == 'true' ) {
            $value = true;
        } else {
            $value = false;
        }
        WC()->session->set( 'joint_order_mode' , $value );
        wp_send_json_success();
    }
    
    public function exit_join_cart() {

        check_ajax_referer( 'cart_nonce', 'ajax_nonce' );

        WC()->session->set('partner_ids', null);

        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            if ( isset($cart_item['partner_id']) ) {
                WC()->cart->remove_cart_item( $cart_item_key );
            }
        }

        WC()->session->set( 'joint_order_mode' , false );
        wp_send_json_success();
    }

    public function force_individual_cart_items( $cart_item_data, $product_id ){

        if ( isset($cart_item_data['partner_id']) && ! empty($cart_item_data['partner_id']) ) {
            $unique_cart_item_key = $product_id . '_' . $cart_item_data['partner_id'];
  			$cart_item_data['unique_key'] = $unique_cart_item_key;
        }
      
        return $cart_item_data;
      
    } 

    public function check_reg_id() {

        check_ajax_referer( 'cart_nonce', 'ajax_nonce' );
        global $wpdb;    
        $user_id = get_current_user_id();
        $register_id = get_user_meta( $user_id, 'register_id', true );
        $partner_id = $_POST['partner_id'];
        $result_api = false;
        $sponsors_table = $wpdb->prefix . 'sponsor_ids';
        $query = "SELECT * 
                  FROM $sponsors_table
                  WHERE reg_no = $partner_id AND reg_no != $register_id
                 ";

        if ( $partner_id == $register_id ) {
            wp_send_json_error( array(
                'show_modal' => false,
            ) );
        }

        $result = $wpdb->get_results(
            $query, 
            'ARRAY_A'
        );

        if ( empty($result) ) {
            // Reg ID not exist in table
            // return modal for check add action
            wp_send_json_error( array(
                'show_modal' => true,
            ) );
        }

        // next step - add ID
        wp_send_json_success();
    }

    public function add_reg_id() {

        check_ajax_referer( 'cart_nonce', 'ajax_nonce' );
        global $wpdb;    
        $user_id = get_current_user_id();
        $register_id = get_user_meta( $user_id, 'register_id', true );
        $partner_id = $_POST['partner_id'];
        $result_api = false;
        $sponsors_table = $wpdb->prefix . 'sponsor_ids';
        $query = "SELECT * 
                  FROM $sponsors_table
                  WHERE reg_no = $partner_id AND reg_no != $register_id
                 ";
        $result = $wpdb->get_results(
            $query, 
            'ARRAY_A'
        );
        
        if ( empty($result) ) {
            $full_name = '';
        } else {
            $full_name = $result[0]['fio_original'];
        }

        // save partner cart to session
        $partner_ids = WC()->session->get('partner_ids');
        $partner_ids[ $partner_id ] = $full_name;
        WC()->session->set('partner_ids', $partner_ids);

        ob_start();
        get_template_part( 
            'woocommerce/cart/join-cart-partner-item', 
            null, 
            [
                'full_name' => $full_name,
                'partner_id' => $partner_id,
            ] 
        );
        $join_cart_partner_item = ob_get_clean();

        wp_send_json_success( [
            'join_cart_partner_item_html' => $join_cart_partner_item,
            'full_name' => $full_name,
            'partner_id' => $partner_id,
        ] );
    }

    public function remove_partner_cart() {

        check_ajax_referer( 'cart_nonce', 'ajax_nonce' );
        $partner_id = $_POST['partner_id'];
        $partner_ids = WC()->session->get('partner_ids');

        unset( $partner_ids[ $partner_id ]);
        WC()->session->set('partner_ids', $partner_ids);

        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            if ( isset($cart_item['partner_id']) && $cart_item['partner_id'] == $partner_id ) {
                WC()->cart->remove_cart_item( $cart_item_key );
            }
        }

        wp_send_json_success();
    }

    public function get_count_cart() {

        $count = 0;
        $type_cart = WC()->session->get('joint_order_mode');
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

            if ( $type_cart && isset($cart_item['partner_id']) ) {
                continue;
            }
            $count++;
        }

        return $count;
    }

    /**
     * This code will automatically remove any out of stock items from a shopping cart.
     * This would be in cases when users add products to their cart and come back to it later.
     *
     */
    public function check_for_out_of_stock_products() {

        if ( WC()->cart->is_empty() ) {
            return;
        }
        
        $removed_products = [];
        
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $product_obj = $cart_item['data'];
        
            if ( ! $product_obj->is_in_stock() ) {
                WC()->cart->remove_cart_item( $cart_item_key );
                $removed_products[] = $product_obj;
            }
        }
        
        if ( ! empty($removed_products) ) {
            wc_clear_notices(); // remove any WC notice about sorry about out of stock products to be removed from cart.
            foreach ( $removed_products as $idx => $product_obj ) {
                $product_name = $product_obj->get_title();
                $msg = sprintf( __( "The product '%s' was removed from your cart because it is now out of stock. Sorry for any inconvenience caused.", 'woocommerce' ), $product_name);
        
                wc_add_notice( $msg, 'error' );
            }
        }
    
    }
    
} 