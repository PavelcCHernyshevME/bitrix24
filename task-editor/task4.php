<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
/**
 * Всем подзадачам, у которых есть чек-боксы в статусе «не выполнен»
 * необходимо добавить 3 отчета со случайным временем.
 * Комментарий в отчете должен соответствовать названию элемента списка.
*/
try {
    \Bitrix\Main\Loader::includeModule('tasks');
    $tastsDbRes = CTasks::GetList([], ['>PARENT_ID' => 0]);
    while ($task = $tastsDbRes->GetNext()) {
        $checkLDBRes = \Bitrix\Tasks\Internals\Task\CheckListTable::getList([
                'filter' => ['TASK_ID' => $task['ID']],
                'select' => ['IS_COMPLETE', 'TITLE']]
        );
        while ($checkListItem = $checkLDBRes->fetch()) {
            $taskMemb = new CTaskMembers();
            if ($checkListItem['IS_COMPLETE'] == 'N') {
                $oTask = CTaskItem::getInstance($task['ID'], $task['CREATED_BY']);
                for ($i = 0; $i < 3; $i++) {
                    CTaskElapsedItem::add($oTask, [
                        'TASK_ID' => $task['ID'],
                        'USER_ID' => $task['CREATED_BY'],
                        'COMMENT_TEXT' => $checkListItem['TITLE'],
                        'MINUTES' => rand(1, 3600)
                    ]);
                }
                break;
            }
        }
    }
} catch (\Throwable $exception) {
    dump($exception->getMessage());
    dump($exception->getTraceAsString());
}


?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>