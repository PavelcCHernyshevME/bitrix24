<?

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/eventhandlers.php')) {
    include $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/eventhandlers.php';
}

function dump($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

function getRandomUserId(array $users) {
    $userPos = rand(0, count($users) - 1);
    return $users[$userPos];
}

