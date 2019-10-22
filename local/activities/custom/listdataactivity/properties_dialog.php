<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
Loader::includeModule('iblock');
$iblocks = IblockTable::getList(['filter' => ['ACTIVE' => 'Y'], 'select' => ['ID', 'TYPE', 'NAME']])->fetchCollection();
$sortFieldList = [
    "ID"=>'По идентификатору',
    "NAME"=>'По наименованию',
    "ACTIVE_FROM"=> 'По дате активности',
    "SORT"=> 'По полю сортировки',
    "TIMESTAMP_X"=> 'По дате последнего изменения'
];
$localPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__);
$curIblockId = $arCurrentValues['iblock_id'] > 0 ? $arCurrentValues['iblock_id'] : $iblocks->getIdList()[0];
$sortName = $arCurrentValues['sort_name'];
$sortValue = $arCurrentValues['sort_value'];
$propCodeList = $arCurrentValues['props_codes'];
$propValueList = $arCurrentValues['props_values'];
CUtil::InitJSCore(['jquery']);
?>

<tr>
    <td align="right" width="40%"><span class="adm-required-field"><?=Loc::getMessage('IBLOCK_NAME')?>:</span></td>
    <td width="60%">
        <select name="iblock_id" class="js-iblock-id-select" >
            <?foreach ($iblocks as $iblock):?>
                <option <? if ($iblock->getId() == $curIblockId) {echo 'selected ';}?> value="<?=$iblock->getId()?>"><?= "{$iblock->getName()} [{$iblock->getId()}]"?></option>
            <?endforeach;?>
        </select>
    </td>
</tr>
<tr>
    <td align="right" width="40%"><span class="adm-required-field"><?= Loc::getMessage('FIELD_SORT')?>:</span></td>
    <td width="60%">
        <select name="sort_name" id="js-select" onchange="">
            <?foreach ($sortFieldList as $code => $name):?>
                <option <? if ($code == $sortName) {echo 'selected ';}?> value="<?=$code;?>"><?= "$name"?></option>
            <?endforeach;?>
        </select>
    </td>
</tr>
<tr>
    <td align="right" width="40%"><span class="adm-required-field"><?= Loc::getMessage('FIELD_SORT_VALUE')?>:</span></td>
    <td width="60%">
        <select name="sort_value">
            <option value="ASK" <? if ($sortValue == 'ASK') {echo 'selected ';}?>><?= Loc::getMessage('ASC')?></option>
            <option value="DESK" <? if ($sortValue == 'DESK') {echo 'selected ';}?>><?= Loc::getMessage('DESC')?></option>
        </select>
    </td>
</tr>
<tr id="block-for-answer">

</tr>
<tr id="btn-iblock-row-add">
    <td align="right" width="40%"><span class="adm-required-field"></span></td>
    <td width="60%">
        <input type="button" class="js-on-row-add-click" value="<?= Loc::getMessage('ADD_FILTER')?> +">
    </td>
</tr>
<script>
    $(function () {
        let lastChoseIblockId = 0;
        let loadIblockProps =  function(iblockId) {
            if (lastChoseIblockId > 0) {
                $('.filter-row').remove();
            }
            lastChoseIblockId = iblockId;
            getFilterRow(iblockId)
                .then(response => {
                    BX.adjust(BX('block-for-answer'),
                        {
                            html: response.data.result
                        }
                    );
                })
                .catch(response => console.log(response));
        }

        let addFilterRowBellow = function(selectedCode = '', value = '') {
            getFilterRow(lastChoseIblockId, selectedCode, value)
                .then(response => {
                    $('#btn-iblock-row-add').before(response.data.result)
                })
                .catch(response => console.log(response));
        }

        let getFilterRow = function (iblockId, selectedCode = '', value = '') {
            return new Promise((resolve, reject) => {
                BX.ajax(
                    {
                        url: '<?= $localPath?>/iblock_props.php?id=' + iblockId + '&selected_code=' + selectedCode + '&value=' + value,
                        method: 'GET',
                        dataType: 'json',
                        onsuccess: function ($data) {
                            return resolve({
                                data: $data,
                                success: true
                            });
                        },
                        onfailure: function ($data) {
                            return reject({
                                data: $data,
                                success: false
                            });
                        }
                    }
                );
            });
        }

        $('.js-iblock-id-select').on("change", function (e) {
            let iblockId = $(this).children("option:selected").val();
            loadIblockProps(iblockId);
        })

        $('.js-on-row-add-click').on("click", function (e) {
            addFilterRowBellow();
        })

        let iblockId = '<?= $curIblockId?>';
        if (iblockId > 0) {
            lastChoseIblockId = iblockId;
            <?foreach ($propValueList as $index => $propValue):?>
                addFilterRowBellow('<?= $propCodeList[$index]?>', '<?= $propValue?>');
            <?endforeach;?>
            addFilterRowBellow();
        }
    });

</script>