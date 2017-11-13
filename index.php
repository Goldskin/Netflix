<?php
// ini_set('xdebug.var_display_max_depth', 10);
// ini_set('xdebug.var_display_max_children', 256);
// ini_set('xdebug.var_display_max_data', 1024);

define('CLASSES_ROOT', getcwd() . '/classes');
define('VIEWS_ROOT', getcwd() . '/views');
define('CONTROLLERS_ROOT', getcwd() . '/controllers');
define('MODELS_ROOT', getcwd() . '/models');
define('DATAS_ROOT', getcwd() . '/datas');


define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));



require_once ROOT . 'core/core.model.php';
require_once ROOT . 'core/core.controller.php';

$param = explode ('/', $_GET['p']);
$controller = $param[0];
$action = isset($param[1]) ? $param[1] : 'index.php';
$path = 'controller/' . $controller . '.controller.php';
if (file_exists($path)) {
    require $path;
    $controller = new $controller ();
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
       echo '404';
   }
} else {
   echo '404';
}
