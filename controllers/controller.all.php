<?php
require_once MODELS_ROOT . '/model.service.php';
require_once CONTROLLERS_ROOT . '/controller.service.php';

$Netflix = model ();
$Netflix = controller($Netflix);

$views = [];
$Netflix::each($Netflix->user(), function ($User) use (&$views) {
    $views[] = [
         'name' => is_null($User->name()) ? '': $User->name()->get(),
         'payed' => is_null($User->payed()) ? '': $User->payed()->format(),
         'unpayed' => is_null($User->unpayed()) ? '': $User->unpayed()->format(),
         'advance' => is_null($User->advance()) ? '': $User->advance()->format()
    ];
});

require_once VIEWS_ROOT . '/view.all.php';

