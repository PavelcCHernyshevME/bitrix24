<?php

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CBPListDataActivity extends CBPActivity
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->arProperties = [
            'Title' => '',
            'IdList' => '',
            'IblockId' => '',
            'SortName' => '',
            'SortValue' => '',
            'PropCodeList' => '',
            'PropValueList' => '',
        ];
    }

    /**
     * @return int
     * @throws LoaderException
     */
    public function Execute()
    {
        if (!Loader::includeModule('iblock')) {
            return CBPActivityExecutionStatus::Closed;
        }

        $filter = ['IBLOCK_ID' => $this->IblockId];
        foreach ($this->arProperties['PropValueList'] as $index => $value) {
            $filter[$this->PropCodeList[$index]] = $value;
        }

        $dbRes = CIBlockElement::GetList(
            [
                $this->SortName => $this->SortValue
            ],
            $filter,
            false,
            false,
            [
                'IBLOCK_ID',
                'ID'
            ]
        );

        $idList = [];
        while ($elInfo = $dbRes->GetNext()) {
            $idList[] = $elInfo['ID'];
        }
        $this->IdList = count($idList) > 0 ? implode(',', $idList) : 'Не найдено';
        return CBPActivityExecutionStatus::Closed;
    }

    public static function ValidateProperties($arTestProperties = [], CBPWorkflowTemplateUser $user = null)
    {
        $arErrors = [];
        if ($arTestProperties['IblockId'] <= 0) {
            $arErrors[] = [
                "code" => "emptyIblockId",
                "message" => 'не заполнен ид инфоблока ' . $arTestProperties['IblockId'],
            ];
        }
        foreach ($arTestProperties['PropValueList'] as $index => $propValue) {
            if (mb_strlen($propValue) <= 0) {
                unset($arTestProperties['PropCodeList'][$index]);
                unset($arTestProperties['PropValueList'][$index]);
            }
        }
        if (count($arTestProperties['PropCodeList']) <= 0) {
            $arErrors[] = [
                "code" => "emptyPropCodeList",
                "message" => 'Не выбранны св-ва',
            ];
        }
        if (count($arTestProperties['PropValueList']) <= 0) {
            $arErrors[] = [
                "code" => "emptyPropValueList",
                "message" => 'Не заполненны занчения свойств',
            ];
        }
        return array_merge($arErrors, parent::ValidateProperties($arTestProperties, $user));
    }

    // Статический метод возвращает HTML-код диалога настройки
    // свойств действия в визуальном редакторе. Если действие не имеет
    // свойств, то этот метод не нужен
    public static function GetPropertiesDialog($documentType, $activityName, $arWorkflowTemplate, $arWorkflowParameters, $arWorkflowVariables, $arCurrentValues = null, $formName = "", $popupWindow = null, $siteId = '')
    {
        $runtime = CBPRuntime::GetRuntime();
        $arMap = [
            "IblockId" => "iblock_id",
            "SortName" => "sort_name",
            "SortValue" => "sort_value",
            "PropCodeList" => "props_codes",
            "PropValueList" => "props_values",
        ];

        if (!is_array($arWorkflowParameters)) {
            $arWorkflowParameters = [];
        }
        if (!is_array($arWorkflowVariables)) {
            $arWorkflowVariables = [];
        }

        // Если диалог открывается первый раз, то подгружаем значение
        // свойства, которое было сохранено в шаблоне бизнес-процесса
        if (!is_array($arCurrentValues))
        {
            $arCurrentValues = [
                'iblock_id' => '',
                'sort_name' => '',
                'sort_value' => '',
                'props_codes' => '',
                'props_values' => '',
            ];
            $arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName($arWorkflowTemplate, $activityName);
            if (is_array($arCurrentActivity["Properties"]))
            {
                $arCurrentValues['iblock_id'] = $arCurrentActivity["Properties"]['IblockId'];
                $arCurrentValues['sort_name'] = $arCurrentActivity["Properties"]['SortName'];
                $arCurrentValues['sort_value'] = $arCurrentActivity["Properties"]['SortValue'];
                $arCurrentValues['props_codes'] = $arCurrentActivity["Properties"]['PropCodeList'];
                $arCurrentValues['props_values'] = $arCurrentActivity["Properties"]['PropValueList'];
                foreach ($arCurrentValues['props_values'] as $index => $propValue) {
                    if (mb_strlen($propValue) <= 0) {
                        unset($arCurrentValues['props_codes'][$index]);
                        unset($arCurrentValues['props_values'][$index]);
                    }
                }
            }
        }

        // Код, формирующий диалог, расположен в отдельном файле
        // properties_dialog.php в папке действия.
        // Возвращаем этот код.
        return $runtime->ExecuteResourceFile(
            __FILE__,
            "properties_dialog.php",
            array(
                "arCurrentValues" => $arCurrentValues,
                "formName" => $formName,
            )
        );
    }

    // Статический метод получает введенные в диалоге настройки свойств
    // значения и сохраняет их в шаблоне бизнес-процесса. Если действие не
    // имеет свойств, то этот метод не нужен.
    public static function GetPropertiesDialogValues($documentType, $activityName, &$arWorkflowTemplate, &$arWorkflowParameters, &$arWorkflowVariables, $arCurrentValues, &$arErrors)
    {
        $arErrors = [];

        $arProperties = [
            "IblockId" => $arCurrentValues["iblock_id"],
            "SortName" => $arCurrentValues["sort_name"],
            "SortValue" => $arCurrentValues["sort_value"],
            "PropCodeList" => $arCurrentValues["props_codes"],
            "PropValueList" => $arCurrentValues["props_values"]
        ];

        $arErrors = self::ValidateProperties($arProperties, new CBPWorkflowTemplateUser(CBPWorkflowTemplateUser::CurrentUser));
        if (count($arErrors) > 0) {
            return false;
        }

        $arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName($arWorkflowTemplate, $activityName);
        $arCurrentActivity["Properties"] = $arProperties;

        return true;
    }
}