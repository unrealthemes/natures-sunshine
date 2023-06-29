<?php

class UT_Address {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        if ( wp_doing_ajax() ) {
            add_action( 'wp_ajax_set_default_address', [$this, 'set_default_address'] );
            add_action( 'wp_ajax_nopriv_set_default_address', [$this, 'set_default_address'] );
            
            add_action( 'wp_ajax_add_address', [$this, 'add_address'] );
            add_action( 'wp_ajax_nopriv_add_address', [$this, 'add_address'] );
            
            add_action( 'wp_ajax_remove_address', [$this, 'remove_address'] );
            add_action( 'wp_ajax_nopriv_remove_address', [$this, 'remove_address'] );
            
            add_action( 'wp_ajax_autocomplete_cities', [$this, 'autocomplete_cities'] );
            add_action( 'wp_ajax_nopriv_autocomplete_cities', [$this, 'autocomplete_cities'] );
            
            add_action( 'wp_ajax_get_another_cities_code', [$this, 'get_another_cities_code'] );
            add_action( 'wp_ajax_nopriv_get_another_cities_code', [$this, 'get_another_cities_code'] );
            
            add_action( 'wp_ajax_get_warehouse_shipping', [$this, 'get_warehouse_shipping'] );
            add_action( 'wp_ajax_nopriv_get_warehouse_shipping', [$this, 'get_warehouse_shipping'] );
            
            add_action( 'wp_ajax_get_warehouses_for_all_shipping_methods', [$this, 'get_warehouses_for_all_shipping_methods'] );
            add_action( 'wp_ajax_nopriv_get_warehouses_for_all_shipping_methods', [$this, 'get_warehouses_for_all_shipping_methods'] );
        }

