<?php
require_once CLASSES_ROOT . 'main.class.php';
/**
 *
 */
class serviceModel
{

    protected function load ($type, $file) {
        $this->$type = file_get_contents($file);
        return $this;
    }

    /**
     * get model
     * @return Service get netflix
     */
    protected function get ()
    {
        $user    = json_decode($this->user);
        $tarif   = json_decode($this->price);
        $options = json_decode($this->options);

        // creat new service
        $Service = new Service ();

        // get options
        $Service->createOptions($options);

        // add all users
        $Service->createUser($user);

        // add all tarif
        $Service->createTarif($tarif);
        
        // generate bills
        $Service->createBills();

        return $Service;
    }

    /**
     * load model
     * @return Service current service
     */
    public function getModel () {
        $Model = new serviceModel ();
        $Model
            ->load('user',    DATAS_ROOT . 'user.json')
            ->load('price',   DATAS_ROOT . 'price.json')
            ->load('options', DATAS_ROOT . 'options.json');
        return $Model->get();
    }
}
