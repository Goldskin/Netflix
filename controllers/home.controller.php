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
        $views['users']  = [];
        $views['billed']  = new Price ();
        $views['payed']   = new Price ();
        $views['unpayed'] = new Price ();
        $views['advance'] = new Price ();

        $views['title'] = (is_null($Netflix->name()) ? 'Repartition' : $Netflix->name()->get()) . ' - Home';

        $Netflix::each($Netflix->user(), function ($User) use (&$views)
        {

            if (!is_null($User->payed()) || !is_null($User->unpayed()) || !is_null($User->advance())) {
                $current = [];

                $current['name']    = is_null($User->name())     ? ''  : $User->name()->get();
                $current['id']      = is_null($User->id())       ? ''  : $User->id();
                $current['url']     = is_null($User->id()) ? '#' : URL . '/user/' . $User->id();

                $current['billed']  = [
                    'value' => is_null($User->getBills()) ? ''  : $User->getBills()->format(),
                    'class' => ''
                ];

                $current['payed']   = [
                    'value' => is_null($User->payed())    ? ''  : $User->payed()->format(),
                    'class' => is_null($User->payed())    ? ''  : Price::getStatus($User->payed()->status()->get())
                ];

                $current['unpayed'] = [
                    'value' => is_null($User->unpayed())  ? ''  : $User->unpayed()->format(),
                    'class' => is_null($User->unpayed())  ? ''  : Price::getStatus($User->unpayed()->status()->get())
                ];

                $current['advance'] = [
                    'value' => is_null($User->advance())  ? ''  : $User->advance()->format(),
                    'class' => is_null($User->advance())  ? ''  : Price::getStatus($User->advance()->status()->get())
                ];


                $views['users'][] = $current;

                is_null($User->getBills()) ? 0 : $views['billed'] ->set($User->getBills());
                is_null($User->payed())    ? 0 : $views['payed']  ->set($User->payed());
                is_null($User->unpayed())  ? 0 : $views['unpayed']->set($User->unpayed());
                is_null($User->advance())  ? 0 : $views['advance']->set($User->advance());
            }
        });
        $views['billed']  = $views['billed'] ->get() == 0 ? '' : $views['billed'] ->format();
        $views['payed']   = $views['payed']  ->get() == 0 ? '' : $views['payed']  ->format();
        $views['unpayed'] = $views['unpayed']->get() == 0 ? '' : $views['unpayed']->format();
        $views['advance'] = $views['advance']->get() == 0 ? '' : $views['advance']->format();

        $this
            ->set($views)
            ->add('index');
    }
}
