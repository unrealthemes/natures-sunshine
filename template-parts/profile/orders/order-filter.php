<?php 
$statuses = wc_get_order_statuses();
?>

<div class="orders__filter orders-filter">
    <div class="orders-filter__top profile-filter">
        <div class="search orders-filter__search">
            <label for="filter-search" class="hidden"><?php _e('Search', 'natures-sunshine'); ?></label>
            <input type="search" 
                    class="search__input" 
                    name="search" 
                    id="filter-search" 
                    placeholder="<?php _e('Search', 'natures-sunshine'); ?>"
                    value="<?php echo ( (isset($_GET['search']) ) ? $_GET['search'] : '' ); ?>">
            <button type="submit" class="search__button search__submit" title="<?php _e('Search', 'natures-sunshine'); ?>">
                <svg width="24" height="24">
                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#search'; ?>"></use>
                </svg>
            </button>
            <button type="reset" class="search__button search__reset hidden" title="<?php _e('Clear', 'natures-sunshine'); ?>">
                <svg width="24" height="24">
                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-small'; ?>"></use>
                </svg>
            </button>
        </div>
        <div class="orders-filter__select profile-filter__select select">
            <label for="filter-type" hidden><?php _e('Order type', 'natures-sunshine'); ?></label>
            <select name="type" id="filter-type">
                <option value="" hidden><?php _e('Order type', 'natures-sunshine'); ?></option>
                <option value="personal" <?php echo ( (isset($_GET['type']) && $_GET['type'] == 'personal') ? 'selected' : '' ); ?>>
                    <?php _e('Personal', 'natures-sunshine'); ?>
                </option>
                <option value="joint" <?php echo ( (isset($_GET['type']) && $_GET['type'] == 'joint') ? 'selected' : '' ); ?>>
                    <?php _e('Joint', 'natures-sunshine'); ?>
                </option>
            </select>
        </div>
        <div class="orders-filter__select profile-filter__select select">
            <label for="filter-status" hidden><?php _e('Order status', 'natures-sunshine'); ?></label>
            <select name="status" id="filter-status">
                <option value="" hidden><?php _e('Order status', 'natures-sunshine'); ?></option>

                <?php 
                foreach ( $statuses as $statuse => $label ) : 
                    $selected = (isset($_GET['status']) && $_GET['status'] == $statuse) ? 'selected' : '';
                ?>

                    <option value="<?php echo $statuse; ?>" <?php echo $selected; ?>>
                        <?php echo $label; ?>
                    </option>

                <?php endforeach; ?>

            </select>
        </div>
    </div>
    <div class="orders-filter__line">

        <div class="orders-filter__item orders-filter__radio">
            <input type="radio" 
                    name="date" 
                    id="filter-today" 
                    value="today"
                    <?php echo ( (isset($_GET['date']) && $_GET['date'] == 'today') ? 'checked' : '' ); ?>>
            <label for="filter-today"><?php _e('Today', 'natures-sunshine'); ?></label>
        </div>

        <div class="orders-filter__item orders-filter__radio">
            <input type="radio" 
                    name="date" 
                    id="filter-yesterday" 
                    value="yesterday"
                    <?php echo ( (isset($_GET['date']) && $_GET['date'] == 'yesterday') ? 'checked' : '' ); ?>>
            <label for="filter-yesterday"><?php _e('Yesterday', 'natures-sunshine'); ?></label>
        </div>

        <div class="orders-filter__item orders-filter__radio">
            <input type="radio" 
                    name="date" 
                    id="filter-week" 
                    value="week"
                    <?php echo ( (isset($_GET['date']) && $_GET['date'] == 'week') ? 'checked' : '' ); ?>>
            <label for="filter-week"><?php _e('Week', 'natures-sunshine'); ?></label>
        </div>

        <div class="orders-filter__item orders-filter__radio">
            <input type="radio" 
                    name="date" 
                    id="filter-month" 
                    value="month"
                    <?php echo ( (isset($_GET['date']) && $_GET['date'] == 'month') ? 'checked' : '' ); ?>>
            <label for="filter-month"><?php _e('Month', 'natures-sunshine'); ?></label>
        </div>

        <div class="orders-filter__item orders-filter__radio">
            <input type="radio" 
                    name="date" 
                    id="filter-quarter" 
                    value="quarter"
                    <?php echo ( (isset($_GET['date']) && $_GET['date'] == 'quarter') ? 'checked' : '' ); ?>>
            <label for="filter-quarter"><?php _e('Quarter', 'natures-sunshine'); ?></label>
        </div>

        <div class="orders-filter__item orders-filter__radio">
            <input type="radio" 
                    name="date" 
                    id="filter-year" 
                    value="year"
                    <?php echo ( (isset($_GET['date']) && $_GET['date'] == 'year') ? 'checked' : '' ); ?>>
            <label for="filter-year"><?php _e('Year', 'natures-sunshine'); ?></label>
        </div>

        <div class="orders-filter__item orders-filter__date">
            <label for="date-range" hidden><?php _e('Date picker', 'natures-sunshine'); ?></label>
            <input class="js-calendar" 
                   name="date_range" 
                   id="filter-date" 
                   placeholder="<?php _e('Date picker', 'natures-sunshine'); ?>" 
                   readonly>
        </div>

    </div>

    <?php 
    if ( isset($_GET) && ! empty($_GET) ) :
        $orders = ut_get_page_data_by_template('template-account-orders.php')
    ?>

        <div class="orders-filter__line reset-filter">
            <div class="orders-filter__item orders-filter__radio">
                <label>
                    <a href="<?php echo get_permalink( $orders->ID ); ?>" title="<?php _e('Reset filter', 'natures-sunshine'); ?>">
                        <?php _e('Reset filter', 'natures-sunshine'); ?>
                    </a>
                </label>
            </div>
        </div>

    <?php endif; ?>

</div>