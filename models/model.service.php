<?php
require_once CLASSES_ROOT . '/class.main.php';
/**
 * get model
 * @return Service get netflix
 */
function model () {
    $file    = file_get_contents(DATAS_ROOT . '/user.json');
    $loutres = json_decode($file);
    $file    = file_get_contents(DATAS_ROOT . '/price.json');
    $prices  = json_decode($file);


    $Service = new Service ();
    foreach ($loutres as $Data) {
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


    return $Service;
}
