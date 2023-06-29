<?php

class UT_Wishlist {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        
        if ( wp_doing_ajax() ) {
            add_action( 'wp_ajax_buy_all_list', [$this, 'buy_all_list'] );
            add_action( 'wp_ajax_nopriv_buy_all_list', [$this, 'buy_all_list'] );
            
            add_action( 'wp_ajax_remove_product_from_wishlist', [$this, 'remove_product_from_wishlist'] );
            add_action( 'wp_ajax_nopriv_remove_product_from_wishlist', [$this, 'remove_product_from_wishlist'] );
            
            add_action( 'wp_ajax_remove_product_from_wishlist_postpone', [$this, 'remove_product_from_wishlist_postpone'] );
            add_action( 'wp_ajax_nopriv_remove_product_from_wishlist_postpone', [$this, 'remove_product_from_wishlist_postpone'] );
            
            add_action( 'wp_ajax_remove_from_wishlist_checkbox', [$this, 'remove_from_wishlist_checkbox'] );
            add_action( 'wp_ajax_nopriv_remove_from_wishlist_checkbox', [$this, 'remove_from_wishlist_checkbox'] );
            
            add_action( 'wp_ajax_remove_wishlist', [$this, 'remove_wishlist'] );
            add_action( 'wp_ajax_nopriv_remove_wishlist', [$this, 'remove_wishlist'] );
            
            add_action( 'wp_ajax_products_move_to_wishlist', [$this, 'products_move_to_wishlist'] );
            add_action( 'wp_ajax_nopriv_products_move_to_wishlist', [$this, 'products_move_to_wishlist'] );
            
            add_action( 'wp_ajax_edit_name_wishlist', [$this, 'edit_name_wishlist'] );
            add_action( 'wp_ajax_nopriv_edit_name_wishlist', [$this, 'edit_name_wishlist'] );
            
            add_action( 'wp_ajax_create_wishlist', [$this, 'create_wishlist'] );
            add_action( 'wp_ajax_nopriv_create_wishlist', [$this, 'create_wishlist'] );
            
            add_action( 'wp_ajax_make_primary_wishlist', [$this, 'make_primary_wishlist'] );
            add_action( 'wp_ajax_nopriv_make_primary_wishlist', [$this, 'make_primary_wishlist'] );
            
            add_action( 'wp_ajax_add_product_to_wishlist', [$this, 'add_product_to_wishlist'] );
            add_action( 'wp_ajax_nopriv_add_product_to_wishlist', [$this, 'add_product_to_wishlist'] );
            
            add_action( 'wp_ajax_add_product_to_wishlist_postpone', [$this, 'add_product_to_wishlist_postpone'] );
            add_action( 'wp_ajax_nopriv_add_product_to_wishlist_postpone', [$this, 'add_product_to_wishlist_postpone'] );
        }

    }

    public function buy_all_list() {

        check_ajax_referer( 'wishlist_nonce', 'ajax_nonce' );
        $product_ids = $_POST['product_ids'];

        foreach ( (array)$product_ids as $product_id ) {
            WC()->cart->add_to_cart( $product_id );
        }

        wp_send_json_success();
    }

    public function remove_product_from_wishlist() {

        check_ajax_referer( 'wishlist_nonce', 'ajax_nonce' );
        $wishlist = false;
        $wishlist_id = $_POST['wishlist_id'];
        $product_id = $_POST['product_id'];

        $wishlist = $this->remove_from_wishlist($product_id, $wishlist_id);

        ob_start();
        get_template_part(
            'template-parts/favorites/icon', 
            null, [
                'product_id' => $product_id,
                'is_product_in_wishlist' => [],
            ]
        ); 
        $icon_html = ob_get_clean();

        ob_start();
        get_template_part( 
            'template-parts/favorites/content', 
            'wishlist', 
            [
                'wishlist' => $wishlist,
                'class_active' => 'active',
            ] 
        );
        $wishlist_html = ob_get_clean();

        wp_send_json_success( [
            'product_id' => $product_id,
            'wishlist_id' => $wishlist->get_id(),
            'icon_html' => $icon_html,
			'wishlist_html' => $wishlist_html,
		] ); 
    }
    
    public function remove_product_from_wishlist_postpone() {

        check_ajax_referer( 'wishlist_nonce', 'ajax_nonce' );
        $wishlist = false;
        $wishlist_id = $_POST['wishlist_id'];
        $product_id = $_POST['product_id'];

        $wishlist = $this->remove_from_wishlist($product_id, $wishlist_id);

        ob_start();
        get_template_part(
            'template-parts/favorites/icon', 
            'postpone', 
            [
                'product_id' => $product_id,
                'is_product_in_wishlist' => [],
            ]
        ); 
        $icon_html = ob_get_clean();

        ob_start();
        get_template_part( 
            'template-parts/favorites/content', 
            'wishlist', 
            [
                'wishlist' => $wishlist,
                'class_active' => 'active',
            ] 
        );
        $wishlist_html = ob_get_clean();

        wp_send_json_success( [
            'product_id' => $product_id,
            'wishlist_id' => $wishlist->get_id(),
            'icon_html' => $icon_html,
			'wishlist_html' => $wishlist_html,
		] ); 
    }

    public function remove_from_wishlist_checkbox() {

        check_ajax_referer( 'wishlist_nonce', 'ajax_nonce' );
        $wishlist = false;
        $wishlist_id = $_POST['wishlist_id'];
        $product_ids = $_POST['product_ids'];

        foreach ( (array)$product_ids as $product_id ) {
            $wishlist = $this->remove_from_wishlist($product_id, $wishlist_id);
        }

        ob_start();
        get_template_part( 
            'template-parts/favorites/content', 
            'wishlist', 
            [
                'wishlist' => $wishlist,
                'class_active' => 'active',
            ] 
        );
        $wishlist_html = ob_get_clean();

        wp_send_json_success( [
            'product_ids' => $product_ids,
			'wishlist_html' => $wishlist_html,
		] ); 
    }

    public function remove_from_wishlist($product_id, $wishlist_id) {

        $atts = [
            'remove_from_wishlist' => $product_id,
            'wishlist_id'          => $wishlist_id,
            'user_id'              => get_current_user_id(),
        ];

        $defaults = array(
            'remove_from_wishlist' => 0,
            'wishlist_id'          => 0,
            'user_id'              => false,
        );

        $atts = empty( $atts ) && ! empty( $this->details ) ? $this->details : $atts;
        $atts = ! empty( $atts ) ? $atts : $_REQUEST; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $atts = wp_parse_args( $atts, $defaults );

        $prod_id     = intval( $atts['remove_from_wishlist'] );
        $wishlist_id = intval( $atts['wishlist_id'] );
        $user_id     = intval( $atts['user_id'] );

        do_action( 'yith_wcwl_removing_from_wishlist', $prod_id, $wishlist_id, $user_id );

        if ( ! $prod_id ) {
            throw new YITH_WCWL_Exception( apply_filters( 'yith_wcwl_unable_to_remove_product_message', __( 'Error. Unable to remove the product from the wishlist.', 'yith-woocommerce-wishlist' ) ), 0 );
        }

        $wishlist_html = '';
        $wishlist = apply_filters( 'yith_wcwl_get_wishlist_on_remove', YITH_WCWL_Wishlist_Factory::get_wishlist( $wishlist_id ), $atts );

        if ( apply_filters( 'yith_wcwl_allow_remove_after_add_to_cart', ! $wishlist instanceof YITH_WCWL_Wishlist || ! $wishlist->current_user_can( 'remove_from_wishlist' ), $wishlist ) ) {
            throw new YITH_WCWL_Exception( apply_filters( 'yith_wcwl_unable_to_remove_product_message', __( 'Error. Unable to remove the product from the wishlist.', 'yith-woocommerce-wishlist' ) ), 0 );
        }

        $wishlist->remove_product( $prod_id );
        $wishlist->save();

        wp_cache_delete( 'wishlist-count-' . $wishlist->get_token(), 'wishlists' );

        $user_id = $wishlist->get_user_id();

        if ( $user_id ) {
            wp_cache_delete( 'wishlist-user-total-count-' . $user_id );
        }

        return $wishlist;
    }
    
    public function remove_wishlist() {

        check_ajax_referer( 'wishlist_nonce', 'ajax_nonce' );
        $wishlist_id = $_POST['wishlist_id'];
        $wishlist = YITH_WCWL()->get_wishlist_detail( $wishlist_id );

        if ( !$wishlist ) {
            wp_send_json_error(); 
        }

        $YITH_WCWL_Wishlist_Data_Store = new YITH_WCWL_Wishlist_Data_Store();
        $YITH_WCWL_Wishlist_Data_Store->delete( $wishlist );

        wp_send_json_success(); 
    }
    
    public function products_move_to_wishlist() {

        check_ajax_referer( 'wishlist_nonce', 'ajax_nonce' );
        $wishlist_id_from = $_POST['wishlist_id_from'];
        $wishlist_id_to = $_POST['wishlist_id_to'];
        $product_ids_str = $_POST['product_ids'];
        $product_ids = explode(',', $product_ids_str);
        $moved = false;

        if ( $wishlist_id_from && $wishlist_id_to && $product_ids ) {

            $origin_wishlist      = YITH_WCWL_Wishlist_Factory::get_wishlist( $wishlist_id_from );
            $destination_wishlist = YITH_WCWL_Wishlist_Factory::get_wishlist( $wishlist_id_to );

            if ( $origin_wishlist && $destination_wishlist ) {

                foreach ( (array)$product_ids as $product_id ) {
                    $item = $origin_wishlist->get_product( $product_id );

                    if ( $item ) {
                        $destination_item = $destination_wishlist->get_product( $product_id );

                        if ( $destination_item ) {
                            $destination_item->set_date_added( current_time( 'mysql' ) );

                            $destination_item->save();
                            $item->delete();
                        } else {
                            $item->set_wishlist_id( $destination_wishlist->get_id() );
                            $item->set_date_added( current_time( 'mysql' ) );

                            $item->save();
                        }

                        $moved = true;
                        wp_cache_delete( 'wishlist-items-' . $origin_wishlist->get_id(), 'wishlists' );
                        wp_cache_delete( 'wishlist-items-' . $destination_wishlist->get_id(), 'wishlists' );

                    }
                }
            }
        }

        if ( $moved ) {

            $origin_wishlist      = YITH_WCWL_Wishlist_Factory::get_wishlist( $wishlist_id_from );
            $destination_wishlist = YITH_WCWL_Wishlist_Factory::get_wishlist( $wishlist_id_to );

            ob_start();
            get_template_part( 
                'template-parts/favorites/content', 
                'wishlist', 
                [
                    'wishlist' => $origin_wishlist,
                    'class_active' => 'active',
                ] 
            );
            $wishlist_from_html = ob_get_clean();

            ob_start();
            get_template_part( 
                'template-parts/favorites/content', 
                'wishlist', 
                [
                    'wishlist' => $destination_wishlist,
                    'class_active' => 'active',
                ] 
            );
            $wishlist_from_to = ob_get_clean();

            wp_send_json_success( [
                'wishlist_from_html' => $wishlist_from_html,
                'wishlist_from_to' => $wishlist_from_to,
            ] ); 
        }

        wp_send_json_success(); 
    }
    
    public function edit_name_wishlist() {

        check_ajax_referer( 'wishlist_nonce', 'ajax_nonce' );
        $wishlist_id = $_POST['wishlist_id'];
        $wishlist_name = $_POST['name'];

        $wishlist = $wishlist_id ? yith_wcwl_get_wishlist( $wishlist_id ) : false;

        if ( ! $wishlist_id || ! $wishlist ) {
            wp_send_json_error();
        }

        $wishlist->set_name( $wishlist_name );
        $wishlist->save();

        wp_send_json_success(); 
    }
    
    public function create_wishlist() {

        check_ajax_referer( 'wishlist_nonce', 'ajax_nonce' );
        $wishlist_name = $_POST['name'];
        $primary = $_POST['primary'];

        $YITH_WCWL_Session = new YITH_WCWL_Session();
        $YITH_WCWL_Premium = new YITH_WCWL_Premium();
        $atts = [
            'wishlist_name' => $wishlist_name,
            'wishlist_visibility' => 1,
            'user_id' => get_current_user_id(),
            'session_id' => $YITH_WCWL_Session->get_session_id(),
        ];
        $wishlist = $YITH_WCWL_Premium->add_wishlist( $atts );
        // reset default for all user wishlists and set default current wishlist
        if ( $primary == 'true' && $wishlist && is_user_logged_in() ) {
            $user_wishlists = YITH_WCWL()->get_current_user_wishlists();
            foreach ( $user_wishlists as $user_wishlist ) {
                $user_wishlist->set_is_default( false );
                $user_wishlist->save();
            }
            $wishlist->set_is_default( true );
            $wishlist->save();
        }

        if ( $wishlist ) {
            ob_start();
            get_template_part( 
                'template-parts/favorites/content', 
                'wishlist', 
                [
                    'wishlist' => $wishlist,
                    'class_active' => 'active',
                ] 
            );
            $wishlist_html = ob_get_clean();

            wp_send_json_success( [
                'wishlist_id' => $wishlist->get_id(),
                'wishlist_name' => $wishlist->get_formatted_name(),
                'wishlist_html' => $wishlist_html,
            ] ); 
        }

        wp_send_json_error(); 
    }

    public static function create_first_wishlist() {

        $wishlist_name = __('My Wishlist', 'natures-sunshine');

        $YITH_WCWL_Session = new YITH_WCWL_Session();
        $YITH_WCWL_Premium = new YITH_WCWL_Premium();
        $atts = [
            'wishlist_name' => $wishlist_name,
            'wishlist_visibility' => 1,
            'user_id' => false,
            'session_id' => $YITH_WCWL_Session->get_session_id(),
        ];
        $wishlist = $YITH_WCWL_Premium->add_wishlist( $atts );

        return $wishlist; 
    }
    
    public function make_primary_wishlist() {

        check_ajax_referer( 'wishlist_nonce', 'ajax_nonce' );
        $YITH_WCWL_Wishlist_Factory = new YITH_WCWL_Wishlist_Factory();
        $new_primary_wishlist_id = $_POST['new_wishlist_id'];
        $old_primary_wishlist_id = null;
        $new_wishlist = YITH_WCWL()->get_wishlist_detail( $new_primary_wishlist_id );
        $old_wishlist = $YITH_WCWL_Wishlist_Factory->get_default_wishlist();

        // reset default for all user wishlists and set default current wishlist
        if ( $new_wishlist ) {
            $new_primary_wishlist_id = $new_wishlist->get_id();
            $new_wishlist->set_is_default( true );
            $new_wishlist->save();
        }
        
        if ( $old_wishlist ) {
            $old_primary_wishlist_id = $old_wishlist->get_id();
            $old_wishlist->set_is_default( false );
            $old_wishlist->save();
        }

        if ( $new_wishlist && $old_wishlist ) {

            ob_start();
            get_template_part( 
                'template-parts/favorites/content', 
                'wishlist', 
                [
                    'wishlist' => $old_wishlist,
                    'class_active' => 'active',
                ] 
            );
            $wishlist_old_primary_html = ob_get_clean();
            
            ob_start();
            get_template_part( 
                'template-parts/favorites/content', 
                'wishlist', 
                [
                    'wishlist' => $new_wishlist,
                    'class_active' => 'active',
                ] 
            );
            $wishlist_new_primary_html = ob_get_clean();

            wp_send_json_success( [
                'wishlist_id_old_primary' => $old_primary_wishlist_id,
                'wishlist_id_new_primary' => $new_primary_wishlist_id,
                'wishlist_old_primary_html' => $wishlist_old_primary_html,
                'wishlist_new_primary_html' => $wishlist_new_primary_html,
            ] ); 
        }

        wp_send_json_error(); 
    }
    
    public function add_product_to_wishlist() {

        check_ajax_referer( 'wishlist_nonce', 'ajax_nonce' );
        $product_id = $_POST['product_id'];
        $wishlist_page = $_POST['wishlist_page'];

        $YITH_WCWL_Wishlist_Factory = new YITH_WCWL_Wishlist_Factory();
        $wishlist = $YITH_WCWL_Wishlist_Factory->get_default_wishlist( wp_get_current_user() );
        // if not exist wishlist - then create wishlsit automatical
        if ( !$wishlist && !is_user_logged_in() ) {
            $wishlist = $this::create_first_wishlist();
            $first_wishlist = true;
        }
        
        if ( !$wishlist ) {
            wp_send_json_error(); 
        }

        $wishlist->add_product( $product_id );
        $wishlist->save();

        ob_start();
        get_template_part(
            'template-parts/favorites/icon', 
            null, [
                'product_id' => $product_id,
                'is_product_in_wishlist' => [ 
                    'wishlist_id' => $wishlist->get_id() 
                ],
            ]
        ); 
        $icon_html = ob_get_clean();

        if ( $wishlist_page == 'true' ) {

            ob_start();
            get_template_part( 
                'template-parts/favorites/content', 
                'wishlist', 
                [
                    'wishlist' => $wishlist,
                    'class_active' => 'active',
                ] 
            );
            $wishlist_html = ob_get_clean();

            wp_send_json_success( [
                'icon_html' => $icon_html,
                'wishlist_html' => $wishlist_html,
                'wishlist_id' => $wishlist->get_id(),
            ] ); 
        }

        wp_send_json_success( [
            'icon_html' => $icon_html,
        ] ); 
    }
    
    public function add_product_to_wishlist_postpone() {

        check_ajax_referer( 'wishlist_nonce', 'ajax_nonce' );
        $product_id = $_POST['product_id'];
        $wishlist_page = $_POST['wishlist_page'];

        $YITH_WCWL_Wishlist_Factory = new YITH_WCWL_Wishlist_Factory();
        $wishlist = $YITH_WCWL_Wishlist_Factory->get_default_wishlist( wp_get_current_user() );
        // if not exist wishlist - then create wishlsit automatical
        if ( !$wishlist && !is_user_logged_in() ) {
            $wishlist = $this::create_first_wishlist();
            $first_wishlist = true;
        }
        
        if ( !$wishlist ) {
            wp_send_json_error(); 
        }

        $wishlist->add_product( $product_id );
        $wishlist->save();

        //
        $product_cart_id = WC()->cart->generate_cart_id( $product_id );
        $cart_item_key = WC()->cart->find_product_in_cart( $product_cart_id );
        
        if ( $cart_item_key ) {
            WC()->cart->remove_cart_item( $cart_item_key );
        }
        //

        ob_start();
        get_template_part(
            'template-parts/favorites/icon', 
            'postpone', 
            [
                'product_id' => $product_id,
                'is_product_in_wishlist' => $wishlist->get_id(),
            ]
        ); 
        $icon_html = ob_get_clean();

        if ( $wishlist_page == 'true' ) {

            ob_start();
            get_template_part( 
                'template-parts/favorites/content', 
                'wishlist', 
                [
                    'wishlist' => $wishlist,
                    'class_active' => 'active',
                ] 
            );
            $wishlist_html = ob_get_clean();

            wp_send_json_success( [
                'icon_html' => $icon_html,
                'wishlist_html' => $wishlist_html,
                'wishlist_id' => $wishlist->get_id(),
            ] ); 
        }

        wp_send_json_success( [
            'icon_html' => $icon_html,
        ] ); 
    }

    public function is_product_in_wishlist( $product_id ) {

        $result = [];
        $user_wishlists = YITH_WCWL()->get_current_user_wishlists();
        foreach ( $user_wishlists as $user_wishlist ) {
            
            if ( $user_wishlist->has_product( $product_id ) ) {
                $result = [
                    'wishlist_id' => $user_wishlist->get_id(),
                ];
                break;
            }
        }

        return $result;
    }
    
} 