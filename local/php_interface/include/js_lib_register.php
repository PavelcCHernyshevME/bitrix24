<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
/**
 * Created by PhpStorm.
 * User: Pavel.Chernyshev
 * Date: 23.01.2019
 * Time: 9:32
 */

/**
 * register
 */
$arJsLibs = [
    'my_ext' => [
        'js' => '/local/php_interface/include/js_lib/my_ext/my_ext.js',
        'css' => '/local/php_interface/include/js_lib/my_ext/my_ext.css',
        'lang' => '/local/php_interface/include/js_lib/my_ext/lang/' . LANGUAGE_ID . '/my_ext.php',
        'rel' => []
    ],
    'my_ajax' => [
        'js' => '/local/php_interface/include/js_lib/my_ajax/my_ajax.js',
        'css' => '/local/php_interface/include/js_lib/my_ajax/my_ajax.css',
        'lang' => '/local/php_interface/include/js_lib/my_ajax/lang/' . LANGUAGE_ID . '/my_ajax.php',
        'rel' => []
    ],
    'my_popup' => [
        'js' => '/local/php_interface/include/js_lib/my_popup/my_popup.js',
        'css' => '/local/php_interface/include/js_lib/my_popup/my_popup.css',
        'rel' => []
    ],
    'my_slider' => [
        'js' => '/local/php_interface/include/js_lib/my_slider/my_slider.js',
        'css' => '/local/php_interface/include/js_lib/my_slider/my_slider.css',
        'rel' => []
    ],
    'my_btnlink' => [
        'js' => '/local/php_interface/include/js_lib/my_btnlink/my_btnlink.js',
        'css' => '/local/php_interface/include/js_lib/my_btnlink/my_btnlink.css',
        'rel' => []
    ]
];
foreach ($arJsLibs as $jsLibName => $options) {
    CJSCore::RegisterExt($jsLibName, $options);
}
