<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/telephony/lines.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");

$APPLICATION->SetTitle(GetMessage("VI_PAGE_LINES_TITLE"));
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:intranet.popup.provider",
	"",
	array(
		"COMPONENT_NAME" => "bitrix:voximplant.config",
		"COMPONENT_TEMPLATE_NAME" => "",
		"COMPONENT_POPUP_TEMPLATE_NAME" => "",
		"COMPONENT_PARAMS" => 	array()
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
