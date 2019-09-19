<?

use Bitrix\Main\Loader;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");

Loader::includeModule('crm');

$cCrmLead = new CCrmLead();

$arFields = [
    'TITLE' => 'TEST user 1',
    'STATUS_ID' => 'NEW',
    'ASSIGNED_BY_ID' => 1
];
$cCrmLead->Add($arFields);

$arFields = [
    'TITLE' => 'TEST user 3',
    'STATUS_ID' => 'PROCESSED',
    'ASSIGNED_BY_ID' => 3
];
$cCrmLead->Add($arFields);

$arFields = [
    'TITLE' => 'TEST user 4',
    'STATUS_ID' => 'IN_PROCESS',
    'ASSIGNED_BY_ID' => 4
];
$cCrmLead->Add($arFields);


?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>