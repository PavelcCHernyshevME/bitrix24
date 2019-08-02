<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("task editor");
$vitaliyId = 4;
$ivanId = 3;
try {
    \Bitrix\Main\Loader::includeModule('tasks');
    $tastsDbRes = CTasks::GetList();
    while ($task = $tastsDbRes->GetNext()) {
        $isSubtask = $task['PARENT_ID'] > 0;
        $checkLDBRes = \Bitrix\Tasks\Internals\Task\CheckListTable::getList([
            'filter' => ['TASK_ID' => $task['ID']],
            'select' => ['IS_COMPLETE']]
        );
        while ($checkListItem = $checkLDBRes->fetch()) {
            $taskMemb = new CTaskMembers();
            if ($isSubtask && $checkListItem['IS_COMPLETE'] == 'N') {
                //Добавляем двух наблюдателей
                $taskMemb->Add(['TASK_ID' => $task['ID'], 'USER_ID' => $vitaliyId, 'TYPE' => 'U']);
                $taskMemb->Add(['TASK_ID' => $task['ID'], 'USER_ID' => $ivanId, 'TYPE' => 'U']);
                break;
            }
            if (!$isSubtask && $checkListItem['IS_COMPLETE'] == 'Y') {
                //Добавляем одного соисполнителя
                $taskMemb->Add(['TASK_ID' => $task['ID'], 'USER_ID' => $vitaliyId, 'TYPE' => 'A']);
                break;
            }
        }
    }
} catch (\Throwable $exception) {
    dump($exception->getMessage());
    dump($exception->getTraceAsString());
}
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>