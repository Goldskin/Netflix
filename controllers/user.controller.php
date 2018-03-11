<?php

require_once MODELS_ROOT . 'service.model.php';
require_once CONTROLLERS_ROOT . 'all.php';
class UserController extends Controller
{

    public function index ($userId = 0) {
        $ServiceModel = (new serviceModel ())->getModel();

        $userId = intval($userId);

        $User = $ServiceModel->getId(intval($userId));

        $views = [];

        $views['user']['billed']  = [
            'value' => is_null($User->getBills()) ? '': $User->getBills()->format(),
            'class' => ''
        ];

        $views['user']['payed'] = [
            'value' => is_null($User->payed()) ? '' : $User->payed()->format(),
            'class' => is_null($User->payed()) ? '' : Price::getStatus($User->payed()->status()->get())
        ];

        $views['user']['unpayed'] = [
            'value' => is_null($User->unpayed()) ? '' : $User->unpayed()->format(),
            'class' => is_null($User->unpayed()) ? '' : Price::getStatus($User->unpayed()->status()->get())
        ];

        $views['user']['advance'] = [
            'value' => is_null($User->advance()) ? '' : $User->advance()->format(),
            'class' => is_null($User->advance()) ? '' : Price::getStatus($User->advance()->status()->get())
        ];

        $views['options'] = getHeader($ServiceModel);

        $views['titles'] = [
            'page' => (is_null($ServiceModel->options()->name())
                ? 'Repartition'
                : $ServiceModel->options()->name()->get()) . ' - ' . $User->name()->get(),
            'header1' => is_null($User->name())     ? '': $User->name()->get(),
            'header2' => 'Résumé'
        ];

        $this
            ->set($views)
            ->add('index')
            ->history($User);
    }

    /**
     * get layout historique
     * @param  User $User user needed
     * @return void
     */
    protected function history ($User) {
        $bills = [
            'line'=> [],
            'total'=> new Price (),
        ];

        $lines = [];
        // get all bills
        Main::each($User->bill(), function ($Bill) use (&$bills, &$lines)
        {
            $lines[] = [
                'price' => [
                    'value' => $Bill->format(),
                    'class' => Price::getStatus($Bill->status()->get()),
                ],
                'date' => $Bill->date()->format('d/m/Y'),
                'url' => URL . '/bill/' . $Bill->date()->format('Ymd')
            ];
            $bills['total']->set($Bill);
        });

        // return array ti have lastest bills
        $bills['line'] = array_reverse($lines);

        $bills['total'] = $bills['total']->get() == 0 ? '' : $bills['total']->format();
        $views['bills'] = $bills;
        $this
            ->set($views)
            ->add('history');
    }
}
