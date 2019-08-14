<?php
/**
 * Created by PhpStorm.
 * User: Pavel.Chernyshev
 * Date: 08.08.2019
 * Time: 16:49
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$taskId = $request->get('task_id');
if (!$taskId) {
    exit();
}

$elDbRes = CIBlockElement::GetList([],
    [
        'IBLOCK_ID' => TASKS_FOR_TESTING_IBLOCK_ID,
    ],
    false,
false,
    [
        'ID',
        'NAME',
        'IBLOCK_SECTION_ID'
    ]
);
$elementsBySectId = [];
while ($el = $elDbRes->GetNext()) {
    $elementsBySectId[$el['IBLOCK_SECTION_ID']][] = $el;
}
$sectDbRes = CIBlockSection::GetList([], ['IBLOCK_ID' => TASKS_FOR_TESTING_IBLOCK_ID], false, ['ID', 'NAME', 'UF_RESPONSIBLE_ID']);
$sections = [];
while ($sect = $sectDbRes->GetNext()) {
    $sections[] = $sect;
}
global $USER;
\Bitrix\Main\Loader::includeModule('tasks');
foreach ($sections as $section) {
    $task = new \Bitrix\Tasks\Item\Task();
    $task->setData([
        'TITLE' => $section['NAME'],
        'CREATED_BY' => $USER->GetID(),
        'RESPONSIBLE_ID' => $section['UF_RESPONSIBLE_ID'],
        'DEADLINE' => $deadline,
        'PARENT_ID' => $taskId
    ]);
    $task->save();
    $task = CTaskItem::getInstance($task->getId(), $USER->GetID());
    foreach ($elementsBySectId[$section['ID']] as $item) {
        CTaskCheckListItem::add($task, [
            'TITLE' => $item['NAME'],
            'IS_COMPLETE' => 'N'
        ]);
    }
}
echo json_encode(['success' => true]);


