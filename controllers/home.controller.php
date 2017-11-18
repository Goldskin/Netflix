<?php

require_once MODELS_ROOT . 'service.model.php';
class HomeController extends Controller
{

    public function index ($userId = 0) {
        $Model = new serviceModel ();
        $Model
            ->load('user',    DATAS_ROOT . '/user.json')
            ->load('price',   DATAS_ROOT . '/price.json')
            ->load('options', DATAS_ROOT . '/options.json');
        $Netflix = $Model->get();

        $views = [];
        $views['resume']  = [];
        $views['payed']   = new Price ();
        $views['unpayed'] = new Price ();
        $views['advance'] = new Price ();

        $Netflix::each($Netflix->user(), function ($User) use (&$views)
        {
            if (!is_null($User->payed()) || !is_null($User->unpayed()) || !is_null($User->advance())) {
                $views['resume'][] = [
                     'name' => is_null($User->name()) ? '' : $User->name()->get(),
                     'id' => is_null($User->id()) ? '' : $User->id(),
                     'payed' => is_null($User->payed()) ? '' : $User->payed()->format(),
                     'unpayed' => is_null($User->unpayed()) ? '' : $User->unpayed()->format(),
                     'advance' => is_null($User->advance()) ? '' : $User->advance()->format(),
                     'url' => URL . '/user/' . (is_null($User->id()) ? '' : $User->id())
                ];
                is_null($User->payed())   ? 0 : $views['payed']  ->set($User->payed());
                is_null($User->unpayed()) ? 0 : $views['unpayed']->set($User->unpayed());
                is_null($User->advance()) ? 0 : $views['advance']->set($User->advance());
            }
        });
        $views['payed']   = $views['payed']  ->get() == 0 ? '' : $views['payed']  ->format();
        $views['unpayed'] = $views['unpayed']->get() == 0 ? '' : $views['unpayed']->format();
        $views['advance'] = $views['advance']->get() == 0 ? '' : $views['advance']->format();

        $this->set($views)->render('index');

        // echo "<pre>", var_dump($views), "</pre>";
    }
}
