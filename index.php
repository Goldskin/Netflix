<?php
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT',    str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
define('URL',     '//'. (($_SERVER['SERVER_NAME'] === 'localhost') ? 'localhost/charles' : $_SERVER['SERVER_NAME']));

define('CLASSES_ROOT',     ROOT . 'classes/');
define('VIEWS_ROOT',       ROOT . 'views/');
define('CONTROLLERS_ROOT', ROOT . 'controllers/');
define('MODELS_ROOT',      ROOT . 'models/');
define('DATAS_ROOT',       ROOT . 'datas/');
define('ROUTE',            ROOT . 'route/');

require_once ROUTE . 'core.controller.php';
require_once ROUTE . 'collection.controller.php';

$RouteCollection = new RouteCollection($_GET['p']);

$RouteCollection
    ->add((new Route ('user'))->param(1)->controller('user')->action('index'))
    ->add((new Route ('/'))->controller('home')->action('index'))
    ->add((new Route ('bill'))->param(1)->controller('bill')->action('index'));

$RouteCollection->redirect();
