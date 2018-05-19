<?php

require_once MODELS_ROOT . 'service.model.php';
require_once CONTROLLERS_ROOT . 'all.php';
class BillController extends Controller
{
    public function index ($id = null)
    {
        $ServiceModel = (new serviceModel ())->getModel();

        $bill = $ServiceModel->getId($id);
        $Date = $bill->date();
        $invoices = $bill->invoice();
        $lines = [];

        foreach ($invoices as $invoice) {

            $User = $invoice->user();

            $lines[] = [
                'user'  => $User->name()->get(),
                'price' => [
                    'value' => $invoice->format(),
                    'class' => Price::getStatus($invoice->status()->get())
                ],
                'url'   => (is_null($User->id) ? '#' : URL . '/user/' . $User->id)
            ];
        }

        $views = [
            'titles' => [
                'page' => (is_null($ServiceModel->options()->name())
                    ? 'Repartition'
                    : $ServiceModel->options()->name()->get()) . ' - Facture du ' . $Date->format('d/m/Y'),
                'header1' => 'Facture du ' . $Date->format('d/m/Y') ,
            ],
            'lines' => $lines,
            'total' => $bill->format()
        ];

        $views['options'] = getHeader($ServiceModel);

        $this
            ->set($views)
            ->add('index');
    }
}
