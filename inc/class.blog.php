<?php

class UT_Blog {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        if ( wp_doing_ajax() ) {
            add_action( 'wp_ajax_load_more', [$this, 'load_more'] );
            add_action( 'wp_ajax_nopriv_load_more', [$this, 'load_more'] );
        }

        add_filter( 'block_categories_all', [$this, 'filter_block_categories_when_post_provided'], 10, 2 );
        add_action( 'acf/init', [$this, 'acf_init_block_types'] );
        add_filter( 'excerpt_length', [$this, 'excerpt_length'] );
        add_filter( 'excerpt_more', [$this, 'excerpt_more'] );
        add_filter( 'get_the_archive_title', [$this, 'archive_title'] );
    }

    public function load_more() {

        check_ajax_referer( 'blog_nonce', 'ajax_nonce' );
        parse_str( $_POST['form'], $form );

        $posts_html = '';
		$pagination_html = '';
        $per_page = get_option('posts_per_page');
        $args = $this->get_args_blog( $form ); // get query arguments
        $query = new WP_Query( $args );
        $post_count = ( (int)$form['paged'] - 1 ) * (int)$per_page + (int)$query->post_count; // number of products shown along with previous pages

        ob_start();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) { 
				$query->the_post();
                get_template_part( 'template-parts/blog/content' );
			} 
		} 
		$posts_html = ob_get_clean();

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
			'posts_html' => $posts_html,
			'pagination_html' => $pagination_html,
			'count_posts' => $post_count,
			'found_posts' => $query->found_posts,
            'url' => $this->prepare_url( $form, 'load_more' ), // url for update browser
		] ); 
    }

    public function get_args_blog( $data ) {

		// global $sitepress;
		// $current_lang = $data['current_lang']; 
		// $sitepress->switch_lang( $current_lang );
	
		$args['paged'] = $data['paged'];
		$args['orderby'] = 'menu_order title';
        $args['order'] = 'ASC';
		$args['post_status'] = 'publish';
		$args['post_type'] = 'post';  

		if ( isset( $data['category'] ) && ! empty( $data['category'] ) ) {
			$args['tax_query'][] = [
			  	[
					'taxonomy' => 'category',
					'field' => 'slug',
					'terms' => $data['category'], 
			  	]
			];
		} 

		return $args;
	}

    public function prepare_url( $params, $type ) {
        // get category of archive
        $url = home_url() . '/category/' . $params['category'];

        if ( isset($params['paged']) && !empty($params['paged']) && $params['paged'] > 1 && $type != 'pagination' ) {
            $url .= '/page/' . $params['paged'] . '/';
        }

        return $url;
    }

    public function filter_block_categories_when_post_provided( $block_categories, $editor_context ) {

        if ( ! empty( $editor_context->post ) ) {
            array_push(
                $block_categories,
                array(
                    'slug'  => 'natures-sunshine',
                    'title' => __( 'Natures sunshine' ),
                    'icon'  => 'welcome-learn-more',
                )
            );
        }

        return $block_categories;
    }
     
    public function acf_init_block_types() {

        // Check function exists.
        if ( function_exists('acf_register_block_type') ) {
    
            acf_register_block_type([
                'name'              => 'blog',
                'title'             => __('Блог'),
                'description'       => __('A custom blog block.'),
                'render_template'   => 'template-parts/blog/block.php',
                'category'          => 'natures-sunshine',
                'icon'              => 'welcome-learn-more',
                'keywords'          => [ 'Блог' ],
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

    public function excerpt_length( $length ) {

        return 12;
    }

    public function excerpt_more( $more ) {

        return '...';
    }

    public function archive_title( $title ) {

        if (is_category()) {
            $title = single_cat_title('', false);
        } elseif (is_tag()) {
            $title = single_tag_title('', false);
        } elseif (is_author()) {
            $title = '<span class="vcard">' . get_the_author() . '</span>';
        } elseif (is_tax()) { //for custom post types
            $title = sprintf(__('%1$s'), single_term_title('', false));
        } elseif (is_post_type_archive()) {
            $title = post_type_archive_title('', false);
        }
        return $title;
    }

} 