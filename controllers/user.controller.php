<?php

require_once MODELS_ROOT . 'service.model.php';
class UserController extends Controller
{

    public function index ($userId = 0) {
        $Model = new serviceModel ();
        $Model
            ->load('user',    DATAS_ROOT . '/user.json')
            ->load('price',   DATAS_ROOT . '/price.json')
            ->load('options', DATAS_ROOT . '/options.json');
        $Netflix = $Model->get();

        if (!is_numeric($userId) && $userId === 0 ) {
            return Self::fourOFour();
        }

        $userId = intval($userId);

        $User = $Netflix->getId(intval($userId));

        if ($User == null) {
            return Self::fourOFour();
        }

        $views = [];

        $views['title'] = is_null($Netflix->name()) ? 'Repartition' : $Netflix->name()->get() . ' - ' . $User->name()->get();



        $views['user'] = [
             'name' => is_null($User->name()) ? '': $User->name()->get(),
             'payed' => is_null($User->payed()) ? '': $User->payed()->format(),
             'unpayed' => is_null($User->unpayed()) ? '': $User->unpayed()->format(),
             'advance' => is_null($User->advance()) ? '': $User->advance()->format(),
             'bill' => $this->getBillHistory($User)
        ];

        $this->set($views)->render('index');
    }

    /**
     * get layout historique
     * @param  [type] $User [description]
     * @return [type]       [description]
     */
    protected function getBillHistory ($User) {
        $bills = [
            'line'=> [],
            'total'=> new Price (),
        ];

        Main::each($User->bill(), function ($Bill) use (&$bills)
        {
            $bills['line'][] = [
                'price' => $Bill->format(),
                'date' => $Bill->date()->format('d/m/Y'),
                'url' => URL . '/bill/' . $Bill->date()->format('Ymd')
            ];
            $bills['total']->set($Bill);
        });

        $bills['total'] = $bills['total']->get() == 0 ? '' : $bills['total']->format();
        return $bills;
    }
}
