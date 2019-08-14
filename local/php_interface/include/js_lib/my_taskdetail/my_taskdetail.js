function startTesting() {
    let taskId = getTaskId();
    BX.ajax(
        {
            url: '/local/php_interface/include/js_lib/my_taskdetail/startTesting.php?task_id=' + taskId,
            method: 'POST',
            dataType: 'json',
            onsuccess: function ($data) {
                location.reload();
            },
            onfailure: function ($data) {
                console.error($data)
            }
        }
    );
}

function getTaskId() {
    let url = window.location.href;
    let taskId = url.replace(/.*view/i, '').replace(/\//ig, '');
    return taskId;
}
BX.ready(


    function() {


        let isItemAdded = false;
        BX.bind(BX('task-detail-create-button'), 'click', function () {
            if (!isItemAdded) {
                addPopupItem();
                isItemAdded = true;
            }
        });

        BX('task-detail-create-button')
        /**
         * добавляет элемент в выпадающий список
         */
        function addPopupItem() {
            let popupMenuAdd = BX('popup-window-content-menu-popup-task-detail-create-button');
            let menuItems = BX.findChildByClassName(popupMenuAdd, 'menu-popup-items');
            let createdDiv = BX.create('div',
                {
                    html: '<a class="menu-popup-item menu-popup-item menu-popup-no-icon" id="start-testing" href="javascript:startTesting();"><span class="menu-popup-item-icon"></span><span class="menu-popup-item-text">Начать тестирование</span></a>'
                }
            );
            BX.adjust(menuItems,
                {
                    children: [createdDiv]
                }
            )
        }
    }
);