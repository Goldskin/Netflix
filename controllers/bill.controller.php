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

        $allReadyDisplayed = [];

        foreach ($Users as $User) {

            $Bill = $User->getBill($Date);

            // bug if multi line for the same user
            if (is_array($Bill)) {
                while (count($Bill)) {
                    if (array_search($Bill[0]->id(), $allReadyDisplayed) !== false) {
                        array_shift($Bill);
                    } else {
                        $allReadyDisplayed[] = $Bill[0]->id();
                        $Bill = $Bill[0];
                        break;
                    }
                }
            }

            $Bills[] = [
                'user' => $User->name()->get(),
                'bill' => $Bill->format(),
                'url'  => (is_null($User->id()) ? '#' : URL . '/user/' . $User->id())
            ];

            $total->set($Bill);
        }

        $views = [
            'title' => (is_null($Netflix->name()) ? 'Repartition' : $Netflix->name()->get()) . ' - Facture du ' . $Date->format('d/m/Y'),
            'lines' => $Bills,
            'date' => $Date->format('d/m/Y'),
            'total' => $total->format()
        ];

        $this
            ->set($views)
            ->add('index');
    }
}
