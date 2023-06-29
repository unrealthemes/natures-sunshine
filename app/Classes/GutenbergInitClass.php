<?php

namespace App\Classes;

class GutenbergInitClass
{
    public function __construct()
    {
        add_action('acf/init', [$this, 'init_gutenberg_blocks']);
        add_action('enqueue_block_editor_assets', [$this, 'organic_origin_gutenberg_styles']);
        add_action('enqueue_block_editor_assets', [$this, 'organic_origin_gutenberg_scripts']);
        
    }
    
    public function organic_origin_gutenberg_styles()
    {
        
    }
    
    public function organic_origin_gutenberg_scripts()
    {
        
    }
    
    public function init_gutenberg_blocks()
    {
        if (function_exists('acf_register_block_type')) {
            $blocks = require get_template_directory() . '/app/Data/GutenbergBlocks.php';
            foreach ($blocks as $block) {
                acf_register_block_type($block);
            }
        }
    }
    
}