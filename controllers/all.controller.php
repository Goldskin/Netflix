<?php
require_once MODELS_ROOT . 'service.model.php';
require_once CONTROLLERS_ROOT . 'view.controller.php';

$Netflix = model();

$views = [];
$views['total']['payed'] = 0;
$views['total']['unpayed'] = 0;
$views['total']['advance'] = 0;

$Netflix::each($Netflix->user(), function ($User) use (&$views)
{
    if (!is_null($User->payed()) || !is_null($User->unpayed()) || !is_null($User->advance())) {
        $views['resume'][] = [
             'name' => is_null($User->name()) ? '' : $User->name()->get(),
             'id' => is_null($User->id()) ? '' : $User->id(),
             'payed' => is_null($User->payed()) ? '' : $User->payed()->format(),
             'unpayed' => is_null($User->unpayed()) ? '' : $User->unpayed()->format(),
             'advance' => is_null($User->advance()) ? '' : $User->advance()->format()
        ];
        $views['total']['payed'] += is_null($User->payed()) ? 0 : $User->payed()->get();
        $views['total']['unpayed'] += is_null($User->unpayed()) ? 0 : $User->unpayed()->get();
        $views['total']['advance'] += is_null($User->advance()) ? 0 : $User->advance()->get();
    }
});

$views['total']['payed'] = $views['total']['payed'] == 0 ? '' : $views['total']['payed'] . ' &euro;';
$views['total']['unpayed'] = $views['total']['unpayed'] == 0 ? '' : $views['total']['unpayed'] . ' &euro;';
$views['total']['advance'] = $views['total']['advance'] == 0 ? '' : $views['total']['advance'] . ' &euro;';

return view('all', $views);
