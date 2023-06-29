<?php 
$i = 1;
$count_show = 5;
$categories = $args['cat_product'];
$params = $args['params'];
$count_show_more = count( $categories ) - $count_show;
$show_more_hide = ( count( $categories ) > $count_show ) ? '' : 'hide-show-more';
?>

<?php if ( $categories ) : ?>

    <div class="filter__row cat_product">
        <span class="filter__title"><?php _e('Product categories', 'natures-sunshine'); ?></span>
        <div class="filter__control">

            <?php 
            foreach ( $categories as $key => $category ) : 
                $class_hide = ( $i > $count_show ) ? 'hide-option-show-more' : '';
                $icon_id = get_field( 'icon_ht', $category );
                $checked = ( isset($params['cat_product']) && in_array($category->slug, $params['cat_product']) ) ? 'checked' : '';
            ?>

                <div class="filter__input <?php echo $class_hide; ?>" 
                     data-tax="cat_product" 
                     data-id="<?php echo $category->term_id; ?>">

                    <input type="checkbox" 
                        name="cat_product[]" 
                        id="<?php echo $category->slug; ?>" 
                        value="<?php echo $category->slug; ?>"
                        <?php echo $checked; ?>>
                        
                    <label for="<?php echo $category->slug; ?>">
                        <?php echo $category->name; ?>
                    </label>
                </div>
            
            <?php $i++; endforeach; ?>

        </div>

        <a href="#" 
            class="filter__show js-filter-show <?php echo $show_more_hide; ?>" 
            data-text="<?php echo __('Show more', 'natures-sunshine') . ' ' . $count_show_more; ?>">
            <?php echo sprintf( __('Show more %s', 'natures-sunshine'), $count_show_more ); ?>
        </a>

    </div>

<?php endif; ?>