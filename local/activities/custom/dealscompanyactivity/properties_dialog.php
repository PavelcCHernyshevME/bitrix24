<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc; ?>
<tr>
    <td align="right" width="40%"><span class="adm-required-field"><?= Loc::getMessage('DEALS_COMAPNY_ACTIVITY_COMPANY_ID')?>:</span></td>
    <td width="60%">
        <input type="text" name="company_id" id="id_company_id" value="<?= htmlspecialcharsbx($arCurrentValues["company_id"]) ?>" size="50">
        <input type="button" value="..." onclick="BPAShowSelector('id_company_id', 'string');">
    </td>
</tr>
