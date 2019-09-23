<?php
/**
 * Created by PhpStorm.
 * User: Pavel.Chernyshev
 * Date: 21.01.2019
 * Time: 10:14
 */

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Tasks\Internals\Task\CheckListTable;

AddEventHandler("main", "OnEpilog", ["Eventhandlers", "onEpilogHandler"]);
AddEventHandler('tasks', 'OnTaskAdd', ['Eventhandlers', 'onTaskAdd']);
AddEventHandler('tasks', 'OnTaskUpdate', ['Eventhandlers', 'onTaskUpdate']);
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
     */
    function onTaskAdd($taskId, $fields)
    {
        try {
            $creatorId = $fields['CREATED_BY'];
            $createdDate = $fields['CREATED_DATE'];
            Loader::includeModule('highloadblock');
            $hl = Bitrix\Highloadblock\HighloadBlockTable::getById(TASKS_STACK_HL_ID)->fetch();
            $hlEntity = HighloadBlockTable::compileEntity($hl);
            $hlClass = $hlEntity->getDataClass();
            $hlClass::add([
                'UF_TASK_ID' => $taskId,
                'UF_CREATED_BY' => $creatorId,
                'UF_CREATED_DATE' => $createdDate,
            ]);
        } catch (Exception $e) {
            //todo: log write
        }
    }

    /**
     * @param $taskId
     * @param $fields
     * @throws LoaderException
     */
    function onTaskUpdate($taskId, $fields)
    {
        if (Loader::includeModule('crm')) {
            $prevFields = $fields['META:PREV_FIELDS'];
            $dealId = getBindDealId($prevFields['UF_CRM_TASK']);
            $hasBindDeal = $dealId > 0;
            $notFineshedBefore = $prevFields['STATUS'] != 5;
            $hasFinishStatus = $fields['STATUS'] == 5;
            if ($hasFinishStatus && $notFineshedBefore && $hasBindDeal) {
                /**
                 * Если есть номер телефона, создаем дело "исходящий звонок"
                 * Ответственный – пользователь закрывший задачу
                 * Заголовок  - «Звонок по сделке НАЗВАНИЕ_СДЕЛКИ»
                 * Иначе, если есть
                 * Ответственный – ответственный за сделку.
                 * Заголовок  - «Письмо по сделке НАЗВАНИЕ_СДЕЛКИ»
                 * Адресат – email из сделки
                 */
                $deal = CCrmDeal::GetList([], ['ID' => $dealId], ['ID', 'ASSIGNED_BY_ID', 'TITLE', 'UF_*'])->GetNext();
                $dealPhone = $deal[UF_NAME_DEAL_PHONE];
                $dealEmail = $deal[UF_NAME_DEAL_EMAIL];
                $caseIsCreated = false;
                if (mb_strlen($dealPhone)) {
                    global $USER;
                    $caseFields = [
                        'TYPE_ID' => CCrmActivityType::Call,
                        'OWNER_TYPE_ID' => CCrmOwnerType::Deal,
                        'OWNER_ID' => $dealId,
                        'RESPONSIBLE_ID' => $USER->GetID(),
                        'DESCRIPTION' => $taskId,
                        'SUBJECT' => $deal['TITLE'],
                    ];
                    $caseIsCreated = (bool)CCrmActivity::Add($caseFields, false);

                } elseif (mb_strlen($dealEmail)) {
                    $caseFields = [
                        'TYPE_ID' => CCrmActivityType::Email,
                        'OWNER_TYPE_ID' => CCrmOwnerType::Deal,
                        'OWNER_ID' => $dealId,
                        'RESPONSIBLE_ID' => $deal['ASSIGNED_BY_ID'],
                        'DESCRIPTION' => $taskId,
                        'SUBJECT' => $deal['TITLE'],
                    ];
                    $caseIsCreated = (bool)CCrmActivity::Add($caseFields, true);
                }
                if ($caseIsCreated) {
                    /**
                     * Переводить сделку в следующую стадию.
                     * Добавить 2 произвольных товара к сделке
                     */
                    $elDeal = new CCrmDeal();
                    $newDealFields = ['STAGE_ID' => 'PREPARATION'];
                    $elDeal->Update($dealId, $newDealFields);

                    CCrmProductRow::SaveRows('D', $dealId, [
                        ['PRODUCT_NAME' => 'table', 'QUANTITY' => 1, 'PRICE' => 600],
                        ['PRODUCT_NAME' => 'chair', 'QUANTITY' => 1, 'PRICE' => 900],
                    ]);
                }
            }
        }
    }



    /**
     * @param $primaty
     * @throws TasksException
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    function onCheckListAfterUpdate($primaty)
    {
        Loader::includeModule('tasks');
        Loader::includeModule('iblock');
        $check = CheckListTable::getByPrimary($primaty)->fetch();
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