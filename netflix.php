<?php
ini_set('xdebug.var_display_max_depth', 10);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

require_once 'class.main.php';
require_once 'model.php';
require_once 'controller.php';

$Netflix = model ();
$Netflix = controller($Netflix);

require_once './view.php';

