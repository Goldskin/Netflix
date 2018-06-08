<?php

require_once MODELS_ROOT . 'service.model.php';
$ServiceModel = (new serviceModel ())->getModel();

$regex = '#(?:\,?(\d+)+)#';
$test = preg_match_all($regex, $vars['id'], $match);



$item = $ServiceModel->getMultipleIds($match[1]);
echo json_encode($item);