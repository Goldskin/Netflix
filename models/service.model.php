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
        $prices  = json_decode($this->price);
        $options = json_decode($this->options);

        $Service = new Service ();

        foreach ($user as $Data) {
            $User = (new User ())->name($Data->name);

            // add all usages
            if (isset($Data->use)) {
                foreach ($Data->use as $Use) {
                    $Interval = (new Interval ())->start( $Use->start );

                    // if user allready finished the session
                    if (isset($Use->end)) {
                        $Interval->end( $Use->end );
                    }

                    $User->interval($Interval);
                }
            }

            // check if current user is admin
            if (isset($Data->admin)) {
                $User->admin($Data->admin);
            }

            // add all payments
            if (isset($Data->payed)) {
                foreach ($Data->payed as $Payment) {
                    $User->payment( (new Price ())->set( $Payment->price )->date( $Payment->date ) );
                }
            }

            // add user
            $Service->user($User);
        }

        // add all
        foreach ($prices as $Data) {
            $Interval = (new Interval ())->start($Data->start);

            if (isset($Data->end)) {
                $Interval->end($Data->end);
            }

            $Tarif = (new Price ())->set( $Data->price )->interval( $Interval );
            $Service->price($Tarif);
        }

        // generate bills
        $Service->createBills();

        return $Service;
    }
}
