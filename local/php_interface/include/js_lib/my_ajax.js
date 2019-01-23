BX.ready(
    function () {
        BX.ajax(
            {
                url: '/local/php_interface/include/js_lib/my_ajax.php',
                method: 'POST',
                dataType: 'json',
                onsuccess: function ($data) {
                    let className = $data.CLASS;
                    let text = $data.TEXT;
                    let includingData = '<div class="' + className + '">' + text + '</div>'
                    BX('block-for-answer').innerHTML = includingData;
                },
                onfailure: function ($data) {
                    console.error($data)
                }
            }
        );
    }
)