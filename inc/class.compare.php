<?php

class UT_Compare {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        
        if ( wp_doing_ajax() ) {
            add_action( 'wp_ajax_add_product_to_compare', [$this, 'add_product_to_compare'] );
            add_action( 'wp_ajax_nopriv_add_product_to_compare', [$this, 'add_product_to_compare'] );
            
            add_action( 'wp_ajax_remove_product_from_compare', [$this, 'remove_product_from_compare'] );
            add_action( 'wp_ajax_nopriv_remove_product_from_compare', [$this, 'remove_product_from_compare'] );
            
            add_action( 'wp_ajax_remove_all_compare', [$this, 'remove_all_compare'] );
            add_action( 'wp_ajax_nopriv_remove_all_compare', [$this, 'remove_all_compare'] );
        }

    }

    public function add_product_to_compare() {

        check_ajax_referer( 'compare_nonce', 'ajax_nonce' );
        require_once get_home_path() . 'wp-content/plugins/yith-woocommerce-compare/includes/class.yith-woocompare-frontend.php';

        $product_id = absint( $_POST['product_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $compare_page = $_POST['compare_page']; 
        $product = wc_get_product( $product_id );
        $products_list = $this->products_list();
        $YITH_Woocompare_Frontend = new YITH_Woocompare_Frontend();
        // Don't add the product if doesn't exist.
        if ( !$product || in_array( $product_id, $products_list ) ) {
            wp_send_json_error();
        }

        $products_list[] = absint( $product_id );
        setcookie( $YITH_Woocompare_Frontend->cookie_name, wp_json_encode( $products_list ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );

        ob_start();
        get_template_part(
            'template-parts/compare/icon', 
            null, 
            [
                'product_id' => $product_id,
                'products_list' => $products_list,
            ]
        ); 
        $icon_html = ob_get_clean(); 

        ob_start();
        get_template_part('template-parts/header/bar', 'compare', ['products_list' => $products_list]);
        $bar_html = ob_get_clean();

        if ( $compare_page == 'true' ) {
            
            foreach ( $products_list as $k => $id ) {
                $product = wc_get_product( $id );
                
                if ( $product && $product->exists() ) {
                    $products[] = $product;
                }
            }

            ob_start();
            get_template_part( 'template-parts/compare/header', null, ['products' => $products] );
            $header_html = ob_get_clean();
            
            ob_start();
            get_template_part( 'template-parts/compare/sticky', null, ['products' => $products] );
            $sticky_html = ob_get_clean();
            
            ob_start();
            get_template_part( 'template-parts/compare/content', null, ['products' => $products] );
            $content_html = ob_get_clean();

            wp_send_json_success( [
                'icon_html' => $icon_html,
                'bar_html' => $bar_html,
                'header_html' => $header_html,
                'sticky_html' => $sticky_html,
                'content_html' => $content_html,
            ] ); 
        }

        wp_send_json_success( [
            'icon_html' => $icon_html,
            'bar_html' => $bar_html,
        ] ); 
    }

    public function remove_product_from_compare() {

        check_ajax_referer( 'compare_nonce', 'ajax_nonce' );
        require_once get_home_path() . 'wp-content/plugins/yith-woocommerce-compare/includes/class.yith-woocompare-frontend.php';

        $products = [];
        $product_id = absint( $_POST['product_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $products_list = $this->products_list();
        $YITH_Woocompare_Frontend = new YITH_Woocompare_Frontend();

        foreach ( $products_list as $k => $id ) {

            if ( absint( $product_id ) === absint( $id ) ) {
                unset( $products_list[ $k ] );
            } else {
                $product = wc_get_product( $id );
                
                if ( $product && $product->exists() ) {
                    $products[] = $product;
                }
            }
        }

        setcookie( $YITH_Woocompare_Frontend->cookie_name, wp_json_encode( array_values( $products_list ) ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );

        ob_start();
        get_template_part(
            'template-parts/compare/icon', 
            null, 
            [
                'product_id' => $product_id,
                'products_list' => $products_list,
            ]
        ); 
        $icon_html = ob_get_clean();
        
        ob_start();
        get_template_part('template-parts/header/bar', 'compare', ['products_list' => $products_list]);
        $bar_html = ob_get_clean();
        
        ob_start();
        get_template_part( 'template-parts/compare/header', null, ['products' => $products] );
        $header_html = ob_get_clean();
        
        ob_start();
        get_template_part( 'template-parts/compare/sticky', null, ['products' => $products] );
        $sticky_html = ob_get_clean();
        
        ob_start();
        get_template_part( 'template-parts/compare/content', null, ['products' => $products] );
        $content_html = ob_get_clean();

        wp_send_json_success( [
            'product_id' => $product_id,
            'icon_html' => $icon_html,
            'bar_html' => $bar_html,
            'header_html' => $header_html,
            'sticky_html' => $sticky_html,
            'content_html' => $content_html,
        ] ); 
    }

    public function remove_all_compare() {

        check_ajax_referer( 'compare_nonce', 'ajax_nonce' );
        require_once get_home_path() . 'wp-content/plugins/yith-woocommerce-compare/includes/class.yith-woocompare-frontend.php';

        $products = [];
        $product_id = absint( $_POST['product_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $products_list = $this->products_list();
        $YITH_Woocompare_Frontend = new YITH_Woocompare_Frontend();
        $products_list = [];

        setcookie( $YITH_Woocompare_Frontend->cookie_name, wp_json_encode( array_values( $products_list ) ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
        
        ob_start();
        get_template_part('template-parts/header/bar', 'compare', ['products_list' => $products_list]);
        $bar_html = ob_get_clean();
        
        ob_start();
        get_template_part( 'template-parts/compare/header', null, ['products' => $products] );
        $header_html = ob_get_clean();
        
        ob_start();
        get_template_part( 'template-parts/compare/sticky', null, ['products' => $products] );
        $sticky_html = ob_get_clean();
        
        ob_start();
        get_template_part( 'template-parts/compare/content', null, ['products' => $products] );
        $content_html = ob_get_clean();

        wp_send_json_success( [
            'bar_html' => $bar_html,
            'header_html' => $header_html,
            'sticky_html' => $sticky_html,
            'content_html' => $content_html,
        ] ); 
    }

    public function products_list() {

        global $sitepress;

        require_once get_home_path() . 'wp-content/plugins/yith-woocommerce-compare/includes/class.yith-woocompare-frontend.php';

        $products_list = [];
        $YITH_Woocompare_Frontend = new YITH_Woocompare_Frontend();

        // WPML Support.
        $lang = isset( $_REQUEST['lang'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['lang'] ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

        // Get cookie val.
        $the_list = isset( $_COOKIE[ $YITH_Woocompare_Frontend->cookie_name ] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE[ $YITH_Woocompare_Frontend->cookie_name ] ) ) ) : array();

        // Switch lang for WPML.
        if ( defined( 'ICL_LANGUAGE_CODE' ) && $lang && isset( $sitepress ) ) {
            $sitepress->switch_lang( $lang, true );
        }

        foreach ( $the_list as $product_id ) {

            if ( function_exists( 'wpml_object_id_filter' ) ) {
                $product_id_translated = wpml_object_id_filter( $product_id, 'product', false );
                // Get all product of current lang.
                if ( $product_id_translated !== $product_id ) {
                    continue;
                }
            }

            // Check for deleted|private products.
            $product = wc_get_product( $product_id );
            if ( ! $product || 'publish' !== $product->get_status() ) {
                continue;
            }

            $products_list[] = absint( $product_id );
        }

        return $products_list;
    }
    
} 