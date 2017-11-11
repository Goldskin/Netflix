<?php
require_once 'class.main.php';
/**
 * get model
 * @return Service get netflix
 */
function controller (Service $Service) {

    $Start = $Service->getStart();
    $Duration = (new Interval ())->start($Start);


    for ($currentMonth = 1; $currentMonth <= $Duration->month(); $currentMonth++) {

        $dateCurrent  = clone $Start;
        $dateCurrent->modify('+' . $currentMonth . ' month');
        $totalUser    = 0;
        $currentTarif = null;

        // Calc right price for current month
        Main::each($Service->tarif(), function ($Tarif) use (&$currentTarif, $dateCurrent)
        {
            Main::each($Tarif->interval(), function ($Interval) use (&$currentTarif, $Tarif, $dateCurrent)
            {
                if ($Interval->between($dateCurrent)) {
                    return ($currentTarif = $Tarif);
                }
            });
        });


        // price repartition by month
        Main::each($Service->user(), function ($User) use (&$totalUser, $dateCurrent)
        {
            Main::each($User->interval(), function ($Interval) use (&$totalUser, $dateCurrent)
            {
                if ($Interval->between($dateCurrent)) {
                    $totalUser++;
                }
            });
        });

        $billPrice = $currentTarif->price()->get() / $totalUser;

        // Applying bill
        Main::each($Service->user(), function ($User) use ($billPrice, $dateCurrent)
        {
            Main::each($User->interval(), function ($Interval) use ($billPrice, $dateCurrent, $User)
            {
                if ($Interval->between($dateCurrent)) {
                    $date = clone $dateCurrent;
                    $User->bill( (new Bill ())->price($billPrice)->date($date) );
                }
            });
        });


    }

    Main::each($Service->user(), function ($User)
    {
        $User->update();
    });

    return $Service;
}
