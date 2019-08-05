<?php
/**
 * Created by PhpStorm.
 * User: Pavel.Chernyshev
 * Date: 21.01.2019
 * Time: 10:14
 */

AddEventHandler("main", "OnEpilog", Array("Eventhandlers", "onEpilogHandler"));
AddEventHandler('tasks', 'OnTaskAdd', ['Eventhandlers', 'onTaskAdd']);


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

    function onTaskAdd($taskId, $fields)
    {
        dump($fields);
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
    }
}