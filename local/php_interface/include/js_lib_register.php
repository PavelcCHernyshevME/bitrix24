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
        'js' => '/local/php_interface/include/js_lib/my_ext.js',
        'css' => '/local/php_interface/include/js_lib/my_ext.css',
        'lang' => '/local/php_interface/include/js_lib/lang/' . LANGUAGE_ID . '/my_ext.php',
        'rel' => []
    ]
];
foreach ($arJsLibs as $jsLibName => $options) {
    CJSCore::RegisterExt($jsLibName, $options);
}
