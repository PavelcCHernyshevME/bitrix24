BX.ready(
    function () {
        let button = BX("js-click-me");
        BX.bind(button, 'click', function () {
            BX.ajax(
                {
                    url: '/local/php_interface/include/js_lib/my_slider/images.php',
                    method: 'POST',
                    dataType: 'html',
                    onsuccess: function ($data) {
                        let createdDiv = BX.create('div',
                            {
                                attrs: {
                                    'id': 'db-items'
                                },
                                html: $data
                            }
                        );
                        BX.adjust(BX('block-for-answer'),
                            {
                                children: [createdDiv]
                            }
                        )
                        sliderInit();
                        BX.unbindAll(button);
                    },
                    onfailure: function ($data) {
                        console.error($data)
                    }
                }
            );

        });

        function sliderInit() {
            let obImageView = BX.viewElementBind(
                'db-items',
                {
                    showTitle: true,
                    lockScroll: false
                },
                function (node) {
                    return BX.type.isElementNode(node) && (node.getAttribute('data-bx-viewer')
                        || node.getAttribute('data-bx-image'));
                }
            );
        }
    }
);