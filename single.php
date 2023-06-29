<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */

get_header();

switch (get_post_type()) {
    case 'project':
        get_template_part('template-parts/project/single');
        break;
    default:
        get_template_part('template-parts/blog/content', 'single');
        break;
}

get_footer();