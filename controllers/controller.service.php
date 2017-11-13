<?php
require_once CLASSES_ROOT . '/class.main.php';

/**
 * get model
 * @return Service get netflix
 */
function controller (Service $Service)
{

    $Start = $Service->getStart();
    $Duration = (new Interval ())->start($Start);
    $rotation = [];

    for ($currentMonth = 1; $currentMonth <= $Duration->month(); $currentMonth++) {

        $dateCurrent  = new Date ($Start->format('Ymd'));

        // add month
        $dateCurrent->modify('+' . $currentMonth . ' month');

        // get current price of service
        $currentTarif = $Service->getActiveTarif($dateCurrent);

        // get active user
        $Users      = $Service->getActiveUsers($dateCurrent);
        $totalUser  = count($Users);

        // get split bill
        $userRepartition = $currentTarif->split($totalUser);

        // Applying bill
        foreach ($Users as $key => $User) {
            $User->bill( (new Price ())->set($userRepartition[$key])->date($dateCurrent) );
        }

    }

    $Service->update();

    return $Service;
}
