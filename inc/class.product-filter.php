<?php

class UT_Product_Filter
{

    private static $_instance = null;

    static public function getInstance()
    {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {

        if (wp_doing_ajax()) {
            add_action('wp_ajax_filter', [$this, 'filter']);
            add_action('wp_ajax_nopriv_filter', [$this, 'filter']);
        }

        add_action('pre_get_posts', [$this, 'filter_pre_get_post']);
        add_action('init', [$this, 'catalog_rewrite']);
        add_filter('posts_clauses', [$this, 'catalog_order_by'], 10, 2);

    }

    public function catalog_order_by($clauses, $query_object)
    {
        global $wpdb;

        if (isset($_POST['form'])) {
            parse_str($_POST['form'], $data);
        }else{
            if (is_shop() || is_product_category()) {
                $data = $this->get_params();
            }
        }

        if (!isset($data) || !is_array($data)) {
            $data = array();
        }

        $join = &$clauses['join'];
        $orderby = &$clauses['orderby'];

        $join .= " LEFT JOIN {$wpdb->postmeta} type_product 
        ON {$wpdb->posts}.ID = type_product.post_id AND type_product.meta_key = '_upsell_ids' ";

        $join .= " LEFT JOIN {$wpdb->postmeta} stock_status 
        ON {$wpdb->posts}.ID = stock_status.post_id AND stock_status.meta_key = '_stock_status' ";

        if (isset($data['sort']) && ($data['sort'] == 'alphabet-az' || $data['sort'] == 'alphabet-ay')) {
            $orderby = " type_product.meta_value IS NOT NULL ASC, " . $orderby;
        }elseif ($data['sort']){
            $orderby .= ", type_product.meta_value IS NOT NULL ASC";
        }else{
            $orderby = " type_product.meta_value IS NOT NULL ASC, " . $orderby;
        }

        $orderby = " stock_status.meta_value ASC, " . $orderby;

        return $clauses;
    }


    public function filter_pre_get_post($q)
    {

        if ( ! $q->is_main_query() ) {
            return;
        }

        if ( ! is_admin() ) {

            if ( is_shop() || is_product_category() ) {
                $params = $this->get_params();

                if ($args = $this->get_args_filter($params, 'filter')) {
                    foreach ($args as $argKey => $argValue) {
                        $q->set($argKey, $argValue);
                    }
                }

                if ( 'literatura' == $q->get('param_1') || 'pc-in-kosmetika' == $q->get('param_1') || 'suveniry' == $q->get('param_1') ) {
                    $term = ( 'pc-in-kosmetika' == $q->get('param_1') ) ? 'kosmetika' : $q->get('param_1');
                    $tax_query = [
                        [
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => $term,
                        ]
                    ];
                    $q->set('tax_query', $tax_query);
                }
            }
        }

        remove_action('pre_get_posts', [$this, 'filter_pre_get_post']);
    }

    public function catalog_rewrite()
    {
        // /catalog/title/pack/vospalenie-i-bol/bady/pishhevaritelnaya/b-12/p-2/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/p-(\d+)/?$',
            'index.php?post_type=product&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]&param_4=$matches[5]&param_5=$matches[6]&param_6=$matches[7]&cp=$matches[8]',
            'top'
        );
        // /catalog/title/pack/vospalenie-i-bol/bady/pishhevaritelnaya/p-2/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/p-(\d+)/?$',
            'index.php?post_type=product&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]&param_4=$matches[5]&param_5=$matches[6]&cp=$matches[7]',
            'top'
        );
        // /catalog/title/pack/vospalenie-i-bol/bady/pishhevaritelnaya/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/?',
            'index.php?post_type=product&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]&param_4=$matches[5]&param_5=$matches[6]',
            'top'
        );
        // /catalog/title/pack/vospalenie-i-bol/bady/p-2/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/p-(\d+)/?$',
            'index.php?post_type=product&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]&param_4=$matches[5]&cp=$matches[6]',
            'top'
        );
        // /catalog/title/pack/vospalenie-i-bol/bady/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/?',
            'index.php?post_type=product&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]&param_4=$matches[5]',
            'top'
        );
        // /catalog/title/pack/vospalenie-i-bol/p-2/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/p-(\d+)/?$',
            'index.php?post_type=product&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]&cp=$matches[5]',
            'top'
        );
        // /catalog/title/pack/vospalenie-i-bol/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/?',
            'index.php?post_type=product&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]',
            'top'
        );
        // /catalog/title/pack/p-2/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/p-(\d+)/?$',
            'index.php?post_type=product&param_1=$matches[2]&param_2=$matches[3]&cp=$matches[4]',
            'top'
        );
        // /catalog/title/pack/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/?',
            'index.php?post_type=product&param_1=$matches[2]&param_2=$matches[3]',
            'top'
        );

        $categories = get_terms('product_cat', [
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 1,
        ]);


        foreach ($categories as $category) {

            // /catalog/title/p-2/
            add_rewrite_rule(
                '^(catalog)/(' . $category->slug . ')/p-(\d+)/?$',
                'index.php?post_type=product&param_1=pc-in-$matches[2]&cp=$matches[3]',
                'top'
            );

            // /catalog/title/
            add_rewrite_rule(
                '^(catalog)/(' . $category->slug . ')/?',
                'index.php?post_type=product&param_1=pc-in-$matches[2]',
                'top'
            );
        }


        // /catalog/title/p-2/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/p-(\d+)/?$',
            'index.php?post_type=product&param_1=$matches[2]&cp=$matches[3]',
            'top'
        );
        // /catalog/title/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/?',
            'index.php?post_type=product&param_1=$matches[2]',
            'top'
        );

        add_filter('query_vars', function ($vars) {

            $vars[] = 'param_1';
            $vars[] = 'param_2';
            $vars[] = 'param_3';
            $vars[] = 'param_4';
            $vars[] = 'param_5';
            $vars[] = 'param_6';
            $vars[] = 'cp';

            return $vars;
        });
    }


    public function catalog_rewrite_template()
    {
        // /catalog/title/pack/vospalenie-i-bol/bady/pishhevaritelnaya/b-12/p-2/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/p-(\d+)/?$',
            'index.php?pagename=$matches[1]&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]&param_4=$matches[5]&param_5=$matches[6]&param_6=$matches[7]&cp=$matches[8]',
            'top'
        );
        // /catalog/title/pack/vospalenie-i-bol/bady/pishhevaritelnaya/p-2/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/p-(\d+)/?$',
            'index.php?pagename=$matches[1]&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]&param_4=$matches[5]&param_5=$matches[6]&cp=$matches[7]',
            'top'
        );
        // /catalog/title/pack/vospalenie-i-bol/bady/pishhevaritelnaya/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/?',
            'index.php?pagename=$matches[1]&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]&param_4=$matches[5]&param_5=$matches[6]',
            'top'
        );
        // /catalog/title/pack/vospalenie-i-bol/bady/p-2/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/p-(\d+)/?$',
            'index.php?pagename=$matches[1]&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]&param_4=$matches[5]&cp=$matches[6]',
            'top'
        );
        // /catalog/title/pack/vospalenie-i-bol/bady/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/([^/]*)/?',
            'index.php?pagename=$matches[1]&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]&param_4=$matches[5]',
            'top'
        );
        // /catalog/title/pack/vospalenie-i-bol/p-2/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/p-(\d+)/?$',
            'index.php?pagename=$matches[1]&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]&cp=$matches[5]',
            'top'
        );
        // /catalog/title/pack/vospalenie-i-bol/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/([^/]*)/?',
            'index.php?pagename=$matches[1]&param_1=$matches[2]&param_2=$matches[3]&param_3=$matches[4]',
            'top'
        );
        // /catalog/title/pack/p-2/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/p-(\d+)/?$',
            'index.php?pagename=$matches[1]&param_1=$matches[2]&param_2=$matches[3]&cp=$matches[4]',
            'top'
        );
        // /catalog/title/pack/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/([^/]*)/?',
            'index.php?pagename=$matches[1]&param_1=$matches[2]&param_2=$matches[3]',
            'top'
        );
        // /catalog/title/p-2/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/p-(\d+)/?$',
            'index.php?pagename=$matches[1]&param_1=$matches[2]&cp=$matches[3]',
            'top'
        );
        // /catalog/title/
        add_rewrite_rule(
            '^(catalog)/([^/]*)/?',
            'index.php?pagename=$matches[1]&param_1=$matches[2]',
            'top'
        );


        add_filter('query_vars', function ($vars) {

            $vars[] = 'param_1';
            $vars[] = 'param_2';
            $vars[] = 'param_3';
            $vars[] = 'param_4';
            $vars[] = 'param_5';
            $vars[] = 'param_6';
            $vars[] = 'cp';

            return $vars;
        });
    }

    public function get_params()
    {

        $query_vars = [
            get_query_var('param_1'),
            get_query_var('param_2'),
            get_query_var('param_3'),
            get_query_var('param_4'),
            get_query_var('param_5'),
            get_query_var('param_6'),
            get_query_var('cp'),
        ];
        $params = [];
        foreach ($query_vars as $param) {
            $result = $this->prepare_params($param);
            $params[array_key_first($result)] = $result[array_key_first($result)];
        }
        $params = array_diff($params, ['']);

        if (!isset($params['paged'])) {
            $params['paged'] = 1;
        }

        return $params;
    }

    public function prepare_params($param)
    {

        $result = [];

        if ($param) {
            $temp = explode('-in-', $param);

            if (isset($temp[0]) && $temp[0] == 'sort') {

                $result['sort'] = $temp[1];

            } else if (isset($temp[0]) && $temp[0] == 'type') {

                $parts = explode('-or-', $temp[1]);
                foreach ($parts as $part) {
                    $result['product_type'][] = $part;
                }

            } else if (isset($temp[0]) && $temp[0] == 'ht') {

                $parts = explode('-or-', $temp[1]);
                foreach ($parts as $part) {
                    $result['health_topics'][] = $part;
                }

            } else if (isset($temp[0]) && $temp[0] == 'pc') {

                $parts = explode('-or-', $temp[1]);
                foreach ($parts as $part) {
                    $result['categories'][] = $part;
                }

            } else if (isset($temp[0]) && $temp[0] == 'cp') {

                $parts = explode('-or-', $temp[1]);
                foreach ($parts as $part) {
                    $result['cat_product'][] = $part;
                }

            } else if (isset($temp[0]) && $temp[0] == 'mc') {

                $parts = explode('-or-', $temp[1]);
                foreach ($parts as $part) {
                    $result['main_components'][] = $part;
                }

            } else if (isset($temp[0]) && $temp[0] == 'bs') {

                $parts = explode('-or-', $temp[1]);
                foreach ($parts as $part) {
                    $result['body_systems'][] = $part;
                }

            } else {

                $temp_page = explode('-', $param);
                if (isset($temp_page[0]) && is_numeric($temp_page[1]) && $temp_page[1] > 0) {
                    $result['paged'] = $temp_page[1];
                }
            }
        }

        return $result;
    }

    public function prepare_url($params, $type = 'filter')
    {

        $in = '-in-';
        $reset = true;
        // $get_params = $this->get_params();
        // $url = ut_get_permalik_by_template('template-catalog.php');
        $url = home_url('/catalog/pc-in-bady/');

        if (isset($params['sort']) && !empty($params['sort'])) {
            $reset = false;
            $url .= 'sort' . $in . $params['sort'] . '/';
        }

        if (isset($params['product_type']) && !empty($params['product_type'])) {
            $reset = false;
            $url .= 'type' . $in;
            foreach ((array)$params['product_type'] as $key => $product_type) {
                $or = (count($params['product_type']) > 1 && count($params['product_type']) > ($key + 1)) ? '-or-' : '/';
                $url .= $product_type . $or;
            }
        }

        if (isset($params['health_topics']) && !empty($params['health_topics'])) {
            $reset = false;
            $url .= 'ht' . $in;
            foreach ((array)$params['health_topics'] as $key => $health_topic) {
                $or = (count($params['health_topics']) > 1 && count($params['health_topics']) > ($key + 1)) ? '-or-' : '/';
                $url .= $health_topic . $or;
            }
        }

        if (isset($params['main_components']) && !empty($params['main_components'])) {
            $reset = false;
            $url .= 'mc' . $in;
            foreach ((array)$params['main_components'] as $key => $category) {
                $or = (count($params['main_components']) > 1 && count($params['main_components']) > ($key + 1)) ? '-or-' : '/';
                $url .= $category . $or;
            }
        }

        if (isset($params['categories']) && !empty($params['categories']) && !in_array('bady', $params['categories'])) {
            $reset = false;
            $url .= 'pc' . $in;
            foreach ((array)$params['categories'] as $key => $category) {
                $or = (count($params['categories']) > 1 && count($params['categories']) > ($key + 1)) ? '-or-' : '/';
                $url .= $category . $or;
            }
        }

        if (isset($params['cat_product']) && !empty($params['cat_product'])) {
            $reset = false;
            $url .= 'cp' . $in;
            foreach ((array)$params['cat_product'] as $key => $category) {
                $or = (count($params['cat_product']) > 1 && count($params['cat_product']) > ($key + 1)) ? '-or-' : '/';
                $url .= $category . $or;
            }
        }

        if (isset($params['body_systems']) && !empty($params['body_systems'])) {
            $reset = false;
            $url .= 'bs' . $in;
            foreach ((array)$params['body_systems'] as $key => $body_system) {
                $or = (count($params['body_systems']) > 1 && count($params['body_systems']) > ($key + 1)) ? '-or-' : '/';
                $url .= $body_system . $or;
            }
        }

        if (isset($params['paged']) && !empty($params['paged']) && $params['paged'] > 1 && $type == 'filter') {
            $reset = false;
            $url .= 'p-' . $params['paged'] . '/';
        }

        if ($reset) {
            $url = home_url('/catalog/bady/');
        }

        return $url;
    }

    public function filter()
    {

        check_ajax_referer('filter_nonce', 'ajax_nonce');
        parse_str($_POST['form'], $form);

        $product_types = [
            'pack' => false,
            'single' => false,
        ];
        $category_ids = [];
        $cat_product_ids = [];
        $health_topics_ids = [];
        $main_components_ids = [];
        $body_systems_ids = [];
        $product_types = ['simple', 'complex'];
        $products_html = '';
        $pagination_html = '';
        $per_page = get_option('posts_per_page');
        $args = $this->get_args_filter($form, 'filter'); // get query arguments
        $query = new WP_Query($args);
        $post_count = ((int)$form['paged'] - 1) * (int)$per_page + (int)$query->post_count; // number of products shown along with previous pages
        $params = $this->get_params(); // get url parameters

        ob_start();
        get_template_part(
            'template-parts/catalog/info',
            null,
            [
                'params' => $form,
                'found_posts' => $query->found_posts,
            ]
        );
        $info_html = ob_get_clean();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $product;

                $GLOBALS['product'] = wc_get_product($query->post->ID);
                $product_class = ($product->get_upsell_ids()) ? 'cards__item cards__item--complex' : 'cards__item';
                $product_type = ($product->get_upsell_ids()) ? 'complex' : 'simple';
                ob_start();
                get_template_part(
                    'woocommerce/content',
                    'product',
                    [
                        'class_wrapp' => $product_class,
                    ]
                );
                $product_html = ob_get_clean();
                //$product_types[ $product_type ][] = $product_html;
                $products_html .= $product_html;
            }
        } else {
            $products_html = '<h3>' . __('No results were found for your parameters.', 'natures-sunshine') . '</h3>';
        }

        /*foreach ($product_types as $product_type) {
            foreach ($product_type as $_product) {
                $products_html .= $_product;
            }
        }*/

        ob_start();
        // $GLOBALS['wp_query'] = $query; // for custom template
        // the_posts_pagination( [
        //     'prev_text' => '<svg width="24" height="24">
        //                         <use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-left"></use>
        //                     </svg>',
        //     'next_text' => '<svg width="24" height="24">
        //                         <use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-right"></use>
        //                     </svg>',
        //     'base' => $this->prepare_url( $form, 'pagination' ) . 'p-%#%/', // url for pagination
        // ] );
        if ($query->max_num_pages > 1) {
            ut_help()->diapasons_pagination->the_pagination($this->prepare_url($form, 'pagination'), $query, (int)$form['paged']);
        }
        $pagination_html = ob_get_clean();
        wp_reset_query();
        // get all existing filter options of all products all pages
        unset($args['paged']);
        $args['posts_per_page'] = -1;
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $product;

                $cat_products = get_the_terms($product->get_id(), 'cat_product');
                $health_topics = get_the_terms($product->get_id(), 'health-topics');
                $main_components = get_the_terms($product->get_id(), 'main-components');
                $body_systems = get_the_terms($product->get_id(), 'body-systems');

                if ($product->get_upsell_ids()) {
                    $product_types['pack'] = true;
                } else {
                    $product_types['single'] = true;
                }

                if (!empty($product->get_category_ids())) {
                    $category_ids = array_merge($category_ids, $product->get_category_ids());
                }

                if (!empty($cat_products)) {
                    foreach ($cat_products as $cat_product) {
                        $cat_product_ids[] = $cat_product->term_id;
                    }
                }

                if (!empty($health_topics)) {
                    foreach ($health_topics as $health_topic) {
                        $health_topics_ids[] = $health_topic->term_id;
                    }
                }

                if (!empty($main_components)) {
                    foreach ($main_components as $main_component) {
                        $main_components_ids[] = $main_component->term_id;
                    }
                }

                if (!empty($body_systems)) {
                    foreach ($body_systems as $body_system) {
                        $body_systems_ids[] = $body_system->term_id;
                    }
                }
            }
        }
        wp_reset_query();
        $url = $this->prepare_url($form, 'filter'); // url for update browser
        $lang_url = ut_filter_lang_url($url);

        wp_send_json_success([
            'products_html' => $products_html,
            'pagination_html' => $pagination_html,
            'info_html' => $info_html,
            'count_posts' => $post_count,
            'found_posts' => $query->found_posts,
            'product_types' => $product_types,
            'category_ids' => array_unique($category_ids),
            'cat_product_ids' => array_unique($cat_product_ids),
            'main_components_ids' => array_unique($main_components_ids),
            'health_topics_ids' => array_unique($health_topics_ids),
            'body_systems_ids' => array_unique($body_systems_ids),
            'url' => $url,
            'lang_url' => $lang_url,
        ]);
    }

    public function get_args_filter($data, $type = 'filter')
    {

        // global $sitepress;
        // $current_lang = $data['current_lang'];
        // $sitepress->switch_lang( $current_lang );

        $args['paged'] = $data['paged'];
        $args['orderby'] = 'menu_order title';
        $args['order'] = 'ASC';
        $args['post_status'] = 'publish';
        $args['post_type'] = 'product';

        /**
         * Product sets or single
         */
        if (isset($data['product_type']) && !empty($data['product_type'])) {
            $product_types = $data['product_type'];

            if (count($product_types) == 1 && $product_types[0] == 'pack') {
                $args['meta_query'][] = [
                    [
                        'key' => '_upsell_ids',
                        'value' => [''],
                        'compare' => 'NOT IN'
                    ]
                ];
            } else if (count($product_types) == 1 && $product_types[0] == 'single') {
                $args['meta_query'][] = [
                    'relation' => 'OR',
                    [
                        'key' => '_upsell_ids',
                        'value' => [''],
                        'compare' => 'IN'
                    ],
                    [
                        'key' => '_upsell_ids',
                        'compare' => 'NOT EXISTS'
                    ]
                ];
            }
        }

        /**
         * Product categories taxonomy
         */
        if (isset($data['categories']) && !empty($data['categories'])) {
            $args['tax_query'][] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $data['categories'],
                ]
            ];
        }

        /**
         * Categories Product taxonomy
         */
        if (isset($data['cat_product']) && !empty($data['cat_product'])) {
            $args['tax_query'][] = [
                [
                    'taxonomy' => 'cat_product',
                    'field' => 'slug',
                    'terms' => $data['cat_product'],
                ]
            ];
        }

        /**
         * Health topics taxonomy
         */
        if (isset($data['health_topics']) && !empty($data['health_topics'])) {
            $args['tax_query'][] = [
                [
                    'taxonomy' => 'health-topics',
                    'field' => 'slug',
                    'terms' => $data['health_topics'],
                ]
            ];
        }

        /**
         * Main components taxonomy
         */
        if (isset($data['main_components']) && !empty($data['main_components'])) {
            $args['tax_query'][] = [
                [
                    'taxonomy' => 'main-components',
                    'field' => 'slug',
                    'terms' => $data['main_components'],
                ]
            ];
        }

        /**
         * Body systems topics taxonomy
         */
        if (isset($data['body_systems']) && !empty($data['body_systems'])) {
            $args['tax_query'][] = [
                [
                    'taxonomy' => 'body-systems',
                    'field' => 'slug',
                    'terms' => $data['body_systems'],
                ]
            ];
        }

        $args['tax_query'][] = [
            [
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'exclude-from-catalog',
                'operator' => 'NOT IN',
            ]
        ];

        /**
         * Orderby
         */
        if (isset($data['sort']) && $data['sort'] == 'rating') {

            $args['meta_key'] = '_rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';

        } else if (isset($data['sort']) && $data['sort'] == 'popularity') {

            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';

        } else if (isset($data['sort']) && $data['sort'] == 'alphabet-ay') {

            $args['orderby'] = 'title';
            $args['order'] = 'ASC';

        } else if (isset($data['sort']) && $data['sort'] == 'alphabet-az') {

            $args['meta_key'] = '_eng_name';
            $args['orderby'] = 'meta_value';
            $args['order'] = 'ASC';
        }

        return $args;
    }

    public function head_seo_meta_tags()
    {

        $noindex = false;
        $index = false;
        $params = $this->get_params();

        if (isset($params['main_components']) && !empty($params['main_components'])) {
            $noindex = true;
        }

        if (isset($params['paged'])) {
            unset($params['paged']);
        }

        if (isset($params['main_components'])) {
            unset($params['main_components']);
        }

        if (count($params) == 1) {

            foreach ($params as $param) {
                if (count($param) == 1) {
                    $index = true;
                } else {
                    $noindex = true;
                    break;
                }
            }

        } else {
            $noindex = true;
        }

        if ($noindex && !$index) {
            echo '<meta name="robots" content="noindex, follow">';
        }

        if (!$noindex && $index) {
            echo '<meta name="robots" content="index, follow">';
        }
    }

}
