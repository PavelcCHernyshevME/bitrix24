<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("task editor");
\Bitrix\Main\Loader::includeModule('tasks');
$userColl = \Bitrix\Main\UserTable::getList()->fetchCollection();
$userIds = $userColl->getIdList();
$dbRes = CTasks::GetList();
$arTtasks = [];
function createTask($respons, $creator, $title, $desc, $deadline)
{
    $task = new \Bitrix\Tasks\Item\Task();
    $task->setData([
        'TITLE' => $title,
        'DESCRIPTION' => $desc,
        'CREATED_BY' => $creator,
        'RESPONSIBLE_ID' => $respons,
        'DEADLINE' => $deadline
    ]);
    $task->save();
}
$counter = (int)$dbRes->SelectedRowsCount();
$deadlineTS = time() + 86400 * 31;
$date = \Bitrix\Main\Type\DateTime::createFromTimestamp($deadlineTS);
$deadline = $date->format('d.m.Y H:i:s');
while ($task = $dbRes->Fetch()) {
    createTask(getRandomUserId($userIds), getRandomUserId($userIds), 'TITLE ' . ++$counter, 'DESC ' . $counter, $deadline);
    createTask(getRandomUserId($userIds), getRandomUserId($userIds), 'TITLE ' . ++$counter, 'DESC ' . $counter, $deadline);
}
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>