        add_action( 'init', [$this, 'create_post_type'] );
        add_action( 'init', [$this, 'register_taxonomy'], 0 );

    }

    public function set_default_address() {

        check_ajax_referer( 'address_nonce', 'ajax_nonce' );
        $user_id = get_current_user_id();
        $taxonomy = 'type';
        $post_id = $_POST['id'];
        $name = $_POST['name'];
        $args = [ 
            'author' =>  $user_id,
            'post_type' => 'address', 
            'post_status' => 'publish', 
            'posts_per_page' => -1, 
        ];
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) { $query->the_post();
                update_field( 'default_addr', false, get_the_ID() );
            }
        }
        wp_reset_postdata();
        update_field( 'default_addr', true, $post_id );
        wp_send_json_success();
    }
    
    public function remove_address() {

        check_ajax_referer( 'address_nonce', 'ajax_nonce' );
        $default_address = get_field('default_addr', $_POST['id']);
        wp_delete_post( $_POST['id'], true );
        
        // set default true first address
        if ( $default_address ) {
            $user_id = get_current_user_id();
            $args = [ 
                'author' =>  $user_id,
                'post_type' => 'address', 
                'post_status' => 'publish', 
                'posts_per_page' => -1, 
            ];
            $query = new WP_Query( $args );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) { $query->the_post();
                    update_field( 'default_addr', true, get_the_ID() ); 
                    break;
                }
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success();
    }

    public function add_address() {

        check_ajax_referer( 'address_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        if ( empty( $form['billing_city'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'billing_city',
                'message' => __('Field City is required', 'natures-sunshine'),
            ) );
        } 
        
        if ( $form['type'] == 'delivery' ) {

            if ( empty( $form['billing_address_1'] ) ) {
                wp_send_json_error( array(
                    'name_field' => 'billing_address_1',
                    'message' => __('Field Street is required', 'natures-sunshine'),
                ) );
            } 

            if ( empty( $form['billing_address_2'] ) ) {
                wp_send_json_error( array(
                    'name_field' => 'billing_address_2',
                    'message' => __('Field House is required', 'natures-sunshine'),
                ) );
            } 
            
        } else if ( $form['type'] == 'pickup' ) {
            
            $shipping_method = preg_replace("/[^A-Za-z_ ]/", '', $form['shipping_method']);

            if ( $shipping_method == 'nova_poshta_shipping_method' && empty($form['nova_poshta_warehouse']) ) {
                
                wp_send_json_error( array(
                    'name_field' => 'nova_poshta_warehouse',
                    'message' => __('<b>Warehouse select field</b> is a required', 'natures-sunshine'),
                ) );

            } else if ( $shipping_method == 'nova_poshta_shipping_method_poshtomat' && empty($form['nova_poshta_poshtomat']) ) {
                
                wp_send_json_error( array(
                    'name_field' => 'nova_poshta_poshtomat',
                    'message' => __('<b>Poshtomat select field</b> is a required', 'natures-sunshine'),
                ) );

            } else if ( $shipping_method == 'ukrposhta_shippping' && empty($form['ukr_warehouses']) ) {
                
                wp_send_json_error( array(
                    'name_field' => 'ukr_warehouses',
                    'message' => __('<b>Warehouse select field</b> is a required', 'natures-sunshine'),
                ) );
            }
        }

        if ( empty( $form['billing_first_name'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'billing_first_name',
                'message' => __('Field First name is required', 'natures-sunshine'),
            ) );
        } 
        
        if ( empty( $form['billing_last_name'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'billing_last_name',
                'message' => __('Field Last name is required', 'natures-sunshine'),
            ) );
        } 

        if ( empty( $form['patronymic'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'patronymic',
                'message' => __('Field Middle name is required', 'natures-sunshine'),
            ) );
        } 
        
        if ( empty( $form['billing_email'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'billing_email',
                'message' => __('Field Email is required', 'natures-sunshine'),
            ) );
        } 
        
        if ( empty( $form['billing_phone'] ) ) {
            wp_send_json_error( array(
                'name_field' => 'billing_phone',
                'message' => __('Field Phone is required', 'natures-sunshine'),
            ) );
        } 

        $set_default = true;
        $taxonomy = 'type';
        $addresses_html = '';
        $user_id = get_current_user_id();
        $first_name = ucfirst( $form['billing_first_name'] );
        $last_name = ucfirst( $form['billing_last_name'] );
        $middle_name = ucfirst( $form['patronymic'] );
        $letter_first_name = mb_substr( $first_name, 0, 1 );
        $letter_middle_name = mb_substr( $middle_name, 0, 1 );
        $full_name = $last_name . ' ' . $letter_first_name . '. ' . $letter_middle_name . '.';
        $post_data = [
            'post_title' => sanitize_text_field( $full_name ),
            'post_status' => 'publish',
            'post_type' => 'address', 
            'author' => $user_id,
        ];

        if ( empty($form['address_id']) ) {
            $post_id = wp_insert_post( $post_data );
        } else {
            $post_data['ID'] = (int)$form['address_id'];
            $post_id = wp_update_post( $post_data );
        }

        

        if ( is_wp_error($post_id) ) {
            wp_send_json_error( array(
                'message' => $post_id->get_error_message(),
            ) );
        }

        update_post_meta( $post_id, 'nova_poshta_city_code_addr', $form['nova_poshta_city_code'] );
        // update_post_meta( $post_id, 'justin_city_code_addr', $form['justin_city_code'] );
        update_post_meta( $post_id, 'ukr_city_code_addr', $form['ukr_city_code'] );
        update_post_meta( $post_id, 'main_warehouse_code', $form['main_warehouse_code'] );
        update_field( 'first_name_addr', $first_name, $post_id );
        update_field( 'last_name_addr', $last_name, $post_id );
        update_field( 'middle_name_addr', $middle_name, $post_id );
        update_field( 'city_addr', $form['billing_city'], $post_id );
        update_field( 'code_city_addr', $form['address_city_code'], $post_id );
        update_field( 'method_addr', $form['shipping_method'], $post_id );
        // update_field( 'method_addr', $form['pickup'], $post_id );
        // update_field( 'method_addr', $form['delivery'], $post_id );
        update_field( 'street_addr', $form['billing_address_1'], $post_id );
        update_field( 'house_addr', $form['billing_address_2'], $post_id );
        update_field( 'flat_addr', $form['billing_address_3'], $post_id );
        update_field( 'email_addr', $form['billing_email'], $post_id );
        update_field( 'phone_addr', $form['billing_phone'], $post_id );

        wp_set_object_terms( $post_id, $form['type'], 'type', false );

        $query = new WP_Query( [ 
            'author' =>  $user_id,
            'post_type' => 'address', 
            'post_status' => 'publish', 
            'posts_per_page' => -1, 
            'tax_query' => [
                [
                    'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => $form['type']
                ]
            ]
        ] ); 

        $posts = $query->posts;
        foreach( (array)$posts as $post ) {
            if ( get_field('default_addr', $post->ID) ) {
                $set_default = false;
                break;
            }
        }

        if ( $set_default ) {   
            update_field( 'default_addr', true, $post_id );
        }

        ob_start();
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) { $query->the_post(); 
                get_template_part( 'template-parts/profile/addresses/content', 'address', ['type' => $form['type']] ); 
            } 
        }
        $addresses_html = ob_get_clean();
        wp_reset_postdata(); 

        wp_send_json_success( [
            'type' => $form['type'],
            'addresses_html' => $addresses_html,
        ] ); 
    }

    public function save_address_after_create_order($order) {

        if ( ! is_user_logged_in() ) {
            return false;
        }

        if ( ! $order ) {
            return false;
        }

        $save = false;
        $user_id = get_current_user_id();
        $args = [
            'author' =>  $user_id,
            'post_type' => 'address', 
            'post_status' => 'publish', 
            'posts_per_page' => -1, 
        ]; 
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) { 
                $query->the_post();
                $city = get_field('city_addr', get_the_ID());
                $street = get_field('street_addr', get_the_ID());
                $house = get_field('house_addr', get_the_ID());
                $flat = get_field('flat_addr', get_the_ID());
                $method = get_field('method_addr', get_the_ID());
                $first_name = get_field('first_name_addr', get_the_ID());
                $middle_name = get_field('middle_name_addr', get_the_ID());
                $last_name = get_field('last_name_addr', get_the_ID());
                $email = get_field('email_addr', get_the_ID());
                $phone = get_field('phone_addr', get_the_ID());

                if ( 
                    $_POST['billing_city'] == $city && 
                    $_POST['billing_address_1'] == $street && 
                    $_POST['billing_address_2'] == $house && 
                    $_POST['billing_address_3'] == $flat && 
                    $_POST['shipping_method'][0] == $method && 
                    $_POST['billing_first_name'] == $first_name && 
                    $_POST['patronymic'] == $middle_name && 
                    $_POST['billing_last_name'] == $last_name && 
                    $_POST['billing_email'] == $email && 
                    $_POST['billing_phone'] == $phone 
                ) {
                    $save = false;
                    break;
                } else {
                    $save = true;
                }
            }
        } else {
            $save = true;
        }
        wp_reset_query();

        if ( $save ) {
            // add new address
            $this->add_address_handler();
        }
    }

    public function add_address_handler() {

        $taxonomy = 'type';
        $user_id = get_current_user_id();
        $first_name = ucfirst( $_POST['billing_first_name'] );
        $last_name = ucfirst( $_POST['billing_last_name'] );
        $middle_name = ucfirst( $_POST['patronymic'] );
        $letter_first_name = mb_substr( $first_name, 0, 1 );
        $letter_middle_name = mb_substr( $middle_name, 0, 1 );
        $full_name = $last_name . ' ' . $letter_first_name . '. ' . $letter_middle_name . '.';
        $post_data = [
            'post_title' => sanitize_text_field( $full_name ),
            'post_status' => 'publish',
            'post_type' => 'address', 
            'author' => $user_id,
        ];
        $post_id = wp_insert_post( $post_data );

        if ( is_wp_error($post_id) ) {
            return false;
        }

        update_post_meta( $post_id, 'nova_poshta_city_code_addr', $_POST['nova_poshta_city_code'] );
        update_post_meta( $post_id, 'ukr_city_code_addr', $_POST['ukr_city_code'] );
        update_post_meta( $post_id, 'main_warehouse_code', $_POST['main_warehouse_code'] );
        update_field( 'first_name_addr', $first_name, $post_id );
        update_field( 'last_name_addr', $last_name, $post_id );
        update_field( 'middle_name_addr', $middle_name, $post_id );
        update_field( 'city_addr', $_POST['billing_city'], $post_id );
        // update_field( 'code_city_addr', $_POST['address_city_code'], $post_id );
        update_field( 'method_addr', $_POST['shipping_method'][0], $post_id );
        update_field( 'street_addr', $_POST['billing_address_1'], $post_id );
        update_field( 'house_addr', $_POST['billing_address_2'], $post_id );
        update_field( 'flat_addr', $_POST['billing_address_3'], $post_id );
        update_field( 'email_addr', $_POST['billing_email'], $post_id );
        update_field( 'phone_addr', $_POST['billing_phone'], $post_id );

        $args = [ 
            'author' =>  $user_id,
            'post_type' => 'address', 
            'post_status' => 'publish', 
            'posts_per_page' => -1, 
        ];
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) { $query->the_post();
                update_field( 'default_addr', false, get_the_ID() );
            }
        }
        wp_reset_postdata();

        update_field( 'default_addr', true, $post_id );

        wp_set_object_terms( $post_id, $_POST['type'], 'type', false ); 

        return $post_id;
    }

    public function create_post_type() {
    
        register_post_type( 
            'address',
            [
                'labels' => [
                    'name' => __('Addresses', 'natures-sunshine'),
                    'singular_name' => __('Address', 'natures-sunshine')
                ],
                'public' => true,
                'has_archive' => true,
                'rewrite' => ['slug' => 'address'],
                'show_in_rest' => true,
                'supports' => ['title', 'author', 'custom-fields'],

            ]
        );
    }

    public function register_taxonomy()  {
        
        $labels = array(
            'name'                     => 'Тип', 
            'singular_name'            => 'Тип', 
            'menu_name'                => 'Тип', 
            'all_items'                => 'Все Тип',
            'edit_item'                => 'Изменить Тип',
            'view_item'                => 'Просмотр Тип', 
            'update_item'              => 'Обновить Тип',
            'add_new_item'             => 'Добавить новую Тип',
            'new_item_name'            => 'Название новой Тип',
            'parent_item'              => 'Родительская Тип', 
            'parent_item_colon'        => 'Родительская Тип:',
            'search_items'             => 'Искать Тип',
            'popular_items'            => 'Популярные Тип', 
            'separate_items_with_commas' => 'Разделяйте Тип запятыми',
            'add_or_remove_items'      => 'Добавить или удалить Тип',
            'choose_from_most_used'    => 'Выбрать из часто используемых Типов',
            'not_found'                => 'Типов здоровья не найдено',
            'back_to_items'            => '← Назад к Типам',
        );
        $args = [
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        ];

        register_taxonomy( 'type', 'address', $args );
    }

    public function generate_address( $city, $street, $house, $flat ) {

        $text = '';

        if ( $city ) {
            $text .= $city;
        }
        
        if ( $street ) {
            $text .= ', ' . $street;
        }
        
        if ( $house ) {
            $text .= ', д.' . $house;
        }
       
        if ( $flat ) {
            $text .= ', кв.' . $flat;
        }

        return $text;
    }

    public function get_all_shipping_zones() {

        $data_store = WC_Data_Store::load( 'shipping-zone' );
        $raw_zones = $data_store->get_zones();
        foreach ( $raw_zones as $raw_zone ) {
           $zones[] = new WC_Shipping_Zone( $raw_zone );
        }
        $zones[] = new WC_Shipping_Zone( 0 ); // ADD ZONE "0" MANUALLY

        return $zones;
    }

    function cart_totals_shipping_html( $type ) {

        $packages = WC()->shipping()->get_packages();
        $first    = true;
    
        foreach ( $packages as $i => $package ) {
            $chosen_method = isset( WC()->session->chosen_shipping_methods[ $i ] ) ? WC()->session->chosen_shipping_methods[ $i ] : '';
            $product_names = [];
    
            if ( count( $packages ) > 1 ) {
                foreach ( $package['contents'] as $item_id => $values ) {
                    $product_names[ $item_id ] = $values['data']->get_name() . ' &times;' . $values['quantity'];
                }
                $product_names = apply_filters( 'woocommerce_shipping_package_details_array', $product_names, $package );
            }
    
            wc_get_template(
                'cart/cart-shipping.php',
                [
                    'package'                  => $package,
                    'available_methods'        => $package['rates'],
                    'show_package_details'     => count( $packages ) > 1,
                    'show_shipping_calculator' => is_cart() && apply_filters( 'woocommerce_shipping_show_shipping_calculator', $first, $i, $package ),
                    'package_details'          => implode( ', ', $product_names ),
                    'package_name'             => apply_filters( 'woocommerce_shipping_package_name', ( ( $i + 1 ) > 1 ) ? sprintf( _x( 'Shipping %d', 'shipping packages', 'woocommerce' ), ( $i + 1 ) ) : _x( 'Shipping', 'shipping packages', 'woocommerce' ), $i, $package ),
                    'index'                    => $i,
                    'chosen_method'            => $chosen_method,
                    'formatted_destination'    => WC()->countries->get_formatted_address( $package['destination'], ', ' ),
                    'has_calculated_shipping'  => WC()->customer->has_calculated_shipping(),
                    'type' => $type,
                ]
            );
    
            $first = false;
        }
    }

    public function autocomplete_cities() {

        check_ajax_referer( 'autocomplete_search_nonce', 'ajax_nonce' );

        if ( ! isset($_REQUEST['search_txt']) && empty($_REQUEST['search_txt']) ) {
            echo json_encode( [] );
        }
        
        if ( ! isset($_REQUEST['shipping_method']) && empty($_REQUEST['shipping_method']) ) {
            echo json_encode( [] );
        }

        $cities_html = '';
        $search_txt = $_REQUEST['search_txt'];
        $shipping_method = $_REQUEST['shipping_method'];
        $current_lang = apply_filters( 'wpml_current_language', null );

        if ( $shipping_method == 'nova_poshta_poshtomats' || $shipping_method == 'nova_poshta_warehouses' ) {
            $cities = $this->get_np_cities( $search_txt, $current_lang );
        } /*else if ( $shipping_method == 'justin_warehouses' ) {
            $cities = $this->get_justin_cities( $search_txt, $current_lang );
        }*/ else if ( $shipping_method == 'ukr_warehouses' ) {
            $cities = $this->get_ukr_cities( $search_txt, $current_lang ); 
        }
        
        ob_start();
        foreach ( (array)$cities as $city ) {
            if ( $shipping_method == 'ukr_warehouses' ) {
                echo '<li data-city-code="' . $city['code'] . '" data-city="' . $city['city'] . '">'. $city['name'] .'</li>';
            } else {
                echo '<li data-city-code="' . $city['code'] . '">'. $city['name'] .'</li>';
            }
        }
        $cities_html = ob_get_clean();

        wp_send_json_success( [
            'cities_html' => $cities_html,
        ] ); 
    }

    public function get_another_cities_code() {

        check_ajax_referer( 'autocomplete_search_nonce', 'ajax_nonce' );

        if ( ! isset($_REQUEST['city']) && empty($_REQUEST['city']) ) {
            echo json_encode( [] );
        }
        
        if ( ! isset($_REQUEST['shipping_method']) && empty($_REQUEST['shipping_method']) ) {
            echo json_encode( [] );
        }

        $city = $_REQUEST['city'];
        $shipping_method = $_REQUEST['shipping_method'];
        $current_lang = apply_filters( 'wpml_current_language', null );

        if ( $shipping_method == 'nova_poshta_poshtomats' || $shipping_method == 'nova_poshta_warehouses' ) {
            $cities = $this->get_np_cities( $city, $current_lang );
        } /*else if ( $shipping_method == 'justin_warehouses' ) {
            $cities = $this->get_justin_cities( $city, $current_lang );
        }*/ else if ( $shipping_method == 'ukr_warehouses' ) {
            $cities = $this->get_ukr_cities( $city, $current_lang ); 
        }
        
        if ( isset($cities[0]['code']) ) {
            $response = [
                'city_code' => $cities[0]['code'],
                'shipping_method' => $shipping_method,
            ];
    
            if ( $shipping_method == 'ukr_warehouses' ) {
                $response['city'] = $cities[0]['city'];
            }
            wp_send_json_success( $response );
        } 

        wp_send_json_error();
    }

    function get_np_cities( $search_txt, $current_lang ) {

        global $wpdb;
        $desciption = ( $current_lang == 'uk' ) ? 'description' : 'description_ru';
        $sql = "
            SELECT 
                ref as code,
                `" . $desciption . "` as name 
            FROM `" . $wpdb->prefix . "nova_poshta_city` 
            WHERE (`" . $desciption . "` LIKE '%" . $search_txt . "%') 
            ORDER BY `" . $desciption . "` ASC
        ";
        $cities = $wpdb->get_results( $sql, ARRAY_A );

        return $cities;
    }
    
    // function get_justin_cities( $search_txt, $current_lang ) {

    //     global $wpdb;
    //     $lang = ( $current_lang == 'uk' ) ? 'ua' : 'ru';
    //     $sql = "
    //         SELECT 
    //             uuid as code,
    //             descr as name 
    //         FROM `" . $wpdb->prefix . "woo_justin_" . $lang . "_cities` 
    //         WHERE (descr LIKE '%" . $search_txt . "%') 
    //         ORDER BY descr ASC
    //     ";

    //     $cities = $wpdb->get_results( $sql, ARRAY_A );

    //     return $cities;
    // }

    function get_ukr_cities( $search_txt, $current_lang ) {

        global $wpdb;
        $city_key = ( $current_lang == 'uk' ) ? 'city_ua' : 'city_ru';
        $citytype_key = 'citytype_ua'; // ( $current_lang == 'uk' ) ? 'citytype_ua' : 'citytype_ru';
        $region_key = 'region_ua'; // ( $current_lang == 'uk' ) ? 'region_ua' : 'region_ru';
        $sql = "
            SELECT 
                city_id as code,
                CONCAT(" . $city_key . ", ' (', " . $citytype_key . ", ') ', " . $region_key . ", ' обл.') as name,
                " . $city_key . " as city 
            FROM `" . $wpdb->prefix . "ukr_poshta_cities` 
            WHERE (`" . $city_key . "` LIKE '%" . $search_txt . "%') 
            ORDER BY `" . $city_key . "` ASC
        ";
        $cities = $wpdb->get_results( $sql, ARRAY_A );

        return $cities;
    }

    function get_filter_cities() {

        $cities = [];
        $query = new WP_Query( [ 
            'author' =>  get_current_user_id(),
            'post_type' => 'address', 
            'post_status' => 'publish', 
            'posts_per_page' => -1, 
        ] ); 

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) { $query->the_post(); 
                $city = get_field( 'city_addr' );
                $cities[] = $city;
            }
        } 
        wp_reset_postdata(); 

        return $cities;
    }

    function get_warehouse_shipping() {

        // check_ajax_referer( 'address_nonce', 'ajax_nonce' );
        $shipping_method = $_POST['shipping_method'];
        $city_code = $_POST['city_code'];
        $warehouses = [];
        $options_html = '<option value="" hidden>' . __('Select branch', 'natures-sunshine') . '</option>';

        if ( $shipping_method == 'nova_poshta_warehouses' ) {
            $warehouses = $this->get_np_warehouses_by_code( $city_code );
        } else if ( $shipping_method == 'nova_poshta_poshtomats' ) {
            $warehouses = $this->get_np_poshtomats_by_code( $city_code );
        } /*else if ( $shipping_method == 'justin_warehouses' ) {
            $warehouses = $this->get_justin_warehouses_by_code( $city_code );
        }*/ else if ( $shipping_method == 'ukr_warehouses' ) {
            $warehouses = $this->get_ukr_warehouses_by_code( $city_code );
        }

        foreach ( (array)$warehouses as $warehouse ) {
            $options_html .= '<option value="' . $warehouse['code'] . '">' . $warehouse['name'] . '</option>';
        }

        wp_send_json_success( [
            'options_html' => $options_html,
        ] ); 
    }

    function get_warehouses_for_all_shipping_methods() {

        check_ajax_referer( 'address_nonce', 'ajax_nonce' );

        $np_warehouses = [];
        $nova_poshta_poshtomats = [];
        // $justin_warehouses = [];
        $ukr_warehouses = [];
        
        $current_lang = apply_filters( 'wpml_current_language', null );
        $np_city_code = $_POST['nova_poshta_city_code'];
        // $justin_city_code = $_POST['justin_city_code'];
        $ukr_city_code = $_POST['ukr_city_code'];
        

        $np_options_html = '<option value="" hidden>' . __('Select branch', 'natures-sunshine') . '</option>';
        $npp_options_html = '<option value="" hidden>' . __('Select branch', 'natures-sunshine') . '</option>';
        // $justin_options_html = '<option value="" hidden>' . __('Select branch', 'natures-sunshine') . '</option>';
        $ukr_options_html = '<option value="" hidden>' . __('Select branch', 'natures-sunshine') . '</option>';

        $np_warehouses = $this->get_np_warehouses_by_code( $np_city_code, $current_lang );
        $np_poshtomats = $this->get_np_poshtomats_by_code( $np_city_code, $current_lang );
        // $justin_warehouses = $this->get_justin_warehouses_by_code( $justin_city_code, $current_lang );
        $ukr_warehouses = $this->get_ukr_warehouses_by_code( $ukr_city_code, $current_lang );
        
        foreach ( (array)$np_warehouses as $np_warehouse ) {
            $search_word = ($current_lang == 'uk') ? 'Поштомат' : 'Почтомат';
            $do_not_show = strpos($np_warehouse['name'], $search_word);
            if ( $do_not_show === false ) { // Почтомат "Новая Почта" №25384: шоссе Пролетарское, 1б ( маг. "Плюшко")
                $np_options_html .= '<option value="' . $np_warehouse['code'] . '">' . $np_warehouse['name'] . '</option>';
            }
        }
        foreach ( (array)$np_poshtomats as $np_poshtomat ) {
            $npp_options_html .= '<option value="' . $np_poshtomat['code'] . '">' . $np_poshtomat['name'] . '</option>';
        }
        // foreach ( (array)$justin_warehouses as $justin_warehouse ) {
        //     $justin_options_html .= '<option value="' . $justin_warehouse['code'] . '">' . $justin_warehouse['name'] . '</option>';
        // }
        foreach ( (array)$ukr_warehouses as $ukr_warehouse ) {
            $ukr_options_html .= '<option value="' . $ukr_warehouse['code'] . '">' . $ukr_warehouse['name'] . '</option>';
        }
        if ( empty($np_warehouses) ) {
            $np_options_html = '<option value="" hidden>' . __('No branches available', 'natures-sunshine') . '</option>';
        }
        if ( empty($np_poshtomats) ) {
            $npp_options_html = '<option value="" hidden>' . __('No poshtomats available', 'natures-sunshine') . '</option>';
        }
        // if ( empty($justin_warehouses) ) {
        //     $justin_options_html = '<option value="" hidden>' . __('No branches available', 'natures-sunshine') . '</option>';
        // }
        if ( empty($ukr_warehouses) ) {
            $ukr_options_html = '<option value="" hidden>' . __('No branches available', 'natures-sunshine') . '</option>';
        }

        wp_send_json_success( [
            'nova_poshta_warehouses_html' => $np_options_html,
            'nova_poshta_poshtomats_html' => $npp_options_html,
            // 'justin_warehouses_html' => $justin_options_html,
            'ukr_warehouses_html' => $ukr_options_html,
        ] ); 
    }

    function get_np_warehouses_by_code( $code, $current_lang ) {

        global $wpdb;
        $desciption = ( $current_lang == 'uk' ) ? 'description' : 'description_ru';
        $sql = "
            SELECT 
                ref as code,
                `" . $desciption . "` as name 
            FROM `" . $wpdb->prefix . "nova_poshta_warehouse` 
            WHERE parent_ref = '" . $code . "'
            ORDER BY `" . $desciption . "` ASC
        ";
        $warehouses = $wpdb->get_results( $sql, ARRAY_A );

        return $warehouses;
    }
    
    function get_np_poshtomats_by_code( $code, $current_lang ) {

        global $wpdb;
        $desciption = ( $current_lang == 'uk' ) ? 'description' : 'description_ru';
        $sql = "
            SELECT 
                ref as code,
                `" . $desciption . "` as name 
            FROM `" . $wpdb->prefix . "nova_poshta_poshtomat` 
            WHERE parent_ref = '" . $code . "'
            ORDER BY `" . $desciption . "` ASC
        ";
        $poshtomats = $wpdb->get_results( $sql, ARRAY_A );

        return $poshtomats;
    }
    
    // function get_justin_warehouses_by_code( $code, $current_lang ) {

    //     global $wpdb;
    //     $lang = ( $current_lang == 'uk' ) ? 'ua' : 'ru';
    //     $sql = "
    //         SELECT 
    //             uuid as code,
    //             concat(descr, ' ', address) as name
    //         FROM `" . $wpdb->prefix . "woo_justin_" . $lang . "_warehouses`
    //         WHERE city_uuid = '" . $code . "'
    //         ORDER BY descr ASC
    //     ";
    //     $warehouses = $wpdb->get_results( $sql, ARRAY_A );

    //     return $warehouses;
    // }

    function get_ukr_warehouses_by_code( $code, $current_lang ) {

        global $wpdb;
        $sql = "
            SELECT 
                postoffice_id as code,
                CONCAT(postcode, ' ', city_ua_vpz, ', ', street_ua_vpz) as name
            FROM `" . $wpdb->prefix . "ukr_poshta_warehouses` 
            WHERE city_id = '" . $code . "'
            ORDER BY `city_ua_vpz` ASC
        ";
        // AND lock_en != 'Doesn't work'
        $warehouses = $wpdb->get_results( $sql, ARRAY_A );

        return $warehouses;
    }

} 