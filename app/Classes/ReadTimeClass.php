<?php

namespace App\Classes;

class ReadTimeClass
{
    /**
     * @param $post_id
     * @param $prefix
     * @param $suffix
     *
     * @return void
     */
    public static function show($post_id, $prefix = '', $suffix = ''): void
    {
        echo self::get($post_id, $prefix, $suffix);
    }
    
    /**
     * @param $post_id
     * @param $prefix
     * @param $suffix
     *
     * @return string
     */
    public static function get($post_id, $prefix = '', $suffix = ''): string
    {
        $content     = get_post_field('post_content', $post_id);
        $count_words = str_word_count(strip_tags($content));
        
        $read_time = ceil($count_words / 250);
        
        return $prefix . ' ' . $read_time . ' ' . $suffix;
    }
}