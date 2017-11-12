<?php
ini_set('xdebug.var_display_max_depth', 10);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

DEFINE('CLASSES_ROOT', getcwd() . '/classes');
DEFINE('VIEWS_ROOT', getcwd() . '/views');
DEFINE('CONTROLLERS_ROOT', getcwd() . '/controllers');
DEFINE('MODELS_ROOT', getcwd() . '/models');
DEFINE('DATAS_ROOT', getcwd() . '/datas');


if (isset($_GET['user'])) {
    require_once CONTROLLERS_ROOT . '/controller.user.php';
} else {
    require_once CONTROLLERS_ROOT . '/controller.all.php';
}
