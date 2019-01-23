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
