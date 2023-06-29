<?php

class UT_Mega_Menu extends Walker_Nav_Menu {

    /**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu' );

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul$class_names>{$n}";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent  = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

        if ( get_field( 'active_mega_menu', $item->ID ) ) {
            $classes[] = 'has-mega-menu';
        }

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS classes applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		if ( '_blank' === $item->target && empty( $item->xfn ) ) {
			$atts['rel'] = 'noopener noreferrer';
		} else {
			$atts['rel'] = $item->xfn;
		}
		$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current'] = $item->current ? 'page' : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title        Title attribute.
		 *     @type string $target       Target attribute.
		 *     @type string $rel          The rel attribute.
		 *     @type string $href         The href attribute.
		 *     @type string $aria_current The aria-current attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function get_terms_by_letter_alphabetical_order( $taxonomy ) {

		$terms = get_terms( [
			'taxonomy' => $taxonomy,
			'hide_empty' => true,
			'orderby' => 'title',
			'order' => 'ASC',
			// 'meta_query' => [
			// 	'relation' => 'AND',
			// 	[
			// 		'meta_key' => 'show_in_mm',
			// 		'value' => '1',
			// 		'compare' => '=='
			// 	]
			// ]
		] );
		$letter_keyed_terms = [];

		if ( $terms ) {
			foreach ( $terms as $terms ) {
				$first_letter = strtoupper( mb_substr( $terms->name, 0, 1, 'UTF-8' ) );

				if ( ! array_key_exists( $first_letter, $letter_keyed_terms ) ) {
					$letter_keyed_terms[ $first_letter ] = [];
				}
		
				$letter_keyed_terms[ $first_letter ][] = $terms;
			}
		}

		return $letter_keyed_terms;
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_el()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Page data object. Not used.
	 * @param int      $depth  Depth of page. Not Used.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

        if ( get_field( 'active_mega_menu', $item->ID ) ) {

			$catalog_url = home_url('/catalog/bady/'); // ut_get_permalik_by_template('template-catalog.php');
            $terms_ht = get_terms( [
                'taxonomy' => 'health-topics',
                'hide_empty' => true,
				'orderby' => 'meta_value_num',
				'meta_key' => 'order_ht',
        		'order' => 'ASC',
				'meta_query' => [
					'relation' => 'AND',
					[
						'meta_key' => 'show_in_mm',
						'value' => '1',
						'compare' => '=='
					]
				]
            ] );

			$letter_keyed_terms_pc = $this->get_terms_by_letter_alphabetical_order('cat_product');
			$letter_keyed_terms_bs = $this->get_terms_by_letter_alphabetical_order('body-systems');
        
            $output .= '<div class="mega-menu">
                            <div class="container">
                                <div class="mega-menu__block">
                                    <div class="mega-menu__links">
                                        <a href="#health" class="mega-menu__link active">'. __('Health topics', 'natures-sunshine') .'</a>
                                        <a href="#categories" class="mega-menu__link">'. __('Product categories', 'natures-sunshine') .'</a>
                                        <a href="#systems" class="mega-menu__link">'. __('Body systems', 'natures-sunshine') .'</a>
                                    </div>
                                    <div class="mega-menu__panels">
                                        <div class="mega-menu__panel active" id="health">
                                            <p class="mega-menu__panel-title">'. __('Health topics', 'natures-sunshine') .'</p>
                                            <ul class="mega-menu__panel-list mega-menu__panel-list--grid" role="list">';
                                                foreach ($terms_ht as $term_ht) : 
                                                    $icon_id = get_field( 'icon_ht', $term_ht ); 
                                                    // $link = get_term_link( (int)$term_ht->term_id, 'health-topics' );
													$link = home_url('/catalog/ht-in-'. $term_ht->slug .'/pc-in-bady/');
													$num_decine = ut_num_decline( 
														$term_ht->count, 
														[ 
															__('drug 1', 'natures-sunshine'),
															__('drug 2', 'natures-sunshine'),
															__('drug 3', 'natures-sunshine')
														] 
													);
                                                    $output .= '<li class="mega-menu__panel-list-item">
                                                                    <a href="'. $link .'" class="mega-menu__panel-list-link" title="'. $term_ht->name .'">
                                                                        <div class="mega-menu__panel-list-icon">';

                                                                        if ( $icon_id ) :
                                                                            $output .= '<img src="'. wp_get_attachment_url( $icon_id, 'full' ) .'" width="24" height="24" loading="eager" decoding="async" alt="">';
                                                                        endif;
                                                            
                                                                        $output .= '</div>
                                                                        <div class="mega-menu__panel-list-content">
                                                                            <p class="mega-menu__panel-list-title">'. $term_ht->name .'</p>
                                                                            <span class="mega-menu__panel-list-count">'. $num_decine .'</span>
                                                                        </div>
                                                                    </a>
                                                                </li>';
                                                endforeach; 
                                $output .= '</ul>
                                            <a href="'. $catalog_url .'" class="mega-menu__panel-more">
                                                '. __('See all', 'natures-sunshine') .'
                                                <svg width="24" height="24">
                                                    <use xlink:href="'. DIST_URI . '/images/sprite/svg-sprite.svg#arrow-right"></use>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="mega-menu__panel" id="categories">
                                            <p class="mega-menu__panel-title">'. __('Product categories', 'natures-sunshine') .'</p>
                                            <ul class="mega-menu__panel-list mega-menu__panel-list--4col" role="list">';
                                                foreach ($letter_keyed_terms_pc as $letter => $letter_keyed_term_pc) : 
                                                    $output .= '<li class="mega-menu__panel-list-item">
                                                                    <div class="mega-menu__panel-list-letter">'. $letter .'</div>
                                                                    <div class="mega-menu__panel-list-content">';
                                                                        foreach ($letter_keyed_term_pc as $term_pc) : 
																			$link = home_url('/catalog/pc-in-bady/cp-in-'. $term_pc->slug .'/');
                                                                            $output .= '<a href="'. $link .'" class="mega-menu__panel-list-text">'. $term_pc->name .'</a>';
                                                                        endforeach; 
                                                        $output .= '</div>
                                                                </li>';
                                                endforeach; 
                                $output .= '</ul>
                                            <a href="'. $catalog_url .'" class="mega-menu__panel-more">
                                                '. __('See all', 'natures-sunshine') .'
                                                <svg width="24" height="24">
                                                    <use xlink:href="'. DIST_URI . '/images/sprite/svg-sprite.svg#arrow-right"></use>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="mega-menu__panel" id="systems">
                                            <p class="mega-menu__panel-title">'. __('Body systems', 'natures-sunshine') .'</p>
                                            <ul class="mega-menu__panel-list mega-menu__panel-list--3col" role="list">';
												foreach ($letter_keyed_terms_bs as $letter => $letter_keyed_term_bs) : 
													$output .= '<li class="mega-menu__panel-list-item">
																	<div class="mega-menu__panel-list-letter">'. $letter .'</div>
																	<div class="mega-menu__panel-list-content">';
																		foreach ($letter_keyed_term_bs as $term_bs) : 
																			$link = home_url('/catalog/bs-in-'. $term_bs->slug .'/pc-in-bady/');
																			$output .= '<a href="'. $link .'" class="mega-menu__panel-list-text">'. $term_bs->name .'</a>';
																		endforeach; 
														$output .= '</div>
																</li>';
												endforeach; 
                                $output .= '</ul>
                                            <a href="'. $catalog_url .'" class="mega-menu__panel-more">
                                                '. __('See all', 'natures-sunshine') .'
                                                <svg width="24" height="24">
                                                    <use xlink:href="'. DIST_URI . '/images/sprite/svg-sprite.svg#arrow-right"></use>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';

        }

		$output .= "</li>{$n}";
	}
    
} 