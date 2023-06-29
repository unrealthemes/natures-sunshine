<?php

/**
 * Выводит переводимую строку
 * @param string $string_key
 *
 * @return void
 */
function _t(string $string_key): void
{
    $strings = require get_template_directory() . '/app/Data/Strings.php';
    
    if (isset($strings[$string_key])) {
        _e($strings[$string_key]);
    }
}

/**
 * Возвращает переводимую строку
 * @param string $string_key
 *
 * @return string
 */
function __t(string $string_key): string
{
    $strings = require get_template_directory() . '/app/Data/Strings.php';
    
    return isset($strings[$string_key]) ? __($strings[$string_key]) : '';
}