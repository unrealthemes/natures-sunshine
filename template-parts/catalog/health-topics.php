<?php 
$i = 1;
$count_show = 5;
$health_topics = $args['health_topics'];
$params = $args['params'];
$count_show_more = count( $health_topics ) - $count_show;
$show_more_hide = ( count( $health_topics ) > $count_show ) ? '' : 'hide-show-more';
?>
 
<?php if ( $health_topics ) : ?>

    <div class="filter__row health-topics">
        <span class="filter__title"><?php _e('Health topics', 'natures-sunshine'); ?></span>
        <div class="filter__control">

            <?php 
            foreach ( $health_topics as $key => $health_topic ) : 
                $class_hide = ( $i > $count_show ) ? 'hide-option-show-more' : '';
                $icon_id = get_field( 'icon_ht', $health_topic );
                $checked = ( isset($params['health_topics']) && in_array($health_topic->slug, $params['health_topics']) ) ? 'checked' : '';
            ?>

                <div class="filter__input filter__input--icon <?php echo $class_hide; ?>" 
                     data-tax="health-topics" 
                     data-id="<?php echo $health_topic->term_id; ?>">

                    <input type="checkbox" 
                        name="health_topics[]" 
                        id="<?php echo $health_topic->slug; ?>" 
                        value="<?php echo $health_topic->slug; ?>"
                        <?php echo $checked; ?>>

                    <label for="<?php echo $health_topic->slug; ?>">
                        <img src="<?php echo wp_get_attachment_url( $icon_id, 'full' ); ?>" 
                            width="24" 
                            height="24" 
                            loading="lazy" 
                            decoding="async" 
                            alt="<?php echo $health_topic->name; ?>">
                        <?php echo $health_topic->name; ?>
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