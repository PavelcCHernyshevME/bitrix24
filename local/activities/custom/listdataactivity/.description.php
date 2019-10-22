<?

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arActivityDescription = [
    'NAME' => Loc::getMessage("ACT_NAME"),
    'DESCRIPTION' => Loc::getMessage("ACT_DESC"),
    'TYPE' => 'activity',
    'CLASS' => 'ListDataActivity',
    'JSCLASS' => 'BizProcActivity',
    'CATEGORY' => [
        'ID' => 'other',
    ],
    'RETURN' => [
        'IdList' => [
            'NAME' => Loc::getMessage("ID_LIST_VAL_NAME"),
            'TYPE' => 'string',
        ],
    ],
];

