<?php 
$i = 1;
$count_show = 5;
$body_systems = $args['body_systems'];
$params = $args['params'];
$count_show_more = count( $body_systems ) - $count_show;
$show_more_hide = ( count( $body_systems ) > $count_show ) ? '' : 'hide-show-more';
?>

<?php if ( $body_systems ) : ?>

    <div class="filter__row body-systems">
        <span class="filter__title"><?php _e('Body systems', 'natures-sunshine'); ?></span>
        <div class="filter__control">

            <?php 
            foreach ( $body_systems as $key => $body_system ) : 
                $class_hide = ( $i > $count_show ) ? 'hide-option-show-more' : '';
                $icon_id = get_field( 'icon_ht', $body_system );
                $checked = ( isset($params['body_systems']) && in_array($body_system->slug, $params['body_systems']) ) ? 'checked' : '';
            ?>

                <div class="filter__input <?php echo $class_hide; ?>" 
                     data-tax="body-systems" 
                     data-id="<?php echo $body_system->term_id; ?>">

                    <input type="checkbox" 
                        name="body_systems[]" 
                        id="<?php echo $body_system->slug; ?>" 
                        value="<?php echo $body_system->slug; ?>"
                        <?php echo $checked; ?>>
                        
                    <label for="<?php echo $body_system->slug; ?>">
                        <?php echo $body_system->name; ?>
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