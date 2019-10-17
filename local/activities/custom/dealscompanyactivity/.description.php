<?

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arActivityDescription = [
    'NAME' => Loc::getMessage("DEALS_CAMOANY_ACTIVETY_NAME"),
    'DESCRIPTION' => Loc::getMessage("DEALS_CAMOANY_ACTIVETY_DESC"),
    'TYPE' => 'activity',
    'CLASS' => 'DealsCompanyActivity',
    'JSCLASS' => 'BizProcActivity',
    'CATEGORY' => [
        'ID' => 'other',
    ],
    'RETURN' => [
        'AllDealsCount' => [
            'NAME' => Loc::getMessage("HOW_MANY_DEALS_WIN"),
            'TYPE' => 'int',
        ],
        'SuccessDealsCount' => [
            'NAME' => Loc::getMessage("HOW_MANY_DEALS_SUCESS"),
            'TYPE' => 'int',
        ],
        'FaileDealsCount' => [
            'NAME' => Loc::getMessage("HOW_MANY_DEALS_FAILURE"),
            'TYPE' => 'int',
        ],
        'ProcessDealsCount' => [
            'NAME' => Loc::getMessage("HOW_MANY_DEALS_PROCESS"),
            'TYPE' => 'int',
        ],
        'SumWonDeals'=> [
            'NAME' => Loc::getMessage("SUM_WON_DEALS"),
            'TYPE' => 'float',
        ],
        'SumNotPaidInvoice' => [
            'NAME' => Loc::getMessage("SUM_NOT_PAID_INVOICE"),
            'TYPE' => 'float',
        ],
        'SumPaidIncoice' => [
            'NAME' => Loc::getMessage("SUM_PAID_INVOICE"),
            'TYPE' => 'float',
        ]

    ],
    'ADDITIONAL_RESULT' => array('EntityFields')
];

