<?php

require_once MODELS_ROOT . 'service.model.php';

$ServiceModel = (new serviceModel ())->getModel();
echo json_encode(Main::$all);