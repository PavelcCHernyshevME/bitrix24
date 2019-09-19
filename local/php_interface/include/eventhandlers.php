<?php
/**
 * Created by PhpStorm.
 * User: Pavel.Chernyshev
 * Date: 21.01.2019
 * Time: 10:14
 */

AddEventHandler("main", "OnEpilog", Array("Eventhandlers", "onEpilogHandler"));
AddEventHandler('tasks', 'OnTaskAdd', ['Eventhandlers', 'onTaskAdd']);
AddEventHandler('tasks', '\Bitrix\Tasks\Internals\Task\CheckList::onAfterUpdate', ['Eventhandlers' ,'onCheckListAfterUpdate']);


class Eventhandlers {
    function onEpilogHandler()
    {
        /**
         * регистрируем js библиотеки
         */
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/js_lib_register.php')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/js_lib_register.php';
        }
        /**
         * подключаем js библиотеки
         */
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/js_lib_including.php')) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/js_lib_including.php';
        }
    }

    /**
     * @param $taskId
     * @param $fields
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    function onTaskAdd($taskId, $fields)
    {
        try {
            $creatorId = $fields['CREATED_BY'];
            $createdDate = $fields['CREATED_DATE'];
            \Bitrix\Main\Loader::includeModule('highloadblock');
            $hl = Bitrix\Highloadblock\HighloadBlockTable::getById(TASKS_STACK_HL_ID)->fetch();
            $hlEntity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hl);
            $hlClass = $hlEntity->getDataClass();
            $hlClass::add([
                'UF_TASK_ID' => $taskId,
                'UF_CREATED_BY' => $creatorId,
                'UF_CREATED_DATE' => $createdDate,
            ]);
        } catch (Exception $e) {

        }
    }

    /**
     * @param $primaty
     * @throws TasksException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    function onCheckListAfterUpdate($primaty)
    {
        \Bitrix\Main\Loader::includeModule('tasks');
        \Bitrix\Main\Loader::includeModule('iblock');
        $check = \Bitrix\Tasks\Internals\Task\CheckListTable::getByPrimary($primaty)->fetch();
        if ($check) {
            $taskId = $check['TASK_ID'];
            $isChacked = $check['IS_COMPLETE'] == 'Y';
            $checkTitle = $check['TITLE'];
            if ($isChacked) {
                $taskInfo = CTasks::GetList([], ['ID' => $taskId], ['CREATED_BY', 'RESPONSIBLE_ID'])->GetNext();
                $taskObj = CTaskItem::getInstance($taskId, $taskInfo['CREATED_BY']);
                global $DB;
                $date = date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL")), time());
                CTaskElapsedItem::add($taskObj, [
                    'TASK_ID' => $taskId,
                    'USER_ID' => $taskInfo['RESPONSIBLE_ID'],
                    'COMMENT_TEXT' => $checkTitle,
                    'CREATED_DATE' => $date,
                    'MINUTES' => 1
                ]);
            }
        }
    }
}