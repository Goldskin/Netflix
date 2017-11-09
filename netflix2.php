<?php


// old

 $debut = new DateTime('2017-07-28');
 $today = new DateTime();
 $loutres = [
     'Charles' => [
         'start' => $debut
     ],
     'Régis' => [
         'start' => $debut,
         'payed' => [
             '24/09/2017' => 44.99
         ]
     ],
     'Gabriel' => [
         'start' => new DateTime('2018-01-28')
     ]
 ];



 $totalMonthDate = $debut->diff($today);
 $totalMonth     = $totalMonthDate->format('%r%m') + $totalMonthDate->format('%r%y') * 12;
 $prixNetflix    = 11.99;
 $prixNetflix    = 13.99;
 $prixPaye       = $totalMonth * $prixNetflix;
 $views          = [];


 $personnesParMois = [];
 $prixParMois = [];


 for ($currentMonth = 1; $currentMonth <= $totalMonth; $currentMonth++) {

     $dateCurrent = clone $debut;
     $dateCurrent->modify('+' . $currentMonth . ' month');
     $prixParMois[$currentMonth]['people'] = 0;

     // tri de prix par mois
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

     // ajout de prix par personnes
     foreach ($loutres as $nom => $loutre) {
         $since = $loutre['start']->diff($dateCurrent);
         if (($total = $since->format('%r%m') + $since->format('%r%y') * 12) > 0) {
             if (!isset($loutres[$nom]['total'])) {
                 $loutres[$nom]['total'] = 0;
             }
             $loutres[$nom]['total'] += $prixNetflix / $prixParMois[$currentMonth]['people'];
         }
     }

 }


 foreach ($loutres as $nom => $loutre) {
     if (isset($loutre['since'])) {
         $avance = $paye = $restant = $avance = 0;

         // total payé
         if (isset($loutre['payed'])) {
             foreach ($loutre['payed'] as $date => $prix) {
                 $loutres[$nom]['total'] -= $prix;
                 $paye += $prix;
             }
         }

         // total restant Ã  payé si le prix est positif
         if ($loutres[$nom]['total'] >= 0) {
             $restant = abs($loutres[$nom]['total']);
         } else {
             // total avance si le prix payé est positif
             $avance = abs($loutres[$nom]['total']);
         }

         $views[] = [
             'nom' => $nom,
             'payed' => number_format($paye, 2),
             'restant' => number_format($restant, 2),
             'avance' => number_format($avance, 2)
         ];
     }

 }

 ?><!DOCTYPE html>
 <html>
     <head>
         <meta charset="utf-8">
         <title>Netflix</title>
         <!-- <link rel="stylesheet" href="https:maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
         <link rel="stylesheet" href="https:cdn.jsdelivr.net/foundation/6.2.4/foundation.min.css">
         <style media="screen">
             img {
                 max-width: 400px;
                 display: block;
                 margin: 50px auto;
             }
             h1 {
                 font-size: 2rem;
                 text-align: center;
             }
         </style>
     </head>
     <body class="container">
         <div class="row">
             <div class="columns">
                 <img src="https:cdn.worldvectorlogo.com/logos/netflix-2.svg" alt="Netflix">
             </div>
             <div class="columns">
                 <h1>Total de mois payés : <?= $totalMonth ?></h1>
             </div>
             <div class="columns">
                 <table class="table ">
                     <thead class="thead-inverse">
                         <tr>
                             <th>Personnes</th>
                             <th>Payé</th>
                             <th>Restant Ã  payer</th>
                             <th>Avance</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php foreach ($views as $key => $view): ?>
                             <tr>
                                 <td><?= $view['nom'] ?></td>
                                 <td><?= $view['payed'] ?></td>
                                 <td><?= $view['restant'] ?></td>
                                 <td><?= $view['avance'] ?></td>
                             </tr>
                         <?php endforeach; ?>
                     </tbody>
                 </table>
             </div>
         </div>

     </body>
 </html>
