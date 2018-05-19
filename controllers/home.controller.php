<?php

require_once MODELS_ROOT . 'service.model.php';
require_once CONTROLLERS_ROOT . 'all.php';
class HomeController extends Controller
{

    public function index ($userId = 0) {
        $ServiceModel = (new serviceModel ())->getModel();
        $views = [];
        $views['users']  = [];
        $views['billed']  = new Price ();
        $views['payed']   = new Price ();
        $views['unpayed'] = new Price ();
        $views['advance'] = new Price ();
        
        $views['titles']['page'] = (is_null($ServiceModel->options()->name())
            ? 'Repartition'
            : $ServiceModel->options()->name()->get()) . ' - Accueil';

        $ServiceModel::each($ServiceModel->user(), function ($User) use (&$views)
        {
            if (!is_null($User->payed()) || !is_null($User->unpayed()) || !is_null($User->advance())) {
                $current = [];

                $current['name']    = is_null($User->name())     ? ''  : $User->name()->get();
                $current['id']      = is_null($User->id())       ? ''  : $User->id();
                $current['url']     = is_null($User->id) ? '#' : URL . '/user/' . $User->id;

                $current['billed']  = [
                    'value' => is_null($User->getInvoices()) ? ''  : $User->getInvoices()->format(),
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


                // add to totals
                if (!is_null($User->getInvoices())) {
                    $views['billed']->price($User->getInvoices());
                }

                if (!is_null($User->payed())) {
                    $views['payed']->price($User->payed());
                }

                if (!is_null($User->unpayed())) {
                    $views['unpayed']->price($User->unpayed());
                }

                if (!is_null($User->advance())) {
                    $views['advance']->price($User->advance());
                }
            }
        });

        $tarif  = $ServiceModel->getActiveTarif()->total();
        $payed  = $views['payed']->total('price');
        $billed = $views['billed']->total('price');
        $diff   = $billed - $payed;

        if ($diff < 0) {
            $class = Price::getStatus(Price::advance);
        } else if ($diff > 0) {
            if ($diff > $tarif) {
                $class = Price::getStatus(Price::unpayed);
            } else {
                $class = Price::getStatus(Price::paying);
            }
        } else {
            $class = Price::getStatus(Price::payed);
        }


        $views['billed']  = [
            'value' => $billed == 0 ? '' : $views['billed'] ->format(),
            'class' => ''
        ];

        $views['payed']   = [
            'value' => $payed == 0 ? '' : $views['payed']  ->format(),
            'class' => $class
        ];

        $views['unpayed'] = [
            'value' => $views['unpayed']->total('price') == 0 ? '' : $views['unpayed']->format(),
            'class' => $views['unpayed']->total('price') > $tarif ? Price::getStatus(Price::unpayed) : Price::getStatus(Price::paying)
        ];

        $views['advance'] = [
            'value' => $views['advance']->total('price') == 0 ? '' : $views['advance']->format(),
            'class' => Price::getStatus(Price::advance)
        ];

        $views['options'] = getHeader($ServiceModel);

        $this
            ->set($views)
            ->add('index');
    }
}
