<?php
require_once MODELS_ROOT . '/model.service.php';
require_once CONTROLLERS_ROOT . '/controller.service.php';
require_once CONTROLLERS_ROOT . '/controller.view.php';

$Netflix = model();
$Netflix = controller($Netflix);

$views = [];
$bills = [];


$name = $_GET['user'];
$User = $Netflix->getUser($name);

Main::each($User->bill(), function ($Bill) use (&$bills) {
    $bills[] = [
        'price' => $Bill->format(),
        'date' => $Bill->date()->format('d/m/Y')
    ];
});
$views[] = [
     'name' => is_null($User->name()) ? '': $User->name()->get(),
     'payed' => is_null($User->payed()) ? '': $User->payed()->format(),
     'unpayed' => is_null($User->unpayed()) ? '': $User->unpayed()->format(),
     'advance' => is_null($User->advance()) ? '': $User->advance()->format(),
     'bill' => $bills
];


return view('user', $views);
