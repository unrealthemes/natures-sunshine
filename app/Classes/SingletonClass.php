<?php

namespace App\Classes;

class SingletonClass
{
    private static $instances = [];
    
    protected function __clone()
    {
    }
    
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }
    
    public static function getInstance()
    {
        $cls = static::class;
        if ( ! isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        
        return self::$instances[$cls];
    }
}