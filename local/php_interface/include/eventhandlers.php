<?php
/**
 * Created by PhpStorm.
 * User: Pavel.Chernyshev
 * Date: 21.01.2019
 * Time: 10:14
 */

AddEventHandler("main", "OnEpilog", Array("Eventhandlers", "OnEpilogHandler"));


class Eventhandlers {
    function OnEpilogHandler()
    {
        /**
         * регистрируем js библиотеки
         */
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/js_lib_register.php')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/js_lib_register.php';
        }
        /**
         * подключаем js библиотеки
         */
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/js_lib_including.php')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/js_lib_including.php';
        }
    }
}