<?php

class UT_Autocomplete {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

	public function __construct() {

        if ( wp_doing_ajax() ) {
            add_action( 'wp_ajax_nopriv_autocomplete_search', [$this, 'autocomplete_search'] );
            add_action( 'wp_ajax_autocomplete_search', [$this, 'autocomplete_search'] );
        }
        
	}

    public function autocomplete_search() {

        check_ajax_referer( 'autocomplete_search_nonce', 'ajax_nonce' );

        if ( ! isset( $_REQUEST['search_txt'] ) ) {
            echo json_encode( [] );
        }

        $search_txt = $_REQUEST['search_txt'];
        $suggestions = [];
        $query = new WP_Query([
            's' => $search_txt,
            'post_type' => ['product', 'post'],
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
                global $post;

                $suggestions[] = $this->loop_content( $post );
            }
            wp_reset_postdata();
        }
        //search by sku
        $args = [
            'posts_per_page' => -1,
            'post_type' => ['product', 'post'],
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

                $suggestions[] = $this->loop_content( $post );
            }
        }
        //search by english name
        $args = [
            'posts_per_page' => -1,
            'post_type' => ['product', 'post'],
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

                $suggestions[] = $this->loop_content( $post );
            }
        }

        //search by replaces in product
        $args = [
            'posts_per_page' => -1,
            'post_type' => ['product', 'post'],
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

                $suggestions[] = $this->loop_content( $post );
            }
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
                        'post_type' => ['product', 'post'],
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
                            global $post;
                
                            $suggestions[] = $this->loop_content( $post );
                        }
                        wp_reset_postdata();
                    }
                    //search only by synonym
                    $query = new WP_Query([
                        's' => $replace,
                        'post_type' => ['product', 'post'],
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
                            global $post;
                
                            $suggestions[] = $this->loop_content( $post );
                        }
                        wp_reset_postdata();
                    }

                }
            }
        }

        $exist = [];
        foreach ( $suggestions as $key => $suggestion ) {
            if ( in_array($suggestion['id'], $exist) ) {
                unset($suggestions[$key]);
            }
            $exist[] = $suggestion['id'];
        }

        wp_send_json_success([
            'text_not_found' => __( 'We could not find any results', 'natures-sunshine' ),
            'suggestions' => $suggestions,
        ]);
    }

    public function loop_content( $post ) {

        if ( $post->post_type == 'product' ) {
            $product = wc_get_product( $post->ID );
            // $dosage = get_post_meta( $product->get_id(), '_dosage', true );  

            if ( $product->get_image_id() ) { 
                $img_url = wp_get_attachment_url( $product->get_image_id(), 'thumbnail' ); 
            } else { 
                $img_url = wc_placeholder_img_src(); 
            } 

            return [
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'eng_name' => get_post_meta( $product->get_id(), '_eng_name', true ),
                'price' => wc_price( $product->get_price() ),
                'link' => get_permalink( $product->get_id() ),
                'image' => $img_url,
            ];

        } else {

            return [
                'id' => $post->ID,
                'name' => get_the_title( $post->ID ),
                'eng_name' => '',
                'price' => '',
                'link' => get_permalink( $post->ID ),
                'image' => get_the_post_thumbnail_url( $post->ID, 'thumbnail' ),
            ];

        }
    }

}
