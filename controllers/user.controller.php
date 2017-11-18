<?php

require_once MODELS_ROOT . 'service.model.php';
class UserController extends Controller
{

    public function index ($userId = 0) {
        $Netflix = model();

        if (!is_numeric($userId) && $userId === 0 ) {
            return Self::fourOFour();
        }

        $userId = intval($userId);

        $User = $Netflix->getId(intval($userId));

        if ($User == null) {
            return Self::fourOFour();
        }


        $views = [];

        $bills = [
            'line'=> [],
            'total'=> 0,
        ];

        Main::each($User->bill(), function ($Bill) use (&$bills)
        {
            $bills['line'][] = [
                'price' => $Bill->format(),
                'date' => $Bill->date()->format('d/m/Y')
            ];
            $bills['total'] += $Bill->get();
        });

        $bills['total'] = $bills['total'] == 0 ? '' : $bills['total'] . ' &euro;';

        $views['user'] = [
             'name' => is_null($User->name()) ? '': $User->name()->get(),
             'payed' => is_null($User->payed()) ? '': $User->payed()->format(),
             'unpayed' => is_null($User->unpayed()) ? '': $User->unpayed()->format(),
             'advance' => is_null($User->advance()) ? '': $User->advance()->format(),
             'bill' => $bills
        ];

        $this->set($views)->render('index');
    }
}
