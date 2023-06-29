<?php 
$type_options = [
	'pack' => __('Product sets', 'natures-sunshine'),
	'single' => __('Single products', 'natures-sunshine'),
];
$params = $args['params'];
?>

<div class="filter__row">
    <span class="filter__title"><?php _e('Offer', 'natures-sunshine'); ?></span>
    <div class="filter__control">

        <?php 
        foreach ( $type_options as $key => $value ) : 
            $checked = ( isset($params['product_type']) && in_array($key, $params['product_type']) ) ? 'checked' : '';
        ?>

            <div class="filter__input"
                 data-tax="product_type"
                 data-id="<?php echo $key; ?>">

                <input type="checkbox" 
                       name="product_type[]" 
                       value="<?php echo $key; ?>" 
                       id="<?php echo $key; ?>" 
                       <?php echo $checked; ?>>

                <label for="<?php echo $key; ?>">
                    <?php echo $value; ?>
                </label>
            </div>

        <?php endforeach; ?>

    </div>
</div>