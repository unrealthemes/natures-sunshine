<?php


/**
 * Echo breadcrumbs
 * @return void
 */
function the_breadcrumbs(): void
{
    breadcrumb_trail([
        'container'       => 'div',
        'container_class' => 'breadcrumbs',
        'before'          => '',
        'after'           => '',
        'browse_tag'      => 'h2',
        'list_tag'        => 'ul',
        'list_class'      => 'breadcrumbs__list',
        'item_tag'        => 'li',
        'item_class'      => 'breadcrumbs__list-item',
        'separator'       => '<span class="separator">/</span>',
        'labels'          => [
            'browse'              => esc_html__('Browse:', 'breadcrumb-trail'),
            'aria_label'          => esc_attr_x('Breadcrumbs', 'breadcrumbs aria label', 'breadcrumb-trail'),
            'home'                => esc_html__('Home', 'breadcrumb-trail'),
            'error_404'           => esc_html__('404 Not Found', 'breadcrumb-trail'),
            'archives'            => esc_html__('Archives', 'breadcrumb-trail'),
            'search'              => esc_html__('Search results for: %s', 'breadcrumb-trail'),
            'paged'               => esc_html__('Page %s', 'breadcrumb-trail'),
            'paged_comments'      => esc_html__('Comment Page %s', 'breadcrumb-trail'),
            'archive_minute'      => esc_html__('Minute %s', 'breadcrumb-trail'),
            'archive_week'        => esc_html__('Week %s', 'breadcrumb-trail'),
            
            // "%s" is replaced with the translated date/time format.
            'archive_minute_hour' => '%s',
            'archive_hour'        => '%s',
            'archive_day'         => '%s',
            'archive_month'       => '%s',
            'archive_year'        => '%s',
        ],
        'post_taxonomy'   => [
            // 'post'  => 'post_tag', // 'post' post type and 'post_tag' taxonomy
            // 'book'  => 'genre',    // 'book' post type and 'genre' taxonomy
        ],
        'echo'            => true
    ]);
}

/**
 * Shows a breadcrumb for all types of pages.  This is a wrapper function for the Breadcrumb_Trail class,
 * which should be used in theme templates.
 *
 * @param array $args Arguments to pass to Breadcrumb_Trail.
 *
 * @return void
 * @since  0.1.0
 * @access public
 */
function breadcrumb_trail(array $args = array())
{
    
    $breadcrumb = apply_filters('breadcrumb_trail_object', null, $args);
    
    if ( ! is_object($breadcrumb)) {
        $breadcrumb = new App\Classes\BreadcrumbsClass($args);
    }
    
    return $breadcrumb->trail();
}