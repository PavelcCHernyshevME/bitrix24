<?php
/**
 * Created by PhpStorm.
 * User: Pavel.Chernyshev
 * Date: 21.01.2019
 * Time: 10:14
 */

AddEventHandler("main", "OnEpilog", Array("Eventhandlers", "OnEpilogHandler"));


class Eventhandlers {
    function OnEpilogHandler() {
        $arJsLibs = [
            'my_ext' => [
                'js' => '/local/php_interface/include/js_lib/my_ext.js',
                'css' => '/local/php_interface/include/js_lib/my_ext.css',
                'lang' => '/local/php_interface/include/js_lib/lang/' . LANGUAGE_ID . '/my_ext.php',
                'rel' => []
            ]
        ];
        foreach ($arJsLibs as $jsLibName => $options) {
            CJSCore::RegisterExt($jsLibName, $options);
        }
    }
}