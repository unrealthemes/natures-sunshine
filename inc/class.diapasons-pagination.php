<?php

class UT_Diapasons_Pagination {

    private static $_instance = null;

    static public function getInstance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

    }
    // [1-5] [6-10] [11-15] 16 17 18 19 20 [21-25] [26-29]
    public function the_pagination( $this_page, $query, $current_page ) {

        $data = range(1, $query->found_posts); // data array to be paginated
        $num_results = count($data);
        $theme_dist = DIST_URI;
        $pagination_arr = [];
        $open_diapasons = [];
        $curr_diapason = 0;
        $text = '';
        $k = 1;
        // if pagination pages <= 10, generate simple pagination without diapasons
        if ( $query->max_num_pages <= 10 ) {

            echo "<nav class=\"navigation pagination\" aria-label=\"Записи\">";
                echo "<div class=\"nav-links\">";
    
                    if ( $current_page > 1 ) {
                        // display 'Prev' link
                        echo "<a class=\"prev page-numbers\" href=\"{$this_page}p-" . ($current_page - 1) . "/\">
                                <svg width=\"24\" height=\"24\">
                                    <use xlink:href=\"{$theme_dist}/images/sprite/svg-sprite.svg#chevron-left\"></use>
                                </svg>
                            </a>";
                    } 

                    for ($p=1, $i=1; $p <= $query->max_num_pages; $p++, $i++) {
                        $current = ( $current_page == $p ) ? ' current' : '';
                        echo "<a class=\"page-numbers{$current}\" href=\"{$this_page}p-{$p}/\">
                                {$p}
                            </a>";
                    }

                    if ( $current_page < $query->max_num_pages ) {
                        // display 'Next' link
                        echo "<a class=\"next page-numbers\" href=\"{$this_page}p-" . ($current_page + 1) . "/\"\>
                                <svg width=\"24\" height=\"24\">
                                    <use xlink:href=\"{$theme_dist}/images/sprite/svg-sprite.svg#chevron-right\"></use>
                                </svg>
                            </a>";
                    }
    
                echo "</nav>";
            echo "</div>";

            return;
        }

        for ($p=1, $i=1; $p <= $query->max_num_pages; $p++, $i++) {
            $open = false;
            $close = false;

            if ($i == 6) {
                $i = 1;
            }
            // open diapason
            if ($i == 1) {
                $text .= '[' . $p;
                $open = true;
            }

            if ($current_page == $p) {
                $curr_diapason = $k;
            }

            if ($i == 5) {
                $first_page = $p - 4;
                // current diapason
                if ($curr_diapason == $k) {
                    $current_pages = '';
                    $current_diapason_pages = range($first_page, $p);
                    foreach ( $current_diapason_pages as $current_diapason_page ) {
                        $current = ( $current_page == $current_diapason_page ) ? ' current' : '';
                        $current_pages .= "<a class=\"page-numbers{$current}\" href=\"{$this_page}p-{$current_diapason_page}/\">
                                                {$current_diapason_page}
                                            </a>";
                    }
                    $pagination_arr[ $k ] = $current_pages;
                // close diapason
                } else {
                    $text .= '-' . $p . ']';
                    $pagination_arr[ $k ] = "<a class=\"page-numbers\" href=\"{$this_page}p-{$first_page}/\">
                                                {$text}
                                            </a>";
                }
                
                $text = '';
                $k++;
                $close = true;
            }

            if ( $open && ! $close ) {
                $open_diapasons[] = $p;
            }
        }
        // last diapason which don't closed
        // and if count pages > 2
        if ( ! empty($open_diapasons) && (array_pop((array_slice($open_diapasons, -1, 1))) < $query->max_num_pages) ) {
            $last_diapason_pages = range( array_pop((array_slice($open_diapasons, -1, 1))), $query->max_num_pages );
            $first_p = $last_diapason_pages[0];
            $last_p = array_pop((array_slice($last_diapason_pages, -1, 1)));
            // current diapason
            if ( in_array($current_page, $last_diapason_pages) ) {
                $last_current_pages = '';
                foreach ( $last_diapason_pages as $last_diapason_page ) {
                    $current = ( $current_page == $last_diapason_page ) ? ' current' : '';
                    $last_current_pages .= "<a class=\"page-numbers{$current}\" href=\"{$this_page}p-{$last_diapason_page}/\">
                                                {$last_diapason_page}
                                            </a>";
                }
                $pagination_arr[] = $last_current_pages;
            // diapason
            } else {
                $text = '[' . $first_p . '-' . $last_p . ']';
                $pagination_arr[] = "<a class=\"page-numbers\" href=\"{$this_page}p-{$first_p}/\">
                                        {$text}
                                    </a>";
            } 
        // if count pages = 1
        } elseif ( ! empty($open_diapasons) && (array_pop((array_slice($open_diapasons, -1, 1))) == $query->max_num_pages) ) {
            $single_page = array_pop((array_slice($open_diapasons, -1, 1)));
            $current = ( $current_page == $single_page ) ? ' current' : '';
            $last_current_page = "<a class=\"page-numbers{$current}\" href=\"{$this_page}p-{$single_page}/\">
                                        {$single_page}
                                    </a>";
            $pagination_arr[] = $last_current_page;
        }

        echo "<nav class=\"navigation pagination\" aria-label=\"Записи\">";
            echo "<div class=\"nav-links\">";

                if ( $current_page > 1 ) {
                    // display 'Prev' link
                    echo "<a class=\"prev page-numbers\" href=\"{$this_page}p-" . ($current_page - 1) . "/\">
                            <svg width=\"24\" height=\"24\">
                                <use xlink:href=\"{$theme_dist}/images/sprite/svg-sprite.svg#chevron-left\"></use>
                            </svg>
                        </a>";
                } 

                foreach ( $pagination_arr as $pagination_item ) {
                    echo $pagination_item;
                }

                if ( $current_page < $query->max_num_pages ) {
                    // display 'Next' link
                    echo "<a class=\"next page-numbers\" href=\"{$this_page}p-" . ($current_page + 1) . "/\"\>
                            <svg width=\"24\" height=\"24\">
                                <use xlink:href=\"{$theme_dist}/images/sprite/svg-sprite.svg#chevron-right\"></use>
                            </svg>
                        </a>";
                }

            echo "</nav>";
        echo "</div>";
    }

} 