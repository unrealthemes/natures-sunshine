<?php

class UT_Breadcrumbs {

    private static $_instance = null; 

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        add_filter( 'kama_breadcrumbs_args', [$this, 'breadcrumbs_args'] );
        add_filter( 'kama_breadcrumbs_l10n', [$this, 'breadcrumbs_l10n'] );
        add_filter( 'kama_breadcrumbs', [$this, 'breadcrumbs'], 10, 4 );
        add_filter( 'kama_breadcrumbs_filter_elements', [$this, 'breadcrumbs_add_elements'], 10, 4 );

    }

    public function breadcrumbs_args( $args ) {

        $my_args = [
            'markup' => [
                'wrappatt'  => '<div class="breadcrumbs"><div class="container"><ul class="breadcrumbs-list" role="list">%s</ul></div></div>',
                'linkpatt'  => '<li class="breadcrumbs-list__item"><a href="%s" class="breadcrumbs-list__link">%s</a></li>',
                'titlepatt' => '<li class="breadcrumbs-list__item breadcrumbs-list__item--current">%s</li>',
                'seppatt'   => '<svg class="breadcrumbs-list__divider" width="24" height="24"><use xlink:href="'. DIST_URI .'/images/sprite/svg-sprite.svg#chevron-right"></use></svg>',
            ],
            'priority_tax' => [ 'category', 'product_cat' ],
        ];
    
        return $my_args + $args;
    }

    public function breadcrumbs_l10n( $l10n ){

        $my_l10n = [
            'home' => '<svg class="breadcrumbs-list__home" width="24" height="24"><use xlink:href="'. DIST_URI .'/images/sprite/svg-sprite.svg#home"></use></svg>',
        ];

        return $my_l10n + $l10n;
    }

    public function breadcrumbs( $html, $sep, $l10n, $arg ){

        return str_replace('shop', 'filter', $html);
    }

    public function breadcrumbs_add_elements( $elms, $class, $ptype ) {

        global $wp_query;

        if ( is_single() || is_product() ) {
            unset($elms['home_after']);
        }

        $params = ut_help()->product_filter->get_params();

        if ( isset($params['health_topics']) ) {
            // $elms['home_after'] = $class->makelink( home_url('catalog/bady/'), __('Bady', 'natures-sunshine') );
            $class->maketitle( __('Bady', 'natures-sunshine') );
        }

        if ( isset($params['categories'][0]) ) {
            $term = get_term_by( 'slug', $params['categories'][0], 'product_cat' );
            // $term_link = get_term_link($term->term_id, 'product_cat');
            // $elms['home_after'] = $class->makelink( $term_link, $term->name );
            $elms['home_after'] = $class->maketitle( $term->name );
        } else if ( ! is_single() ) {
            $term = get_term_by( 'slug', $wp_query->get('param_1'), 'product_cat' );
            $elms['home_after'] = $class->maketitle( $term->name );
        }

        $elms['paging'] = [];
    
        return $elms;
    }
} 