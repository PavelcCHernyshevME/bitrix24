<?php

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CBPDealsCompanyActivity extends CBPActivity
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->arProperties = [
            'Title' => '',
            'CompanyId' => '',
            'AllDealsCount' => 0,
            'SuccessDealsCount' => 0,
            'FaileDealsCount' => 0,
            'ProcessDealsCount' => 0,
            'SumWonDeals' => 0,
            'SumNotPaidInvoice' => 0,
            'SumPaidIncoice' => 0,
        ];
    }

    /**
     * @return int
     * @throws LoaderException
     */
    public function Execute()
    {
        if (!Loader::includeModule('crm')) {
            return CBPActivityExecutionStatus::Closed;
        }

        $this->initDealsProps();
        $this->initInvoiceProps();

        return CBPActivityExecutionStatus::Closed;
    }

    protected function initDealsProps()
    {
        $resDeals = CCrmDeal::GetList([], [ 'COMPANY_ID' => $this->CompanyId], ['ID', 'STAGE_ID', 'CLOSED', 'OPPORTUNITY']);
        while ($dealInfo = $resDeals->GetNext()) {
            $closed = $dealInfo['CLOSED'] == 'Y';
            $won = $dealInfo['STAGE_ID'] == 'WON';
            $this->AllDealsCount++;
            if (!$closed) {
                $this->ProcessDealsCount++;
            } elseif ($closed && $won) {
                $this->SuccessDealsCount++;
                $this->SumWonDeals += $dealInfo['OPPORTUNITY'];
            } elseif ($closed && !$won) {
                $this->FaileDealsCount++;
            }
        }
    }

    protected function initInvoiceProps()
    {
        $dbRes = CCrmInvoice::GetList([], ['UF_COMPANY_ID' => $this->CompanyId], false, false, ['PAYED', 'PRICE']);
        while ($item = $dbRes->GetNext()) {
            if ($item['PAYED'] == 'Y') {
                $this->SumPaidIncoice +=$item['PRICE'];
            } else {
                $this->SumNotPaidInvoice += $item['PRICE'];
            }
        }
    }

    public static function ValidateProperties($arTestProperties = [], CBPWorkflowTemplateUser $user = null)
    {
        $arErrors = [];

        if (strlen($arTestProperties["CompanyId"]) <= 0)
        {
            $arErrors[] = [
                "code" => "emptyCompanyId",
                "message" => Loc::getMessage("ERROR_EMPTY_COMPANY_ID") . '  ' . print_r($arTestProperties, true),
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
            "CompanyId" => "company_id",
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
                'company_id' => ''
            ];
            $arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName($arWorkflowTemplate, $activityName);
            if (is_array($arCurrentActivity["Properties"]))
            {
                $arCurrentValues['company_id'] = $arCurrentActivity["Properties"]['CompanyId'];
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
            "CompanyId" => $arCurrentValues["company_id"],
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