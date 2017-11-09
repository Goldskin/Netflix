<?php
ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

require_once 'class.main.php';

$file = file_get_contents('./data.json');
$loutres = json_decode($file);


$users = [];
foreach ($loutres as $data) {
    $User = (new User ())->name($data->name);

    // determine les utilisations
    if (isset($data->use)) {
        foreach ($data->use as $use) {
            $Interval = (new Interval ())->start($use[0]);
            if (isset($use[1])) $Interval->end($use[1]);
            $User->interval($Interval);
        }
    }

    // determine les utilisations
    if (isset($data->payed)) {
        foreach ($data->payed as $payed) {
            $User->payment( (new Payment ())->price($payed[0])->date($payed[1]) );
        }
    }
    $users[] = $User;
}

$file = file_get_contents('./price.json');
$prices = json_decode($file);
$netflix = [];
foreach ($prices as $data) {
    $Interval = (new Interval ())->start($data->use[0]);
    if (isset($data->use[1])) $Interval->end($data->use[1]);
    $tarif = (new Tarif ())->price( $data->price )->interval( $Interval );
    $netflix[] = $tarif;
}
