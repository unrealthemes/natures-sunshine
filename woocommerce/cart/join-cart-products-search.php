<div class="cart-products__search">
    <div class="ut-loader"></div>
    <h2 class="cart-products__title text bold"><?php _e('Search products', 'natures-sunshine'); ?></h2>

    <div class="search cart-products__search-field">
        <label for="join_cart_search" class="hidden"><?php _e('Search', 'natures-sunshine'); ?></label>
        <input type="search" 
               class="search__input" 
               name="join_cart_search" 
               id="join_cart_search" 
               placeholder="<?php _e('Find a product by name or article number', 'natures-sunshine'); ?>">
        <button type="button" class="search__button search__submit" title="<?php _e('Search', 'natures-sunshine'); ?>">
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

    <div class="cart-products__search-results">
        <ul class="cart-products__search-list join-cart-search-list">          
        </ul>
        <div class="cart-products__search-clean">
            <button type="button" class="cart-products__button cart-products__search-button btn btn-white clear-join-cart-search">
                <svg class="btn__icon" width="24" height="24">
                    <use xlink:href="<?= DIST_URI . '/images/sprite/svg-sprite.svg#trash'; ?>"></use>
                </svg>
                <span><?php _e('Clear selection', 'natures-sunshine'); ?></span>
            </button>
        </div>
    </div>
</div>