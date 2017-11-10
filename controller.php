<?php
require_once 'class.main.php';
/**
 * get model
 * @return Service get netflix
 */
function controller (Service $Service) {

    $Start = $Service->getStart();
    $Interval = (new Interval ())->start( $Start);


    for ($currentMonth = 1; $currentMonth <= $Interval->getMonth(); $currentMonth++) {

        $dateCurrent = clone $Start;
        $dateCurrent->modify('+' . $currentMonth . ' month');
        $prixParMois[$currentMonth]['people'] = 0;

        // // tri de prix par mois
        foreach ($loutres as $nom => $loutre) {
            $since = $loutre['start']->diff($dateCurrent);
            if (($total = $since->format('%r%m') + $since->format('%r%y') * 12) > 0) {
                if (!isset($loutres[$nom]['since'])) {
                    $loutres[$nom]['since'] = 0;
                }
                $loutres[$nom]['since']++;
                $personnesParMois[$currentMonth][] = $nom;
                $prixParMois[$currentMonth]['people']++;
            }
        }

        // // ajout de prix par personnes
        // foreach ($loutres as $nom => $loutre) {
        //     $since = $loutre['start']->diff($dateCurrent);
        //     if (($total = $since->format('%r%m') + $since->format('%r%y') * 12) > 0) {
        //         if (!isset($loutres[$nom]['total'])) {
        //             $loutres[$nom]['total'] = 0;
        //         }
        //         $loutres[$nom]['total'] += $prixNetflix / $prixParMois[$currentMonth]['people'];
        //     }
        // }

    }
    echo '<pre>', var_dump( $currentMonth ), '</pre>';


    // foreach ($loutres as $nom => $loutre) {
    //     if (isset($loutre['since'])) {
    //         $avance = $paye = $restant = $avance = 0;
    //
    //         // total payé
    //         if (isset($loutre['payed'])) {
    //             foreach ($loutre['payed'] as $date => $prix) {
    //                 $loutres[$nom]['total'] -= $prix;
    //                 $paye += $prix;
    //             }
    //         }
    //
    //         // total restant Ã  payé si le prix est positif
    //         if ($loutres[$nom]['total'] >= 0) {
    //             $restant = abs($loutres[$nom]['total']);
    //         } else {
    //             // total avance si le prix payé est positif
    //             $avance = abs($loutres[$nom]['total']);
    //         }
    //
    //         $views[] = [
    //             'nom' => $nom,
    //             'payed' => number_format($paye, 2),
    //             'restant' => number_format($restant, 2),
    //             'avance' => number_format($avance, 2)
    //         ];
    //     }
    //
    // }
}
