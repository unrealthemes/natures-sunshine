<?php

use App\Classes\SingletonClass;

class CommonData extends SingletonClass
{
    protected $data;
    
    protected function __construct()
    {
        /**
         * Наполняем данные data массива, получаем опции и тд
         * Желательно из конструктора вызывать функции которые будут наполнять массив
         */
        
        $this->data = [];
    }
    
}