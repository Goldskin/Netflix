<?php
require_once CLASSES_ROOT . 'main.class.php';
/**
 *
 */
class serviceModel
{

    public function load ($type, $file) {
        $this->$type = file_get_contents($file);
        return $this;
    }

    /**
     * get model
     * @return Service get netflix
     */
    public function get ()
    {
        $user    = json_decode($this->user);
        $tarif   = json_decode($this->price);
        $options = json_decode($this->options);

        // creat new service
        $Service = new Service ();

        $Service->createOptions($options);

        // add all users
        $Service->createUser($user);

        // add all tarif
        $Service->createTarif($tarif);

        // generate bills
        $Service->createBills();

        return $Service;
    }
}
