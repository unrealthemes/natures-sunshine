<?php 
$i = 1;
$count_show = 5;
$main_components = $args['main_components'];
$params = $args['params'];
$count_show_more = count( $main_components ) - $count_show;
$show_more_hide = ( count( $main_components ) > $count_show ) ? '' : 'hide-show-more';
?>

<?php if ( $main_components ) : ?>

    <div class="filter__row main-components">
        <span class="filter__title"><?php _e('Main components', 'natures-sunshine'); ?></span>
        <div class="filter__control">

            <?php 
            foreach ( $main_components as $key => $main_component ) : 
                $class_hide = ( $i > $count_show ) ? 'hide-option-show-more' : '';
                $icon_id = get_field( 'icon_ht', $main_component );
                $checked = ( isset($params['main_components']) && in_array($main_component->slug, $params['main_components']) ) ? 'checked' : '';
            ?>

                <div class="filter__input <?php echo $class_hide; ?>" 
                     data-tax="main-components" 
                     data-id="<?php echo $main_component->term_id; ?>">

                    <input type="checkbox" 
                        name="main_components[]" 
                        id="<?php echo $main_component->slug; ?>" 
                        value="<?php echo $main_component->slug; ?>"
                        <?php echo $checked; ?>>
                        
                    <label for="<?php echo $main_component->slug; ?>">
                        <?php echo $main_component->name; ?>
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