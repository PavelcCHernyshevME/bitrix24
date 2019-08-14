<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
/**
 * Всем подзадачам, у которых есть чек-боксы в статусе «не выполнен»
 * необходимо добавить 3 отчета со случайным временем.
 * Комментарий в отчете должен соответствовать названию элемента списка.
*/
/*$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler('tasks',
    \Bitrix\Tasks\Template\CheckListTable::getTableName()
    '\Bitrix\Tasks\Internals\Task\CheckListTable::onUpdate',
    'myFunc'
);*/

/*AddEventHandler('tasks', 'CheckListOnAfterUpdate', function ($i) {
    CEventLog::Log(CEventLog::SEVERITY_ERROR, 1, 'tasks', 1, 'event');
});
AddEventHandler('tasks', 'Bitrix\Tasks\Internals\Task\CheckListTable::onBeforeUpdate', function ($i) {
    CEventLog::Log(CEventLog::SEVERITY_ERROR, 1, 'tasks', 1, 'onBeforeUpdate');
});
AddEventHandler('tasks', '\Bitrix\Tasks\Internals\Task\CheckList::onUpdate', function ($i) {
    CEventLog::Log(CEventLog::SEVERITY_ERROR, 1, 'tasks', 1, 'OnBeforeUpdate');
});*/

\Bitrix\Main\Loader::includeModule('tasks');
//echo \Bitrix\Tasks\Internals\Task\CheckList::onUpdate;
?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>