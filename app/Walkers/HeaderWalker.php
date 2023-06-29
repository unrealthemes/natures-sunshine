<?php

namespace App\Walkers;

class HeaderWalker extends ParentWalker
{
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $classes   = empty($item->classes) ? array() : (array)$item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = implode(' ', array_filter($classes));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $output .= '<li' . $class_names . '>';
        
        $attributes = $this->get_item_attrs($item);
        
        $item_output = '<a' . $attributes . '>';
        $item_output .= $item->title;
        $item_output .= '</a>';
        
        $output .= $item_output;
    }
    
    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= "</li>";
    }
    
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= "<ul>";
    }
    
    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= "</ul>";
    }
    
    public function walk($elements, $max_depth, ...$args)
    {
        $output = '';
        
        if ($max_depth < -1 || empty($elements)) {
            return $output;
        }
        
        $parent_field = $this->db_fields['parent'];
        
        if (-1 == $max_depth) {
            $empty_array = array();
            foreach ($elements as $e) {
                $this->display_element($e, $empty_array, 1, 0, $args, $output);
            }
            
            return $output;
        }
        
        $top_level_elements = array();
        $children_elements  = array();
        foreach ($elements as $e) {
            if (empty($e->$parent_field)) {
                $top_level_elements[] = $e;
            } else {
                $children_elements[$e->$parent_field][] = $e;
            }
        }
        
        if (empty($top_level_elements)) {
            
            $first = array_slice($elements, 0, 1);
            $root  = $first[0];
            
            $top_level_elements = array();
            $children_elements  = array();
            foreach ($elements as $e) {
                if ($root->$parent_field == $e->$parent_field) {
                    $top_level_elements[] = $e;
                } else {
                    $children_elements[$e->$parent_field][] = $e;
                }
            }
        }
        
        foreach ($top_level_elements as $e) {
            $this->display_element($e, $children_elements, $max_depth, 0, $args, $output);
        }
        
        if ((0 == $max_depth) && count($children_elements) > 0) {
            $empty_array = array();
            foreach ($children_elements as $orphans) {
                foreach ($orphans as $op) {
                    $this->display_element($op, $empty_array, 1, 0, $args, $output);
                }
            }
        }
        
        return $output;
    }
}