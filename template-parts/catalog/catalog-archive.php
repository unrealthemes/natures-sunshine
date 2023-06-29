<?php

global $wp_query;

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] === 443 ? "https://" : "http://";
$page_url = "{$protocol}{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$product_types = ['simple', 'complex'];
$product_html = '';
$products_html = '';
$health_topics = get_terms( 'health-topics', [
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => 1,
] );
$main_components = get_terms( 'main-components', [
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => 1,
] );
$categories = get_terms( 'product_cat', [
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => 1,
] );
$cat_product = get_terms( 'cat_product', [
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => 0,
] );
$body_systems = get_terms( 'body-systems', [
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => 1,
] );
$params = ut_help()->product_filter->get_params(); // get url parameters
$args = ut_help()->product_filter->get_args_filter( $params, 'filter' ); // get query arguments

//$query = new WP_Query( $args );
$query = $wp_query;

$per_page = get_option('posts_per_page');
$post_count = ( (int)$params['paged'] - 1 ) * (int)$per_page + (int)$query->post_count; // number of products shown along with previous pages
$show_loadmore = ( $post_count == $query->found_posts ) ? 'style="display: none;"' : '';
$url = ut_help()->product_filter->prepare_url( $params, 'pagination' ); // url for pagination
// badi, bady
$hide_el = ( isset($params['categories']) && ( in_array( 'badi', $params['categories'] ) || in_array( 'bady', $params['categories'] ) ) ) ? '' : 'style="display:none;"';

if ( isset($params['categories']) && ( in_array( 'badi', $params['categories'] ) || in_array( 'bady', $params['categories'] ) ) ) {
    $main_title = __('Bady', 'natures-sunshine');
    $class_column = '';
} else {   
    // $current_term = get_term_by( 'slug', $params['categories'][0], 'product_cat' );
    $current_term = get_term_by( 'slug', $wp_query->get('param_1'), 'product_cat' );
    $main_title = $current_term->name; // get_the_archive_title();
    $class_column = 'column-5';
}
?>

<section class="catalog-content">
    <div class="container">
        <div class="catalog-header">
            <h2 class="catalog-title"><?php echo $main_title; ?></h2>
            <a href="#" class="catalog-trigger js-open-filter"><?php _e('Filters', 'natures-sunshine'); ?></a>
        </div>

        <form id="filter_form" action="" method="post">
            <!-- <div class="ut-loader"></div> -->

            <input type="hidden" id="filter_type" name="filter_type" value="">
            <input type="hidden" id="paged" name="paged" value="<?php echo $params['paged'] ?: 1; ?>">
            <input type="hidden" id="current_url" name="current_url" value="<?php echo $page_url; ?>">
            <input type="hidden" name="current_lang" value="<?php // echo ICL_LANGUAGE_CODE; ?>">

            <div class="catalog-top" <?php echo $hide_el; ?>>

                <?php
                get_template_part(
                    'template-parts/catalog/sort',
                    null,
                    [
                        'params' => $params,
                    ]
                );
                ?>

                <?php
                get_template_part(
                    'template-parts/catalog/info',
                    null,
                    [
                        'params' => $params,
                        'found_posts' => $query->found_posts,
                    ]
                );
                ?>

            </div>

            <div class="catalog-body">
                <aside class="catalog-filter js-filter" <?php echo $hide_el; ?>>


                    <div class="filter">
                        <div class="filter__header">
                            <button class="filter__header-reset text js-clear-filter" type="button">
                                <?php _e('Reset', 'natures-sunshine'); ?>
                            </button>
                            <h3 class="filter__header-title"><?php _e('Filters', 'natures-sunshine'); ?></h3>
                            <a href="#" class="filter__header-close js-filter-close">
                                <svg width="24" height="24">
                                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
                                </svg>
                            </a>
                        </div>
                        <div class="filter__body">

                            <?php
                            get_template_part(
                                'template-parts/catalog/product-type',
                                null,
                                [
                                    'params' => $params,
                                ]
                            );
                            ?>

                            <?php
                            get_template_part(
                                'template-parts/catalog/health-topics',
                                null,
                                [
                                    'health_topics' => $health_topics,
                                    'params' => $params,
                                ]
                            );
                            ?>

                            <?php
                            get_template_part(
                                'template-parts/catalog/main-components',
                                null,
                                [
                                    'main_components' => $main_components,
                                    'params' => $params,
                                ]
                            );
                            ?>

                            <?php
                            get_template_part(
                                'template-parts/catalog/categories',
                                null,
                                [
                                    'categories' => $categories,
                                    'params' => $params,
                                ]
                            );
                            ?>

                            <?php
                            get_template_part(
                                'template-parts/catalog/cat-product',
                                null,
                                [
                                    'cat_product' => $cat_product,
                                    'params' => $params,
                                ]
                            );
                            ?>

                            <?php
                            get_template_part(
                                'template-parts/catalog/body-systems',
                                null,
                                [
                                    'body_systems' => $body_systems,
                                    'params' => $params,
                                ]
                            );
                            ?>

                        </div>
                        <!-- <div class="filter__footer text-center">
                            <button type="button" class="filter__footer-submit btn btn-green">Показать товары</button>
                        </div> -->
                    </div>

                    <?php get_template_part( 'template-parts/catalog/widget', 'news' ); ?>

                    <?php get_template_part( 'template-parts/catalog/widget', 'banner' ); ?>

                </aside>
                <div class="cards catalog-cards <?php echo $class_column; ?>">
                    <div class="ut-loader"></div>

                    <?php if ( have_posts() ) : ?>

                        <?php
                        while ( have_posts() ) : the_post();

                            global $product;
                           // $GLOBALS['product'] = wc_get_product( $query->post->ID );
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
                            //$product_types[ $product_type ][] = $product_html;
                            $products_html .= $product_html;
                        endwhile;

                        /*foreach ( $product_types as $product_type ) :
                            foreach ( $product_type as $_product ) :
                                $products_html .= $_product;
                            endforeach;
                        endforeach;*/

                        echo $products_html;
                        ?>

                    <?php else : ?>

                        <?php echo '<h3>' . __( 'No results were found for your parameters.', 'natures-sunshine' ) . '</h3>'; ?>

                    <?php endif; ?>

                </div>
            </div>

        </form>

        <div class="catalog-footer text-center product-pagination">
            <div class="ut-loader"></div>
            <button class="catalog-footer__button btn btn-green js-load-more" <?php echo $show_loadmore; ?>>
                <?php _e('Load more', 'natures-sunshine'); ?>
            </button>
            <div class="js-pagination">
                <?php
                // $GLOBALS['wp_query'] = $query; // for custom template
                // the_posts_pagination( [
                // 	'end_size' => 2,
                // 	'mid_size' => 2,
                // 	'prev_text' => '<svg width="24" height="24">
                // 						<use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-left"></use>
                // 					</svg>',
                // 	'next_text' => '<svg width="24" height="24">
                // 						<use xlink:href="' . DIST_URI . '/images/sprite/svg-sprite.svg#chevron-right"></use>
                // 					</svg>',
                // 	'base' => $url . 'p-%#%/',
                // ] );
                if ( $query->max_num_pages > 1 ) :
                    ut_help()->diapasons_pagination->the_pagination( $url, $query, (int)$params['paged'] );
                endif;
                wp_reset_query();
                ?>
            </div>
        </div>
    </div>
</section>
