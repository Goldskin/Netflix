<?php

require_once MODELS_ROOT . 'service.model.php';
class BillController extends Controller
{

    public function index ($date = null) {
        $Model = new serviceModel ();
        $Model
            ->load('user',    DATAS_ROOT . '/user.json')
            ->load('price',   DATAS_ROOT . '/price.json')
            ->load('options', DATAS_ROOT . '/options.json');
        $Netflix = $Model->get();

        $Date = new Date ($date);



        $Users = $Netflix->getActiveUsers($Date);
        $Bills = [];
        $total = 0;
        foreach ($Users as $User) {
            $Bill = $User->getBill($Date);
            $Bills[] = [
                'user' => $User->name()->get(),
                'bill' => $Bill->format()
            ];
            $total += $Bill->get();
        }

        $views = [
            'lines' => $Bills,
            'date' => $Date->format('d/m/Y'),
            'total' => $total
        ];

        $this->set($views)->render('index');
    }
}
