<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("task editor");
try {
    \Bitrix\Main\Loader::includeModule('tasks');
    $checkListForSubTasks = [
        ['TITLE' => 'Дизайн', 'STATE' => false],
        ['TITLE' => 'Верстка', 'STATE' => false],
        ['TITLE' => 'Бэкенд ', 'STATE' => false],
    ];
    $checkListForParentTasks = [
        ['TITLE' => 'Оценка', 'STATE' => true],
        ['TITLE' => 'Подписание договора', 'STATE' => true],
        ['TITLE' => 'Создание подзадач по проекту', 'STATE' => false],
    ];
    /**
     * @param $taskId
     * @param $taskCreatorId
     * @param array $checkList
     */
    function addCheckList($taskId, $taskCreatorId, array $checkList)
    {
        $task = CTaskItem::getInstance($taskId, $taskCreatorId);
        foreach ($checkList as $item) {
            CTaskCheckListItem::add($task, [
                'TITLE' => $item['TITLE'],
                'IS_COMPLETE' => $item['STATE'] ? 'Y' : 'N'
            ]);
        }
    }

    $dbRes = CTasks::GetList();
    $arTtasks = [];
    while ($task = $dbRes->Fetch()) {
        $isSubtask = $task['PARENT_ID'] > 0;
        if ($isSubtask) {
            addCheckList($task['ID'], $task['CREATED_BY'], $checkListForSubTasks);
        } else {
            addCheckList($task['ID'], $task['CREATED_BY'], $checkListForParentTasks);
        }
    }
} catch (\Throwable $exception) {
    dump($exception->getMessage());
    dump($exception->getTraceAsString());
}
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>