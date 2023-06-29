<?php

namespace App\Controllers;

use App\Classes\TableOfContentClass;

class BlogController
{
    public function __construct()
    {
        add_filter('the_content', [$this, 'tableOfContent'], 20);
    }
    
    public function tableOfContent($content)
    {
        if ( ! is_singular() && (get_post_type() != 'post')) {
            return $content;
        }
        
        $args = array(
            'container_class' => 'post__table table-of-contents',
            'title'           => 'Table of contents',
        );
        
        $contents = TableOfContentClass::init($args)->make_contents($content);
        
        return $contents . $content;
    }
}