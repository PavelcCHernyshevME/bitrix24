<?
use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Новая страница");
Loader::includeModule('crm');

$cCrmLead = new CCrmLead();

$arFields = [
    'TITLE' => 'TEST user 1',
    'STATUS_ID' => 'NEW',
    'COMMENTS' => 'COMMENTS TEST user 1',
    'ASSIGNED_BY_ID' => 1
];
$cCrmLead->Add($arFields);

$arFields = [
    'TITLE' => 'TEST user 3',
    'STATUS_ID' => 'PROCESSED',
    'COMMENTS' => 'COMMENTS TEST user 3',
    'ASSIGNED_BY_ID' => 3
];
$cCrmLead->Add($arFields);

$arFields = [
    'TITLE' => 'TEST user 4',
    'STATUS_ID' => 'IN_PROCESS',
    'COMMENTS' => 'COMMENTS TEST user 4',
    'ASSIGNED_BY_ID' => 4
];
$cCrmLead->Add($arFields);

Loader::includeModule('tasks');

$dbRes = CCrmLead::GetList(['ID' => 'DESK'], ['!STATUS_ID' => 'CONVERTED']);
$crmLesd = new CCrmLead();
$crmDeal = new CCrmDeal();
$fields = ['STATUS_ID' => 'CONVERTED'];
$arLeadsIds = [];
while ($item = $dbRes->GetNext()) {
    $arLeadsIds[] = $item['ID'];
    $rs = $crmLesd->Update($item['ID'], $fields);
    $fields = [
        'TITLE' => $item['TITLE'],
        'COMMENTS' => $item['COMMENTS'],
        'ASSIGNED_BY_ID' => $item['ASSIGNED_BY_ID'],
        'LEAD_ID' => $item['ID'],
    ];
    $crmDeal->Add($fields);
}
$dbDealRes = CCrmDeal::GetList([], ['LEAD_ID' => $arLeadsIds]);
$deals = [];
while ($item = $dbDealRes->GetNext()) {
    $deals[] = $item;
    $task = new \Bitrix\Tasks\Item\Task();
    $task->setData([
        'TITLE' => $item['TITLE'],
        'DESCRIPTION' => 'описание: ' . $item['COMMENTS'],
        'CREATED_BY' => $item['ASSIGNED_BY_ID'],
        'RESPONSIBLE_ID' => $item['ASSIGNED_BY_ID'],
        'UF_CRM_TASK' => 'D_' . $item['ID']
    ]);
    $res = $task->save();
}
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>