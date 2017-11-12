<?php
require_once CLASSES_ROOT . '/class.main.php';

function rounder ($difference)
{
    if ($difference > 0) {
        $difference = ceil( ($difference) * 100) / 100;
    } else {
        $difference = floor( ($difference) * 100) / 100;
    }
    return $difference;
}


/**
 * get model
 * @return Service get netflix
 */
function controller (Service $Service) {

    $Start = $Service->getStart();
    $Duration = (new Interval ())->start($Start);

    $rotation = [];


    for ($currentMonth = 1; $currentMonth <= $Duration->month(); $currentMonth++) {

        $dateCurrent  = clone $Start;

        // add month
        $dateCurrent->modify('+' . $currentMonth . ' month');

        // get current price of service
        $currentTarif = $Service->getActiveTarif($dateCurrent);

        // price repartition by month
        $totalUser = $Service->getActiveUsersNumber($dateCurrent);

        // Calc right price for current month
        // ----------
        // get approximative price and apply to each one
        $repartitionAprox = rounder($currentTarif->get() / $totalUser);

        $userRepartition = [];
        for ($i = $totalUser - 1; $i >= 0; $i--) {
            $userRepartition[$i] = rounder($repartitionAprox);
        }

        $difference = $currentTarif->get() - array_sum($userRepartition);
        $difference = round($difference*100)/100;


        if ($difference < 0) {
            for ($i = $totalUser - 1; $i >= 0; $i--) {
                $numberNeeded = $difference / $totalUser;
                $numberNeededRounded = rounder($numberNeeded);

                $difference -= $numberNeededRounded;
                $userRepartition[$i] += $numberNeededRounded;
            }
        } else {
            for ($i = 0; $i < $totalUser; $i++) {
                $numberNeeded = $difference / $totalUser;
                $numberNeededRounded = rounder($numberNeeded);

                $difference -= $numberNeededRounded;
                $userRepartition[$i] += $numberNeededRounded;
            }
        }

        ksort($userRepartition);


        // Applying bill
        $i = 0;
        Main::each($Service->user(), function ($User) use ($userRepartition, $dateCurrent, &$i)
        {
            Main::each($User->interval(), function ($Interval) use ($userRepartition, $dateCurrent, &$User, &$i)
            {

                if ($Interval->between($dateCurrent)) {
                    $date = clone $dateCurrent;
                    $User->bill( (new Price ())->set($userRepartition[$i])->date($date) );
                    $i++;
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
