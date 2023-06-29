<div class="header__search">
	<div class="header__search-overlay"></div>
    <div class="ut-loader"></div>
    <div class="search" id="searchform">
    <!-- <form class="search" role="search" method="get" id="searchform" action="<?php echo home_url( '/' ) ?>" > -->
        <!-- <svg class="search__icon" width="24" height="24">
            <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#microphone'; ?>"></use>
        </svg> -->
        <label for="s" class="hidden"><?php _e('Search', 'natures-sunshine'); ?></label>
        <input  class="search__input search__input--micro" 
                type="search" 
                value="<?php echo get_search_query() ?>"  
                name="s" 
                id="s"
                placeholder="<?php _e('Search', 'natures-sunshine'); ?>" 
                required>
        <input type="hidden" name="lang" value="<?php echo( ICL_LANGUAGE_CODE ); ?>">
        <button type="button" id="searchsubmit" class="search__button search__submit" title="<?php _e('Search', 'natures-sunshine'); ?>">
            <svg width="24" height="24">
                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#search'; ?>"></use>
            </svg>
        </button>
        <button type="reset" class="search__button search__reset hidden" title="Очистить">
            <svg width="24" height="24">
                <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-small'; ?>"></use>
            </svg>
        </button>
    </div>
    <ul class="result_wrapper cart-products__search-list"></ul>
</div>

<button class="header__search-trigger header-search" type="button">
    <span class="header__search-trigger__icon header-search__icon">
        <svg width="24" height="24" class="header-search__default">
            <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#search'; ?>"></use>
        </svg>
	    <svg width="24" height="24" class="header-search__active">
            <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#cross-big'; ?>"></use>
        </svg>
    </span>
    <span class="header__search-trigger__text"><?php _e('Search', 'natures-sunshine'); ?></span>
</button>