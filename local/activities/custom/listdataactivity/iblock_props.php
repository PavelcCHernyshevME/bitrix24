<?php
/**
 * Created by PhpStorm.
 * User: Pavel.Chernyshev
 * Date: 23.01.2019
 * Time: 10:31
 */

use Bitrix\Main\Context;
use Bitrix\Main\Loader;

define('NO_KEEP_STATISTIC', true);
define('PUBLIC_AJAX_MODE', true);
define('NO_AGENT_CHECK', true);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
$fieldList = [
    "ID"=>'Идентификтор',
    "NAME"=>'Название',
    "ACTIVE_FROM"=> 'Дата активности',
    "SORT"=> 'Сортировка',
];
$request = Context::getCurrent()->getRequest();
$result = '';
if (($id = $request->get('id')) && Loader::includeModule('iblock')) {
    $selectedCode = $request->get('selected_code');
    $value = $request->get('value');
    $dbRes = CIBlock::GetProperties($id);
    $result .= '<tr class="filter-row"><td align="right" width="40%" class="adm-detail-content-cell-l"><span class="adm-required-field">Свойство: </span></td><td width="40%"><select name="props_codes[]">';
    foreach ($fieldList as $code => $name) {
        $selectedTag = $code == $selectedCode ? 'selected' : '';
        $result .= '<option ' .$selectedTag. ' value="' . $code . '">' . $name . '</option>';
    }
    while ($propInfo = $dbRes->GetNext()) {
        $code = 'PROPERTY_'.$propInfo['CODE'];
        $name = $propInfo['NAME'];
        $selectedTag = $code == $selectedCode ? 'selected' : '';
        $result .= '<option ' .$selectedTag. ' value="' . $code . '">' . $name . '</option>';
    }
    $result .= '</select>&nbsp;<input type="text" name="props_values[]" placeholder="Значение" value="'. $value.'"></td></tr>';
}

echo json_encode(['result' => $result]);