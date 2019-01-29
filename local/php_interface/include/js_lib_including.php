<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
/**
 * Created by PhpStorm.
 * User: Pavel.Chernyshev
 * Date: 23.01.2019
 * Time: 9:32
 */
global $APPLICATION;

if ($APPLICATION->GetCurPage(true) == '/BXJS/index.php') {
    CUtil::InitJSCore(['my_ext']);
}
if ($APPLICATION->GetCurPage(true) == '/BXJS/ajax.php') {
    CUtil::InitJSCore(['my_ajax']);
}
if ($APPLICATION->GetCurPage(true) == '/BXJS/popup.php') {
    CUtil::InitJSCore(['popup', 'my_popup', 'my_ajax']);
}
if ($APPLICATION->GetCurPage(true) == '/BXJS/viewer.php') {
    CUtil::InitJSCore(['viewer', 'my_slider']);
}
