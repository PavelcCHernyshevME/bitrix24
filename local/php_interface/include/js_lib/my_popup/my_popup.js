BX.ready(
    function () {
        let popup = new BX.PopupWindow('popup', window.body, {
            autoHide: true,
            closeIcon: true,
            autoHide: true,
            closeByEsc: true,
            titleBar: {
                content: ''
            },
            overlay: {
                opacity: '80'
            }
        });
        popup.setContent(BX('block-for-answer'));
        let button = BX("js-click-me");
        BX.bind(button, 'click', function () {
            popup.show();
        })
    }
)