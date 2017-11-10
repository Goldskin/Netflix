<?php
ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

require_once 'class.main.php';

$file = file_get_contents('./user.json');
$loutres = json_decode($file);
$file = file_get_contents('./price.json');
$prices = json_decode($file);


$Netflix = new Service ();
foreach ($loutres as $Data) {
    
    $User = (new User ())->name($Data->name);

    // determine les utilisations
    if (isset($Data->use)) {
        foreach ($Data->use as $Use) {
            $Interval = (new Interval ())->start( $Use->start );
            if (isset($Use->end)) $Interval->end( $Use->end );
            $User->interval($Interval);
        }
    }

    // determine les utilisations
    if (isset($Data->payed)) {
        foreach ($Data->payed as $Payment) {
            $User->payment( (new Payment ())->price( $Payment->price )->date( $Payment->date ) );
        }
    }
    
    // $users[] = $User;
    $Netflix->user($User);
}


foreach ($prices as $Data) {
    $Interval = (new Interval ())->start($Data->start);
    if (isset($Data->end)) $Interval->end($Data->end);
    $Tarif = (new Tarif ())->price( $Data->price )->interval( $Interval );
    $Netflix->tarif($Tarif);
}

echo '<pre>', var_dump( $Netflix ), '</pre>';
