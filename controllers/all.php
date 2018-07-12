<?php

require_once MODELS_ROOT . 'service.model.php';

$ServiceModel = (new serviceModel ())->getModel();
header('Content-Type: application/json');
echo json_encode(Main::$all);