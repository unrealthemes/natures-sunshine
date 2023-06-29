<?php 
$sort_options = [
	'alphabet-ay' => __('Name А-Я', 'natures-sunshine'),
	'alphabet-az' => __('Name A-Z', 'natures-sunshine'),
	'rating' => __('By rating', 'natures-sunshine'),
	'popularity' => __('By popularity', 'natures-sunshine'),
];
$params = $args['params'];
?>

<div class="catalog-sort">
    <div class="sort">
        <div class="catalog-sort__select select">
            <select id="sort" name="sort">
                <option hidden value=""><?php _e('Sorting', 'natures-sunshine'); ?></option>

                <?php 
                foreach ( $sort_options as $key => $value ) : 
                    $selected = ( $key == $params['sort'] ) ? 'selected' : '';
                ?>
                    <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
                <?php endforeach; ?>

            </select>
        </div>
    </div>
    <div class="catalog-sort__view">
        <button type="button" class="catalog-sort__view-btn btn btn-square" data-view="list">
            <svg width="24" height="24">
                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#orientation-horizontal'; ?>"></use>
            </svg>
        </button>
        <button type="button" class="catalog-sort__view-btn active btn btn-square" data-view="grid">
            <svg width="24" height="24">
                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#orientation-vertical'; ?>"></use>
            </svg>
        </button>
    </div>
</div>