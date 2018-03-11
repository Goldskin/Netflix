<?php

require_once MODELS_ROOT . 'service.model.php';
require_once CONTROLLERS_ROOT . 'all.php';
class BillController extends Controller
{
    public function index ($date = null)
    {
        $ServiceModel = (new serviceModel ())->getModel();

        $Date = new Date ($date);

        $Users = $ServiceModel->getActiveUsers($Date);
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
                'user'  => $User->name()->get(),
                'price' => [
                    'value' => $Bill->format(),
                    'class' => Price::getStatus($Bill->status()->get())
                ],
                'url'   => (is_null($User->id()) ? '#' : URL . '/user/' . $User->id())
            ];

            $total->set($Bill);
        }

        $views = [
            'titles' => [
                'page' => (is_null($ServiceModel->options()->name())
                    ? 'Repartition'
                    : $ServiceModel->options()->name()->get()) . ' - Facture du ' . $Date->format('d/m/Y'),
                'header1' => 'Facture du ' . $Date->format('d/m/Y') ,
            ],
            'lines' => $Bills,
            'total' => $total->format()
        ];

        $views['options'] = getHeader($ServiceModel);

        $this
            ->set($views)
            ->add('index');
    }
}
