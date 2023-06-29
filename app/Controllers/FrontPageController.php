<?php

namespace App\Controllers;

class FrontPageController
{
    public function __construct()
    {
        add_filter('body_class', [$this, 'removeBodyClasses']);
    }
    
    public function removeBodyClasses($classes): array
    {
        if (is_front_page()) {
            return ['home'];
        }
        
        return $classes;
    }
}