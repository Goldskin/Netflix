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
        $total = new Price ();
        foreach ($Users as $User) {
            $Bill = $User->getBill($Date);
            $Bills[] = [
                'user' => $User->name()->get(),
                'bill' => $Bill->format(),
                'url'  => (is_null($User->id()) ? '#' : URL . '/user/' . $User->id())
            ];
            $total->set($Bill);
        }

        $views = [
            'lines' => $Bills,
            'date' => $Date->format('d/m/Y'),
            'total' => $total->format()
        ];

        $this->set($views)->render('index');
    }
}
