<?php

class UT_Product {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        
        add_action( 'init', [$this, 'register_taxonomy_health_topics'], 0 );
        add_action( 'init', [$this, 'register_taxonomy_body_systems'], 0 );
        add_action( 'init', [$this, 'register_taxonomy_main_components'], 0 );
        add_action( 'init', [$this, 'register_taxonomy_category_product'], 0 );
        add_action( 'template_redirect', [$this, 'track_product_view'], 20 );

        add_action( 'woocommerce_product_options_general_product_data', [$this, 'tab_price_add_custom_fields'] );
        add_action( 'woocommerce_product_options_shipping', [$this, 'tab_shipping_add_custom_fields'] );
        add_action( 'woocommerce_product_options_sku', [$this, 'tab_sku_add_custom_fields'] );
        add_action( 'woocommerce_product_data_panels', [ $this, 'product_add_custom_fields' ] );
        add_filter( 'woocommerce_product_data_tabs', [ $this, 'added_tabs' ], 10, 1 );
        add_action( 'woocommerce_process_product_meta', [ $this, 'product_custom_fields_save' ], 10 );
        add_filter( 'gettext', [$this, 'change_backend_product_regular_price'], 100, 3 );
	
        remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

        add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 4 );
        add_action( 'woocommerce_single_product_summary', [$this, 'woocommerce_template_sale'], 70 );
        add_action( 'woocommerce_single_product_summary', [$this, 'notify_of_availability'], 30 );
        add_action( 'woocommerce_after_single_product_summary', [$this, 'output_product_group'], 5 );
        
        add_action('woocommerce_product_set_stock', [$this, 'process_stock_change'], 1);
        add_action('woocommerce_variation_set_stock', [$this, 'process_stock_change'], 1);

        add_filter( 'woocommerce_product_tabs', [$this, 'product_tabs'], 98 );
        // add_filter( 'post_type_link', [$this, 'append_sku_string'], 1, 2 );

        if ( wp_doing_ajax() ) {
            add_action( 'wp_ajax_insert_comment', [$this, 'insert_comment'] );
            add_action( 'wp_ajax_nopriv_insert_comment', [$this, 'insert_comment'] );
            
            add_action( 'wp_ajax_ajax_pagination', [$this, 'ajax_pagination'] );
            add_action( 'wp_ajax_nopriv_ajax_pagination', [$this, 'ajax_pagination'] );

            add_action( 'wp_ajax_ajax_add_to_cart', [$this, 'ajax_add_to_cart'] );
            add_action( 'wp_ajax_nopriv_ajax_add_to_cart', [$this, 'ajax_add_to_cart'] );
            
            add_action( 'wp_ajax_back_in_stock_notifier', [$this, 'back_in_stock_notifier'] );
            add_action( 'wp_ajax_nopriv_back_in_stock_notifier', [$this, 'back_in_stock_notifier'] );
            
            add_action( 'wp_ajax_product_load_more', [$this, 'product_load_more'] );
            add_action( 'wp_ajax_nopriv_product_load_more', [$this, 'product_load_more'] );
        }

        add_action( 'pre_get_posts', [$this, 'filter_pre_get_posts'] );
        add_filter( 'body_class', [$this, 'class_names'] );
    }

    public function class_names( $classes ) {

        if ( is_singular('product') ) {
            global $product;
            
            if ( $product->get_upsell_ids() ) {
                $classes[] = 'complex';
            }
        }
    
        return $classes;
    }

    // public function append_sku_string( $link, $post ) {

    //     if ( 'product' == get_post_type( $post ) ) {
    //         $post_meta = get_post_meta( $post->ID, '_sku', true );
    //         $link = $link . '#' . $post_meta;
    //         return $link;
    //     }
    // }

    public function filter_pre_get_posts( $query ) {

        if ( ! is_admin() && $query->is_main_query() && taxonomy_exists( 'product_cat' ) ) {
            $query->set( 'posts_per_page', get_option('posts_per_page') );
        }
    }
     
    public function register_taxonomy_health_topics()  {
        
        $labels = array(
            'name'                     => 'Темы здоровья', 
            'singular_name'            => 'Тема здоровья', 
            'menu_name'                => 'Темы здоровья', 
            'all_items'                => 'Все Темы здоровья',
            'edit_item'                => 'Изменить Тему здоровья',
            'view_item'                => 'Просмотр Темы здоровья', 
            'update_item'              => 'Обновить Тему здоровья',
            'add_new_item'             => 'Добавить новую Тему здоровья',
            'new_item_name'            => 'Название новой Темы здоровья',
            'parent_item'              => 'Родительская Тема здоровья', 
            'parent_item_colon'        => 'Родительская Тема здоровья:',
            'search_items'             => 'Искать Темы здоровья',
            'popular_items'            => 'Популярные Темы здоровья', 
            'separate_items_with_commas' => 'Разделяйте Темы здоровья запятыми',
            'add_or_remove_items'      => 'Добавить или удалить Темы здоровья',
            'choose_from_most_used'    => 'Выбрать из часто используемых Тем здоровья',
            'not_found'                => 'Тем здоровья не найдено',
            'back_to_items'            => '← Назад к Темам здоровья',
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

        register_taxonomy( 'health-topics', 'product', $args );
    }
    
    public function register_taxonomy_body_systems()  {
        
        $labels = array(
            'name'                     => 'Системы организма', 
            'singular_name'            => 'Система организма', 
            'menu_name'                => 'Системы организма', 
            'all_items'                => 'Все Системы организма',
            'edit_item'                => 'Изменить Систему организма',
            'view_item'                => 'Просмотр Системы организма', 
            'update_item'              => 'Обновить Систему организма',
            'add_new_item'             => 'Добавить новую Систему организма',
            'new_item_name'            => 'Название новой Системы организма',
            'parent_item'              => 'Родительская Система организма', 
            'parent_item_colon'        => 'Родительская Система организма:',
            'search_items'             => 'Искать Системы организма',
            'popular_items'            => 'Популярные Системы организма', 
            'separate_items_with_commas' => 'Разделяйте Системы организма запятыми',
            'add_or_remove_items'      => 'Добавить или удалить Системы организма',
            'choose_from_most_used'    => 'Выбрать из часто используемых Систем организма',
            'not_found'                => 'Систем организма не найдено',
            'back_to_items'            => '← Назад к Системам организма',
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

        register_taxonomy( 'body-systems', 'product', $args );
    }
    
    public function register_taxonomy_main_components()  {
        
        $labels = array(
            'name'                     => 'Основные компоненты', 
            'singular_name'            => 'Основной компонент', 
            'menu_name'                => 'Основные компоненты', 
            'all_items'                => 'Все Основные компоненты',
            'edit_item'                => 'Изменить Основной компонент',
            'view_item'                => 'Просмотр Основные компоненты', 
            'update_item'              => 'Обновить Основной компонент',
            'add_new_item'             => 'Добавить новую Основной компонент',
            'new_item_name'            => 'Название новой Основные компоненты',
            'parent_item'              => 'Родительская Основной компонент', 
            'parent_item_colon'        => 'Родительская Основной компонент:',
            'search_items'             => 'Искать Основные компоненты',
            'popular_items'            => 'Популярные Основные компоненты', 
            'separate_items_with_commas' => 'Разделяйте Основные компоненты запятыми',
            'add_or_remove_items'      => 'Добавить или удалить Основные компоненты',
            'choose_from_most_used'    => 'Выбрать из часто используемых Основных компонентов',
            'not_found'                => 'Основных компонентов не найдено',
            'back_to_items'            => '← Назад к Основным компонентам',
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

        register_taxonomy( 'main-components', 'product', $args );
    }
    
    public function register_taxonomy_category_product()  {
        
        $labels = array(
            'name'                     => 'Категории Продуктов', 
            'singular_name'            => 'Категория Продукта', 
            'menu_name'                => 'Категории Продуктов', 
            'all_items'                => 'Все Категории Продуктов',
            'edit_item'                => 'Изменить Категория Продукта',
            'view_item'                => 'Просмотр Категории Продуктов', 
            'update_item'              => 'Обновить Категория Продукта',
            'add_new_item'             => 'Добавить новую Категория Продукта',
            'new_item_name'            => 'Название новой Категории Продуктов',
            'parent_item'              => 'Родительская Категория Продукта', 
            'parent_item_colon'        => 'Родительская Категория Продукта:',
            'search_items'             => 'Искать Категории Продуктов',
            'popular_items'            => 'Популярные Категории Продуктов', 
            'separate_items_with_commas' => 'Разделяйте Категории Продуктов запятыми',
            'add_or_remove_items'      => 'Добавить или удалить Категории Продуктов',
            'choose_from_most_used'    => 'Выбрать из часто используемых Категории Продуктов',
            'not_found'                => 'Категории Продуктов не найдено',
            'back_to_items'            => '← Назад к Категориям Продуктов',
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

        register_taxonomy( 'cat_product', 'product', $args );
    }

    public function track_product_view() {
 
        if ( ! is_singular( 'product' ) ) {
            return;
        }
     
        global $post;
     
        if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) {
            $viewed_products = array();
        } else {
            $viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );
        }

        if ( ! in_array( $post->ID, $viewed_products ) ) {
            $viewed_products[] = $post->ID;
        }
     
        if ( sizeof( $viewed_products ) > 15 ) {
            array_shift( $viewed_products );
        }
     
        // Store for session only
        wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
     
    }

    public function woocommerce_template_sale() {
        wc_get_template_part( 'single-product/sale' );
    }
    
    public function output_product_group() {
        wc_get_template_part( 'single-product/grouped' );
    }
    
    public function notify_of_availability() {
        wc_get_template_part( 'single-product/notify-of-availability' );
    }

    public function tab_price_add_custom_fields() {

        global $product, $post;

        echo '<div class="options_group">';
            woocommerce_wp_text_input( [
                'id'                => '_pv',
                'label'             => __( 'PV', 'natures-sunshine' ),
                'type'              => 'text',
                'custom_attributes' => [
                    'step' => '1',
                    'min'  => '0',
                ],
            ] );
            woocommerce_wp_text_input( [
                'id'                => '_benefit_complex',
                'label'             => __( 'Выгода комплекса', 'natures-sunshine' ),
                'type'              => 'number',
                'custom_attributes' => [
                    'step' => '1',
                    'min'  => '0',
                ],
            ] );
            woocommerce_wp_checkbox( array(
                'id'            => '_bestseller',
                'wrapper_class' => 'show_if_simple',
                'label'         => __( 'Bestseller', 'natures-sunshine' ),
             ) );
            woocommerce_wp_checkbox( array(
                'id'            => '_discount',
                'wrapper_class' => 'show_if_simple',
                'label'         => __( 'Discount', 'natures-sunshine' ),
             ) );
             woocommerce_wp_text_input( array(
                'id'            => '_rating',
                'wrapper_class' => 'show_if_simple',
                'label'         => __( 'Rating', 'natures-sunshine' ),
                'type'          => 'text',
            ) );
        echo '</div>';
    }
    
    public function tab_sku_add_custom_fields() {

        global $product, $post;

        echo '<div class="options_group">';
            woocommerce_wp_text_input( [
                'id' => '_eng_name',
                'label' => __( 'ENG name', 'natures-sunshine' ),
            ] );
            woocommerce_wp_text_input( [
                'id' => '_sell_by',
                'type' => 'date',
                'placeholder' => '',
                'label' => __( 'Sell by', 'natures-sunshine' ),
            ] );
        echo '</div>';
    }
    
    public function tab_shipping_add_custom_fields() {

        global $product, $post;

        echo '<div class="options_group">';
            woocommerce_wp_checkbox( array(
                'id'            => '_info_block',
                'wrapper_class' => 'show_if_simple',
                'label'         => __( 'Show info block', 'natures-sunshine' ),
            ) );
            woocommerce_wp_textarea_input( [
                'id' => '_shipping_txt',
                'label' => __( 'Shipping text', 'natures-sunshine' ),
            ] );
            woocommerce_wp_textarea_input( [
                'id' => '_shipping_info',
                'label' => __( 'Shipping info', 'natures-sunshine' ),
            ] );
        echo '</div>';
    }

    function product_tabs( $tabs ) {
        
        $tabs['composition'] = [
            'title' => __( 'Composition', 'natures-sunshine' ), 
            'priority' => 20,
            'callback' => [$this, 'composition_product_tab_content']
        ];
        
        $tabs['contraindications'] = [
            'title' => __( 'Contraindications', 'natures-sunshine' ), 
            'priority' => 21,
            'callback' => [$this, 'contraindications_product_tab_content']
        ];
        
        $tabs['notes'] = [
            'title' => __( 'Notes', 'natures-sunshine' ), 
            'priority' => 22,
            'callback' => [$this, 'notes_product_tab_content']
        ];

        $tabs['certificates'] = [
            'title' => __( 'Certificates', 'natures-sunshine' ), 
            'priority' => 23,
            'callback' => [$this, 'certificates_product_tab_content']
        ];
        
        $tabs['reviews'] = [
            'title' => __( 'Reviews', 'natures-sunshine' ), 
            'priority' => 24,
            'callback' => [$this, 'reviews_product_tab_content']
        ];

        return $tabs;
    }

    public function composition_product_tab_content() {
        wc_get_template_part( 'single-product/tabs/composition' );
    }

    public function contraindications_product_tab_content() {
        wc_get_template_part( 'single-product/tabs/contraindications' );
    }

    public function notes_product_tab_content() {
        wc_get_template_part( 'single-product/tabs/notes' );
    }

    public function certificates_product_tab_content() {
        wc_get_template_part( 'single-product/tabs/certificates' );
    }
    
    public function reviews_product_tab_content() {
        wc_get_template_part( 'single-product/tabs/reviews' );
    }

    public function added_tabs( array $tabs ): array {

        // $tabs['ut_description'] = [
        //     'label'    => __( 'Description', 'woocommerce' ),
        //     'target'   => 'ut_description', // tab id
        //     // 'class'    => [ 'hide_if_grouped' ], 
        //     'priority' => 70, 
        // ];
        
        // $tabs['ut_composition'] = [
        //     'label'    => __( 'Composition', 'natures-sunshine' ),
        //     'target'   => 'ut_composition', // tab id
        //     // 'class'    => [ 'hide_if_grouped' ], 
        //     'priority' => 71, 
        // ];
        
        // $tabs['ut_contraindications'] = [
        //     'label'    => __( 'Contraindications', 'natures-sunshine' ),
        //     'target'   => 'ut_contraindications', // tab id
        //     // 'class'    => [ 'hide_if_grouped' ], 
        //     'priority' => 71, 
        // ];
        
        // $tabs['ut_notes'] = [
        //     'label'    => __( 'Notes', 'natures-sunshine' ),
        //     'target'   => 'ut_notes', // tab id
        //     // 'class'    => [ 'hide_if_grouped' ], 
        //     'priority' => 72, 
        // ];
        
        // $tabs['ut_certificates'] = [
        //     'label'    => __( 'Certificates', 'natures-sunshine' ),
        //     'target'   => 'ut_certificates', // tab id
        //     // 'class'    => [ 'hide_if_grouped' ], 
        //     'priority' => 72, 
        // ];
    
        return $tabs;
    }

    public function product_add_custom_fields() {

        global $product, $post;

        // $contraindications = get_post_meta( $post->ID, '_contraindications', true );
        // $notes = get_post_meta( $post->ID, '_notes', true );

        // echo '<div id="ut_description" class="panel woocommerce_options_panel">';
        //     echo '<div class="options_group" style="padding: 10px;">';
        //         wp_editor( $post->post_content, 'description' );
        //     echo '</div>';
        // echo '</div>';

        // echo '<div id="ut_composition" class="panel woocommerce_options_panel">';
        //     echo '<div class="options_group">';
                
        //     echo '</div>';
        // echo '</div>';

        // echo '<div id="ut_contraindications" class="panel woocommerce_options_panel">';
        //     echo '<div class="options_group" style="padding: 10px;">';
        //         wp_editor( $contraindications, 'contraindications' );
        //     echo '</div>';
        // echo '</div>';
        
        // echo '<div id="ut_notes" class="panel woocommerce_options_panel">';
        //     echo '<div class="options_group" style="padding: 10px;">';
        //         wp_editor( $notes, 'notes' );
        //     echo '</div>';
        // echo '</div>';
        
        // echo '<div id="ut_certificates" class="panel woocommerce_options_panel">';
        //     echo '<div class="options_group">';
                
        //     echo '</div>';
        // echo '</div>';

    }

    public function product_custom_fields_save( $post_id ) {

        // if ( isset( $_POST['description'] ) ) {
        //     $product_data = [
        //         'ID' => $post_id,
        //         'post_content' => $_POST['description'],
        //     ];
        //     wp_update_post( $product_data );
        // }

        // update_post_meta( $post_id, '_contraindications', $_POST['contraindications'] );
        // update_post_meta( $post_id, '_notes', $_POST['notes'] );

        if ( isset( $_POST['_bestseller'] ) ) {
            update_post_meta( $post_id, '_bestseller', $_POST['_bestseller'] );
        }

        if ( isset( $_POST['_discount'] ) ) {
            update_post_meta( $post_id, '_discount', $_POST['_discount'] );
        }
        
        if ( isset( $_POST['_info_block'] ) ) {
            update_post_meta( $post_id, '_info_block', $_POST['_info_block'] );
        } else {
            update_post_meta( $post_id, '_info_block', null );
        }

        update_post_meta( $post_id, '_pv', $_POST['_pv'] );
        update_post_meta( $post_id, '_benefit_complex', $_POST['_benefit_complex'] );
        update_post_meta( $post_id, '_shipping_txt', $_POST['_shipping_txt'] );
        update_post_meta( $post_id, '_shipping_info', $_POST['_shipping_info'] );
        update_post_meta( $post_id, '_eng_name', $_POST['_eng_name'] );
        // update_post_meta( $post_id, '_capsules', $_POST['_capsules'] );
        // update_post_meta( $post_id, '_portions', $_POST['_portions'] );
        update_post_meta( $post_id, '_sell_by', $_POST['_sell_by'] );
        update_post_meta( $post_id, '_rating', $_POST['_rating'] );
        // update_post_meta( $post_id, '_wc_average_rating', $_POST['_wc_average_rating'] );
    }

    public function change_backend_product_regular_price( $translated_text, $text, $domain ) {
        global $pagenow, $post_type;
    
        if ( is_admin() && in_array( $pagenow, ['post.php', 'post-new.php'] ) && 'product' === $post_type && 'Regular price' === $text  && 'woocommerce' === $domain ) {
            $translated_text =  __( 'Розничная цена', $domain );
        }
        
        if ( is_admin() && in_array( $pagenow, ['post.php', 'post-new.php'] ) && 'product' === $post_type && 'Sale price' === $text  && 'woocommerce' === $domain ) {
            $translated_text =  __( 'Партнерска цена', $domain );
        }

        return $translated_text;
    }

    public function insert_comment() {

        check_ajax_referer( 'comment_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        if ( ! $form['product_id'] ) {
            wp_send_json_error( [
                'message' => __( 'Product ID is empty', 'natures-sunshine' ),
            ] );
        }

        if ( ! $form['comment_name'] ) {
            wp_send_json_error( [
                'message' => __( 'Field Name is required', 'natures-sunshine' ),
            ] );
        }

        if ( ! $form['comment_email'] ) {
            wp_send_json_error( [
                'message' => __( 'Field Email is required', 'natures-sunshine' ),
            ] );
        }

        if ( ! $form['comment_message'] ) {
            wp_send_json_error( [
                'message' => __( 'Field Comment is required', 'natures-sunshine' ),
            ] );
        }

        $want_answer = ( isset( $form['want_answer'] ) ) ? true : false;
        $data = [
            'comment_post_ID' => $form['product_id'],
            'comment_author' => $form['comment_name'],
            'comment_author_email' => $form['comment_email'],
            'comment_content' => $form['comment_message'],
            'comment_parent' => $form['comment_id'],
            'user_id' => $form['user_id'],
            'comment_approved' => 1,
        ];
        
        $comment_id = wp_insert_comment( wp_slash($data) );
        update_comment_meta( $comment_id, '_want_answer', $want_answer );
        // send notification for user parent comment
        if ( $form['comment_id'] && get_comment_meta( $form['comment_id'], '_want_answer', true ) ) {
            $comment = get_comment( intval($form['comment_id']) );
            $user = get_user_by('email', $comment->comment_author_email);
            $middle_name = get_user_meta( $user->ID, 'patronymic', true );
            $full_name = $user->last_name . ' ' . $user->first_name . ' ' . $middle_name;
            $data = [
                'full_name' => $full_name,
                'user_email' => $user->user_email, 
                'parent_comment_content' => $comment->comment_content, 
                'comment_content' => $form['comment_message'], 
            ];
            ut_help()->esputnik->send_reply_to_comment_message( $data ); 
        }

        wp_send_json_success();
    }

    public function ajax_pagination() {

        check_ajax_referer( 'comment_nonce', 'ajax_nonce' );

        $comments_html = '';
		$pagination_html = '';
        $cpage = ( isset($_POST['page']) && !empty($_POST['page']) ) ? $_POST['page'] : 1;
        $per_page = get_option('comments_per_page');
        $max_depth = get_option('thread_comments_depth');
        $args = [
            'post_id' => $_POST['product_id'],
            'status' => 'approve', 
        ];
        $comments_query = new WP_Comment_Query;
        $comments = $comments_query->query( $args );
        $count = ut_help()->product->parent_comment_counter( $_POST['product_id'] );
        $total = ceil($count / $per_page);

		ob_start();

        wp_list_comments( 
            [ 
                'walker' => new UT_Walker_Comment(),
                'style' => 'div',
                'max_depth' => $max_depth,
                'page' => $cpage, 
		        'per_page' => $per_page,
            ], 
            $comments
        ); 

        $comments_html = ob_get_clean();
        ob_start();

        echo '<div class="commentPagination">';
            echo paginate_links( [
                'base' => add_query_arg( 'cpage', '%#%' ),
                'format' => '',
                'prev_text' => '<svg width="24" height="24">
                                    <use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-left"></use>
                                </svg>',
                'next_text' => '<svg width="24" height="24">
                                    <use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-right"></use>
                                </svg>',
                'total' => $total,
                'current' => $cpage
            ] );
        echo '<div>';

        $pagination_html = ob_get_clean();

        wp_send_json_success( [
			'comments_html' => $comments_html,
			'pagination_html' => $pagination_html,
		] ); 
    }

    public function parent_comment_counter( $product_id ) {

        global $wpdb;

        $query = "SELECT COUNT(comment_post_id) AS count 
                  FROM $wpdb->comments 
                  WHERE `comment_approved` = 1 
                  AND `comment_post_ID` = $product_id 
                  AND `comment_parent` = 0";

        $parents = $wpdb->get_row($query);

        return $parents->count;
    }

    public function ajax_add_to_cart() {

        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
        $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
        $variation_id = absint($_POST['variation_id']);
        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        $product_status = get_post_status($product_id);

        if ($passed_validation && 'publish' === $product_status) {

            if ( isset($_POST['cart_partner_id']) && 0 < $_POST['cart_partner_id'] ) {
                WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, [], ['partner_id' => $_POST['cart_partner_id']] );
            } else {
                WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );
            }

            do_action('woocommerce_ajax_added_to_cart', $product_id);

            if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                wc_add_to_cart_message(array($product_id => $quantity), true);
            }

            WC_AJAX :: get_refreshed_fragments();
        } else {

            $data = [
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
            ];

            echo wp_send_json($data);
        }

        wp_die();
    }

    public function best_before( $product_id ) {

        $best_before = get_post_meta( $product_id, '_sell_by', true );

        if ( !$best_before ) {
            return false;
        }

        $before_end_date = get_field('before_end_date','options');
        $check_time = strtotime($best_before) - time();
        $days = floor($check_time/86400);
        
        if ( $days > $before_end_date ) { // 541 < 180
            return false;
        }

        $best_before_format = date('d.m.Y', strtotime($best_before));
        
        get_template_part( 'woocommerce/single-product/best-before', null, ['date' => $best_before_format] );
    }

    public function back_in_stock_notifier() {

        check_ajax_referer( 'product_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        if ( ! $form['product_id'] ) {
            wp_send_json_error();
        }
        
        if ( ! $form['note_email'] ) {
            wp_send_json_error();
        }

        $emails = get_post_meta( $form['product_id'], '_emails_for_back_in_stock_notifier', true );

        if ( empty($emails) ) {
            $emails = [];
        }
        $emails[] = $form['note_email'];
        update_post_meta( $form['product_id'], '_emails_for_back_in_stock_notifier', $emails );

        wp_send_json_success();
    }

    public function process_stock_change( WC_Product $product ) {   

        if ( $product->get_stock_quantity() > 1 ) {
            $emails = get_post_meta( $product->get_id(), '_emails_for_back_in_stock_notifier', true );
            // send notifications
            if ( ! empty($emails) ) {
                foreach ( (array)$emails as $email ) {
                    $data = [
                        'user_email' => $email,
                        'product_name' => $product->get_name(),
                        'product_url' => $product->get_permalink(),
                        'product_image' => $product->get_image(),
                    ];
                    ut_help()->esputnik->send_back_in_stock_notifier($data);
                }
                // clear list subscribers on this product
                update_post_meta( $product->get_id(), '_emails_for_back_in_stock_notifier', null );
            }
        }
    }

    public function product_load_more() { 

        check_ajax_referer( 'product_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        $product_types = ['simple', 'complex'];
		$products_html = '';
		$pagination_html = '';
        $per_page = get_option('posts_per_page');
        $args = $this->get_args_products( $form ); // get query arguments
        $query = new WP_Query( $args );
        $post_count = ( (int)$form['paged'] - 1 ) * (int)$per_page + (int)$query->post_count; // number of products shown along with previous pages

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) { 
				$query->the_post();
                global $product;

				$GLOBALS['product'] = wc_get_product( $query->post->ID ); 
                $product_class = ( $product->get_upsell_ids() ) ? 'cards__item cards__item--complex' : 'cards__item';
                $product_type = ( $product->get_upsell_ids() ) ? 'complex' : 'simple';
                ob_start();
                get_template_part( 
                    'woocommerce/content', 
                    'product', 
                    [
                        'class_wrapp' => $product_class,
                    ] 
                );
                $product_html = ob_get_clean();
                $product_types[ $product_type ][] = $product_html;
			} 
		} else { 
			$products_html = '<h3>' . __( 'No results were found for your parameters.', 'natures-sunshine' ) . '</h3>';
		} 
		foreach ( $product_types as $product_type ) {
            foreach ( $product_type as $_product ) {
                $products_html .= $_product;
            }
        }

        ob_start();
		$GLOBALS['wp_query'] = $query; // for custom template
        the_posts_pagination( [
            'prev_text' => '<svg width="24" height="24">
                                <use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-left"></use>
                            </svg>',
            'next_text' => '<svg width="24" height="24">
                                <use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-right"></use>
                            </svg>',
            'base' => $this->prepare_url( $form, 'pagination' ) . '/page/%#%/', // url for pagination
        ] );
		$pagination_html = ob_get_clean();
		wp_reset_query();

        wp_send_json_success( [
			'products_html' => $products_html,
			'pagination_html' => $pagination_html,
			'count_posts' => $post_count,
			'found_posts' => $query->found_posts,
            'url' => $this->prepare_url( $form, 'load_more' ), // url for update browser
		] ); 
    }

    public function get_args_products( $data ) {

		// global $sitepress;
		// $current_lang = $data['current_lang']; 
		// $sitepress->switch_lang( $current_lang );
	
		$args['paged'] = $data['paged'];
		$args['orderby'] = 'menu_order title';
        $args['order'] = 'ASC';
		$args['post_status'] = 'publish';
		$args['post_type'] = 'product';  

		if ( isset( $data['category'] ) && ! empty( $data['category'] ) ) {
			$args['tax_query'][] = [
			  	[
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => $data['category'], 
			  	]
			];
		} 

		return $args;
	}

    public function prepare_url( $params, $type ) {
        // get category of archive
        $url = home_url() . '/product-category/' . $params['category'];

        if ( isset($params['paged']) && !empty($params['paged']) && $params['paged'] > 1 && $type != 'pagination' ) {
            $url .= '/page/' . $params['paged'] . '/';
        }

        return $url;
    }
    
} 