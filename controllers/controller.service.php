<?php
require_once CLASSES_ROOT . '/class.main.php';
/**
 * get model
 * @return Service get netflix
 */
function controller (Service $Service) {

    $Start = $Service->getStart();
    $Duration = (new Interval ())->start($Start);


    for ($currentMonth = 1; $currentMonth <= $Duration->month(); $currentMonth++) {

        $dateCurrent  = clone $Start;

        // add month
        $dateCurrent->modify('+' . $currentMonth . ' month');

        // get current price of service
        $currentTarif = $Service->getActiveTarif($dateCurrent);

        // price repartition by month
        $totalUser = $Service->getActiveUsersNumber($dateCurrent);

        // Calc right price for current month


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


        // split price
        $billPrice = $currentTarif->get() / $totalUser;

        // Applying bill
        Main::each($Service->user(), function ($User) use ($billPrice, $dateCurrent)
        {
            Main::each($User->interval(), function ($Interval) use ($billPrice, $dateCurrent, $User)
            {
                if ($Interval->between($dateCurrent)) {
                    $date = clone $dateCurrent;
                    $User->bill( (new Price ())->set($billPrice)->date($date) );
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
