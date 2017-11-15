<?php
// ini_set('xdebug.var_display_max_depth', 10);
// ini_set('xdebug.var_display_max_children', 256);
// ini_set('xdebug.var_display_max_data', 1024);


define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

define('CLASSES_ROOT', ROOT . 'classes/');
define('VIEWS_ROOT', ROOT . 'views/');
define('CONTROLLERS_ROOT', ROOT . 'controllers/');
define('MODELS_ROOT', ROOT . 'models/');
define('DATAS_ROOT', ROOT . 'datas/');
define('ROUTE', ROOT . 'route/');

require_once ROUTE . 'core.controller.php';
require_once ROUTE . 'collection.controller.php';
$RouteCollection = new RouteCollection ($_GET['p']);

$RouteCollection
    ->add((new Route ('user/test'))->param(2)->class('user')->method('index'))
    ->add((new Route ('details'))->param(1));

$RouteCollection->redirect();
