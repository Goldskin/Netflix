<?php

require_once MODELS_ROOT . 'service.model.php';

$ServiceModel = (new serviceModel ())->getModel();
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
echo json_encode(Main::$all);