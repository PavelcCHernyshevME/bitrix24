BX.ready(
    function () {
        BX.ajax(
            {
                url: '/local/php_interface/include/js_lib/my_ajax/my_ajax.php',
                method: 'POST',
                dataType: 'json',
                onsuccess: function ($data) {
                    let className = $data.CLASS;
                    let text = $data.TEXT;
                    let createdDiv = BX.create('div',
                        {
                            attrs: {
                                'class': className
                            },
                            text: text
                        }
                    );
                    BX.adjust(BX('block-for-answer'),
                        {
                            children: [createdDiv]
                        }
                    )
                },
                onfailure: function ($data) {
                    console.error($data)
                }
            }
        );
    }
)