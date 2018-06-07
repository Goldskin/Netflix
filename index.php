<?php
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT',    str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
define('URL',     '//'. (($_SERVER['SERVER_NAME'] === 'localhost') ? 'localhost/charles' : $_SERVER['SERVER_NAME']));
define('CORRECT_PATH',     ($_SERVER['SERVER_NAME'] === 'localhost') ? '/charles' : '');

define('CLASSES_ROOT',     ROOT . 'classes/');
define('VIEWS_ROOT',       ROOT . 'views/');
define('VENDOR_ROOT',      ROOT . 'vendor/');
define('CONTROLLERS_ROOT', ROOT . 'controllers/');
define('MODELS_ROOT',      ROOT . 'models/');
define('DATAS_ROOT',       ROOT . 'datas/');
define('ROUTE',            ROOT . 'route/');

require VENDOR_ROOT . 'autoload.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->get(CORRECT_PATH . '/all.json', 'get_all_users_handler');
    // {id} must be a number (\d+)
    $r->get('GET', CORRECT_PATH . '/id/{id:\d+}', 'get_user_handler');
    // The /{title} suffix is optional
    $r->addRoute('GET', CORRECT_PATH . '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

var_dump(CORRECT_PATH, $httpMethod, $uri);

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo 'not found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        var_dump($allowedMethods);
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        var_dump($handler, $vars);
        // ... call $handler with $vars
        break;
}