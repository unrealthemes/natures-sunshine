<?php

namespace App\Walkers;

use Walker_Nav_Menu;
use WP_Post;

class ParentWalker extends Walker_Nav_Menu
{
    public function get_item_attrs(WP_Post $item): string
    {
        $atts           = [];
        $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = ! empty($item->target) ? $item->target : '';
        if ('_blank' === $item->target && empty($item->xfn)) {
            $atts['rel'] = 'noopener'; 
        } else {
            $atts['rel'] = $item->xfn;
        }
        $atts['href']         = ! empty($item->url) ? $item->url : '';
        $atts['aria-current'] = $item->current ? 'page' : '';
        
        $attributes = '';
        
        foreach ($atts as $attr => $value) {
            if (is_scalar($value) && '' !== $value && false !== $value) {
                $value      = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        return $attributes;
    }
    
    public function is_current_page_item(WP_Post $item): bool
    {
        return in_array('current-menu-item', $item->classes);
    }
    
}