<?

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/eventhandlers.php')) {
    include $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/eventhandlers.php';
}
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/constans.php')) {
    include $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/constans.php';
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

function isPageTaskDetail($url)
{
    $regExpr = '/\/company\/personal\/user\/([\d]+)\/tasks\/task\/view\/([\d]+)\//';
    $matches = [];
    preg_match($regExpr, $url, $matches);
    return count($matches) > 0;
}
