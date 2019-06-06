console.log('loaded 1' );

BX.ready(
    function () {
        console.log('loaded');
        let panel = BX('company_1_details_tabs_menu');
        let btnDeal = BX.findChild(panel, {
            attribute: {
                'data-tab-id': "tab_deal"
            }
        });
        let btnDealLink = BX.findChild(btnDeal, {
            tag: 'a'
        });
        BX.bind(btnDealLink, 'click', function () {
            BX.addCustomEvent('onAjaxSuccess', function(){
                let ajaxBtmPanel = BX('crm_deal_list_v12_company_details_toolbar');
                let creatDealBtn = BX.findChild(ajaxBtmPanel, {tag: 'a'});
                creatDealBtn.setAttribute('href', '/company/personal/user/');
            });
        });
    }
)