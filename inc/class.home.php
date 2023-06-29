<?php

class UT_Home {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        if ( wp_doing_ajax() ) {
            add_action( 'wp_ajax_categories_tabs_with_slider', [$this, 'categories_tabs_with_slider'] );
            add_action( 'wp_ajax_nopriv_categories_tabs_with_slider', [$this, 'categories_tabs_with_slider'] );
        }

        add_action( 'acf/init', [$this, 'acf_init_block_types'] );
    }

    public function categories_tabs_with_slider() {

        check_ajax_referer( 'front_page_nonce', 'ajax_nonce' );
        $taxonomy = $_POST['taxonomy'];
        $term_id = $_POST['term_id'];
        $count = $_POST['count'];
        $slider_html = '';
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $count,
            'tax_query' => [ [
                'taxonomy' => $taxonomy,
                'field' => 'term_id', // can be 'term_id', 'slug' or 'name'
                'terms' => [ $term_id ],
            ], ],
        ];
        $loop = new WP_Query( $args ); 

        ob_start();
        if ( $loop->have_posts() ) :
            while ( $loop->have_posts() ) : $loop->the_post(); 
                global $product; 
                $GLOBALS['product'] = wc_get_product( $loop->post->ID ); 
                get_template_part( 
                    'woocommerce/content', 
                    'product', 
                    [ 
                        'cat_ids' => implode(', ', $product->get_category_ids()) 
                    ] 
                );
            endwhile; 
        endif;
        $slider_html = ob_get_clean();

        wp_reset_postdata();

        wp_send_json_success([
            'slider_html' => $slider_html,
        ]);
    }
     
    public function acf_init_block_types() {

        // Check function exists.
        if ( function_exists('acf_register_block_type') ) {
    
            acf_register_block_type([
                'name'              => 'front',
                'title'             => __('Слайдер'),
                // 'description'       => __('A custom blog front.'),
                'render_template'   => 'template-parts/front-page/front.php',
                'category'          => 'natures-sunshine',
                'icon'              => 'welcome-learn-more',
                'keywords'          => [ 'Слайдер' ],
                'example'           => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [
                            'is_preview'    => true
                        ]
                    ]
                ]
            ]);

            acf_register_block_type([
                'name'              => 'advantages',
                'title'             => __('Преимущества'),
                // 'description'       => __('A custom blog advantages.'),
                'render_template'   => 'template-parts/front-page/advantages.php',
                'category'          => 'natures-sunshine',
                'icon'              => 'welcome-learn-more',
                'keywords'          => [ 'Преимущества' ],
                'example'           => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [
                            'is_preview'    => true
                        ]
                    ]
                ]
            ]);
            
            acf_register_block_type([
                'name'              => 'seo',
                'title'             => __('Категории продуктов (SEO)'),
                // 'description'       => __('A custom blog seo.'),
                'render_template'   => 'template-parts/front-page/seo.php',
                'category'          => 'natures-sunshine',
                'icon'              => 'welcome-learn-more',
                'keywords'          => [ 'Категории', 'SEO' ],
                'example'           => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [
                            'is_preview'    => true
                        ]
                    ]
                ]
            ]);
            
            acf_register_block_type([
                'name'              => 'categories-products',
                'title'             => __('Категории продуктов'),
                // 'description'       => __('A custom blog categories-products.'),
                'render_template'   => 'template-parts/front-page/categories.php',
                'category'          => 'natures-sunshine',
                'icon'              => 'welcome-learn-more',
                'keywords'          => [ 'Категории' ],
                'example'           => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [
                            'is_preview'    => true
                        ]
                    ]
                ]
            ]);

            acf_register_block_type([
                'name'              => 'health-topics',
                'title'             => __('Темы здоровья'),
                // 'description'       => __('A custom blog health-topics.'),
                'render_template'   => 'template-parts/front-page/health-topics.php',
                'category'          => 'natures-sunshine',
                'icon'              => 'welcome-learn-more',
                'keywords'          => [ 'Темы здоровья' ],
                'example'           => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [
                            'is_preview'    => true
                        ]
                    ]
                ]
            ]);
            
            acf_register_block_type([
                'name'              => 'main-components',
                'title'             => __('Основные компонетны'),
                // 'description'       => __('A custom blog main-components.'),
                'render_template'   => 'template-parts/front-page/results.php',
                'category'          => 'natures-sunshine',
                'icon'              => 'welcome-learn-more',
                'keywords'          => [ 'Основные компонетны' ],
                'example'           => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [
                            'is_preview'    => true
                        ]
                    ]
                ]
            ]);
            
            acf_register_block_type([
                'name'              => 'news-sunshine',
                'title'             => __('Новости Sunshine'),
                // 'description'       => __('A custom blog news-sunshine.'),
                'render_template'   => 'template-parts/front-page/news.php',
                'category'          => 'natures-sunshine',
                'icon'              => 'welcome-learn-more',
                'keywords'          => [ 'Новости Sunshine' ],
                'example'           => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [
                            'is_preview'    => true
                        ]
                    ]
                ]
            ]);
            
            acf_register_block_type([
                'name'              => 'popular',
                'title'             => __('Слайдер товаров'),
                // 'description'       => __('A custom blog popular.'),
                'render_template'   => 'template-parts/front-page/popular.php',
                'category'          => 'natures-sunshine',
                'icon'              => 'welcome-learn-more',
                'keywords'          => [ 'Слайдер товаров' ],
                'example'           => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [
                            'is_preview'    => true
                        ]
                    ]
                ]
            ]);
            
            acf_register_block_type([
                'name'              => 'tab-products',
                'title'             => __('Слайдер товаров (вкладки)'),
                // 'description'       => __('A custom blog tab-products.'),
                'render_template'   => 'template-parts/front-page/products.php',
                'category'          => 'natures-sunshine',
                'icon'              => 'welcome-learn-more',
                'keywords'          => [ 'Слайдер товаров' ],
                'example'           => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [
                            'is_preview'    => true
                        ]
                    ]
                ]
            ]);
            
            acf_register_block_type([
                'name'              => 'banner',
                'title'             => __('Банер'),
                // 'description'       => __('A custom blog banner.'),
                'render_template'   => 'template-parts/front-page/banner.php',
                'category'          => 'natures-sunshine',
                'icon'              => 'welcome-learn-more',
                'keywords'          => [ 'Банер' ],
                'example'           => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [
                            'is_preview'    => true
                        ]
                    ]
                ]
            ]);
            
            acf_register_block_type([
                'name'              => 'banners',
                'title'             => __('Банеры'),
                // 'description'       => __('A custom blog banners.'),
                'render_template'   => 'template-parts/front-page/banners.php',
                'category'          => 'natures-sunshine',
                'icon'              => 'welcome-learn-more',
                'keywords'          => [ 'Банер' ],
                'example'           => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [
                            'is_preview'    => true
                        ]
                    ]
                ]
            ]);

        }
    }

} 