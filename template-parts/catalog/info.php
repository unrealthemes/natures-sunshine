<?php 
$found_posts = $args['found_posts'];
$num_decine = ut_num_decline( 
    $found_posts, 
    [ 
        __('product 1', 'natures-sunshine'),
        __('product 2', 'natures-sunshine'),
        __('product 3', 'natures-sunshine')
    ] 
);
$params = $args['params'];
// unset( $params['sort'] );
unset( $params['paged'] );
unset( $params['filter_type'] );
unset( $params['current_url'] );
unset( $params['current_lang'] );
$tax_names = [
    'health_topics' => 'health-topics',
    'categories' => 'product_cat',
    'cat_product' => 'cat_product',
    'body_systems' => 'body-systems',
    'main_components' => 'main-components',
];
$product_types = [
    'pack' => __('Product sets', 'natures-sunshine'),
    'single' => __('Single products', 'natures-sunshine'),
];
$show_reset_btn = ( count($params) > 1 && isset($params['categories']) ) ? true : false;
unset($params['categories']);
?>

<div class="catalog-info">

    <?php if ( $found_posts ) : ?>
        <span class="catalog-info__result">
            <?php echo sprintf( __('Selected %s', 'natures-sunshine'), $num_decine ); ?>
        </span>
    <?php endif; ?>

    <div class="catalog-info__results tags inline-scroll">
        <div class="inline-scroll__content">

            <?php if ( $show_reset_btn ) : ?>
                <div class="tags__item tag js-clear-filter">
                    <?php _e('Reset all', 'natures-sunshine'); ?>
                    <svg class="tag__icon" width="24" height="24">
                        <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-small'; ?>"></use>
                    </svg>
                </div>
            <?php endif; ?>

            <?php foreach( $params as $key => $param ) : ?>

                <?php 
                foreach( $param as $value ) : 
                    $term = get_term_by('slug', $value, $tax_names[ $key ]);
                    $label = ( $key == 'product_type' ) ? $product_types[ $value ] : $term->name;
                ?>

                    <div class="tags__item tag js-change-filter" 
                         data-tax-name="<?php echo $key; ?>" 
                         data-slug="<?php echo $value; ?>">
                        <?php echo $label; ?>
                        <svg class="tag__icon" width="24" height="24">
                            <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-small'; ?>"></use>
                        </svg>
                    </div>

                <?php endforeach; ?>

            <?php endforeach; ?>

        </div>
    </div>
</div>